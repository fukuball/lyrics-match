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

# 從資料庫抓資料
cur = db.cursor()
cur.execute("SELECT * FROM music_feature_matrix WHERE id=1")

music_feature_matrix = "";
row_song_id = "";
column_music_feature = "";

for row in cur.fetchall() :
   music_feature_matrix = row[1];
   row_song_id = row[2];
   column_music_feature = row[3];

# music feature matrix
A_music_feature_matrix = np.matrix(music_feature_matrix)
print A_music_feature_matrix
print( "matrix shape --> %d rows x %d columns" % A_music_feature_matrix.shape )

# SVD decomposition
music_feature_U,music_feature_s,music_feature_V = np.linalg.svd(A_music_feature_matrix, full_matrices=False)

# 降維
for s_index, s_item in enumerate(music_feature_s) :
   if (s_item<10000) :
      music_feature_s[s_index] = 0.0

# music feature model matrix
music_feature_s = np.diag(music_feature_s)
A_bar_music_feature_matrix = np.dot(music_feature_U,np.dot(music_feature_s,music_feature_V))
print A_bar_music_feature_matrix
print( "matrix shape --> %d rows x %d columns" % A_bar_music_feature_matrix.shape )