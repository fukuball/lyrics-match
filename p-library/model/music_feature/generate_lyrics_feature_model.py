#!/usr/bin/python26
# -*- coding:utf-8 -*-
#
# generate_lyrics_feature_model.py to generate lyrics feature model
#
# Python version 2.6.8
#
# @category Python
# @package  /p-library/model/
# @author   Fukuball Lin <fukuball@gmail.com>
# @license  No Licence
# @version  Release: <1.0>
# @link     http://sarasti.cs.nccu.edu.tw
#
# hard code

import sys
import numpy as np
import MySQLdb as mysql
import json
np.set_printoptions(threshold=np.nan)

sys.path.append("/var/www/html/lyrics-match/p-library/model")
import ImportPath
ImportPath.Import()

import db_stage
CONST = db_stage._Const()

# connect to db
db = mysql.connect(host    = CONST.DBHOST,
                   user    = CONST.DBUSER,
                   passwd  = CONST.DBPASS,
                   db      = CONST.DBNAME,
                   charset = 'UTF8')

# 從資料庫抓資料
cur = db.cursor()
cur.execute("SET NAMES UTF8")
cur.execute("SET CHARACTER_SET_CLIENT=UTF8")
cur.execute("SET CHARACTER_SET_RESULTS=UTF8")
db.commit()

cur.execute("SELECT * FROM lyrics_feature_matrix WHERE id=1")


lyrics_feature_matrix = ""
row_song_id = ""
column_lyrics_feature = ""
create_time = ""
modify_time = ""

for row in cur.fetchall() :
   lyrics_feature_matrix = row[1]
   row_song_id = row[2]
   column_lyrics_feature = row[3]
   create_time = row[6]
   modify_time = row[7]

# lyrics feature matrix
# model
A_lyrics_feature_matrix = np.matrix(json.loads(lyrics_feature_matrix))

print "matrix formed"
#print A_lyrics_feature_matrix
print( "matrix shape --> %d rows x %d columns" % A_lyrics_feature_matrix.shape )

# SVD decomposition
lyrics_feature_U,lyrics_feature_s,lyrics_feature_V = np.linalg.svd(A_lyrics_feature_matrix, full_matrices=False)

print "SVD performed"
#print lyrics_feature_s

# 降維
for s_index, s_item in enumerate(lyrics_feature_s) :
   if (s_item<100) :
      lyrics_feature_s[s_index] = 0.0

print "dedimenstion performed"

# lyrics feature model matrix
lyrics_feature_s = np.diag(lyrics_feature_s)
#print lyrics_feature_s

A_bar_lyrics_feature_matrix = np.dot(lyrics_feature_U,np.dot(lyrics_feature_s,lyrics_feature_V))
#print A_bar_lyrics_feature_matrix
print( "matrix shape --> %d rows x %d columns" % A_bar_lyrics_feature_matrix.shape )

print "reform matrix"

A_bar_list = A_bar_lyrics_feature_matrix.tolist()
A_bar_string = json.dumps(A_bar_list)

print "matrix dump"

f = open('lyrics-model-1.txt', 'w')
f.write(A_bar_string)
f.close()

cur = db.cursor()
try:
   cur.execute("""INSERT INTO lyrics_feature_matrix (matrix, row_song_id, column_lyrics_feature, type, create_time, modify_time) VALUES (%s, %s, %s, %s, %s, %s)""",("lyrics-model-1.txt", row_song_id, column_lyrics_feature, "model", create_time, modify_time))
   db.commit()
   print "success"
except mysql.Error, e:
   db.rollback()
   print "An error has been passed. %s" %e

print "save in db"


cur.close()
db.close()
