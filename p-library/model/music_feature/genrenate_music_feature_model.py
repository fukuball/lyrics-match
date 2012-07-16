#!/usr/bin/python26
# -*- coding:utf-8 -*-

import sys
import numpy as np
import MySQLdb as mysql

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

cur = db.cursor()
cur.execute("SELECT * FROM music_feature_matrix WHERE id=1")

music_feature_matrix = "";
row_song_id = "";
column_music_feature = "";

for row in cur.fetchall() :

   music_feature_matrix = row[1];
   row_song_id = row[2];
   column_music_feature = row[3];

A_music_feature_matrix = np.matrix(music_feature_matrix)

print A_music_feature_matrix
print( "shape --> %d rows x %d columns" % A_music_feature_matrix.shape )

U,s,V = np.linalg.svd(A_music_feature_matrix) # SVD decomposition

print U
print( "shape --> %d rows x %d columns" % U.shape )

print s
#print( "shape --> %d rows x %d columns" % s.shape )

print V
print( "shape --> %d rows x %d columns" % V.shape )

#
#
#A = np.floor(np.random.rand(4,4)*20-10) # generating a random
#b = np.floor(np.random.rand(4,1)*20-10) # system Ax=b
#
#U,s,V = np.linalg.svd(A) # SVD decomposition of A
#
#print U