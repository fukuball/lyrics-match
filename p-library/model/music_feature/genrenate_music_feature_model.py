#!/usr/bin/python26
# -*- coding:utf-8 -*-
#
# generate_music_feature_model.py to generate music feature model
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
                   db      = CONST.DBNAME)

# 從資料庫抓資料
cur = db.cursor()
cur.execute("SELECT * FROM music_feature_matrix WHERE id=1")

music_feature_matrix = "";
row_song_id = "";
column_music_feature = "";
augment_music_feature = "";
augment_matrix = "";
create_time = "";
modify_time = "";
delete_time = "";

for row in cur.fetchall() :
   music_feature_matrix = row[1];
   row_song_id = row[2];
   column_music_feature = row[3];
   augment_music_feature = row[4];
   augment_matrix = row[5];
   create_time = row[7];
   modify_time = row[8];
   delete_time = row[9];

# music feature matrix
A_music_feature_matrix = np.matrix(music_feature_matrix)
#print A_music_feature_matrix
#print( "matrix shape --> %d rows x %d columns" % A_music_feature_matrix.shape )

# SVD decomposition
music_feature_U,music_feature_s,music_feature_V = np.linalg.svd(A_music_feature_matrix, full_matrices=False)

# 降維
#for s_index, s_item in enumerate(music_feature_s) :
#   if (s_item<1000) :
#      music_feature_s[s_index] = 0.0

# music feature model matrix
music_feature_s = np.diag(music_feature_s)
print music_feature_U
print( "music_feature_U shape --> %d rows x %d columns" % music_feature_U.shape )
print music_feature_s
print( "music_feature_s shape --> %d rows x %d columns" % music_feature_s.shape )
print music_feature_V
print( "music_feature_V shape --> %d rows x %d columns" % music_feature_V.shape )

A_bar_music_feature_matrix = np.dot(music_feature_U,np.dot(music_feature_s,music_feature_V))
#print A_bar_music_feature_matrix
#print( "matrix shape --> %d rows x %d columns" % A_bar_music_feature_matrix.shape )

#music_feature_matrix_subtract = np.subtract(A_music_feature_matrix, A_bar_music_feature_matrix)
#print np.extract(music_feature_matrix_subtract>10, music_feature_matrix_subtract)

A_bar_list = A_bar_music_feature_matrix.tolist()
A_bar_string = json.dumps(A_bar_list)

#try:
#   cur.execute("""INSERT INTO music_feature_matrix (matrix,row_song_id,column_music_feature,augment_music_feature,augment_matrix,type,create_time,modify_time,delete_time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)""",(A_bar_string, row_song_id, column_music_feature, augment_music_feature, augment_matrix, "model", create_time, modify_time, delete_time))
#   db.commit()
#   print "success"
#except mysql.Error, e:
#   db.rollback()
#   print "An error has been passed. %s" %e