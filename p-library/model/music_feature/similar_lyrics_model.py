#!/usr/bin/python26
# -*- coding:utf-8 -*-
#
# similar_music_model.py to get similar song id
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

song_id = "1"
model_id = 4

cur.execute("""SELECT * FROM lyrics_feature_matrix WHERE id=%s""", (model_id))

lyrics_feature_matrix_path = ""
row_song_id = ""
column_music_feature = ""
has_model_data = "false"

for row in cur.fetchall() :
   lyrics_feature_matrix_path = row[1];
   row_song_id = row[2];
   column_music_feature = row[3];
   has_model_data = "true"

if (has_model_data=="true") :

   song_id_array = row_song_id.split(',')

   # model
   f = open(lyrics_feature_matrix_path, 'r')
   lyrics_feature_matrix = f.read()
   lyrics_feature_matrix = np.matrix(json.loads(lyrics_feature_matrix))
   f.close()

   similar_music_model = lyrics_feature_matrix
   #print similar_music_model
   print( "matrix shape --> %d rows x %d columns" % similar_music_model.shape )

   normalize_min = similar_music_model.getA().min(axis=0)
   normalize_range = similar_music_model.getA().ptp(axis=0)
   similar_music_model_normalized = (similar_music_model.getA() - normalize_min) / normalize_range

   cur.execute("""SELECT id FROM song WHERE lyric!='' LIMIT 1""")

   for row in cur.fetchall() :

      song_id = row[0]

      input_song_feature_key = song_id_array.index(song_id)
      input_song_matrix = similar_music_model.getA()[input_song_feature_key]
      input_song_matrix_normalized = (input_song_matrix - normalize_min) / normalize_range

      similar_music_dic = {}
      for music_feature_index, music_feature_value in enumerate(similar_music_model_normalized):
         similar_music_dic[song_id_array[music_feature_index]] = np.dot(music_feature_value, input_song_matrix_normalized)/(np.linalg.norm(music_feature_value)*np.linalg.norm(input_song_matrix_normalized))

      similar_music_sort_dic = list(sorted(similar_music_dic, key=similar_music_dic.__getitem__, reverse=True))

      similar_song_string = ""
      for similar_song_id in similar_music_sort_dic :
         similar_song_string += similar_song_id+":"+str(similar_music_dic[similar_song_id])+","

      similar_song_string = similar_song_string[:-1]
      print similar_song_string