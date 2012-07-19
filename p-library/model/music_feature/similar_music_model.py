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
                   db      = CONST.DBNAME)

song_id = sys.argv[1];
model_id = sys.argv[2];

cur = db.cursor()

#cur.execute("""SELECT s.mode, s.tempo, s.time_signature, s.energy, s.danceability, s.speechiness, s.loudness, mf.bar_count, mf.beat_count, mf.tatum_count, mf.section_count, mf.segment_count, mf.bar_avg_second, mf.beat_avg_second, mf.tatum_avg_second, mf.section_avg_second, mf.segment_avg_second, mf.pitch_avg_vector, mf.timbre_avg_vector, mf.pitch_std_vector, mf.timbre_std_vector FROM music_feature mf INNER JOIN song s ON (mf.song_id = s.id) WHERE mf.is_deleted = '0' AND s.is_deleted ='0' AND mf.song_id=%s ORDER BY mf.id""", (song_id))
#
#has_feature_data = "false"
#song_music_feature_str = ""
#for row in cur.fetchall() :
#   if (row[0]==1) :
#      song_music_feature_str += "10 "
#   else :
#      song_music_feature_str += "-10 "
#   song_music_feature_str += str(row[1])+" "
#   song_music_feature_str += str(row[2])+" "
#   song_music_feature_str += str(row[3])+" "
#   song_music_feature_str += str(row[4])+" "
#   song_music_feature_str += str(row[5])+" "
#   song_music_feature_str += str(row[6])+" "
#   song_music_feature_str += str(row[7])+" "
#   song_music_feature_str += str(row[8])+" "
#   song_music_feature_str += str(row[9])+" "
#   song_music_feature_str += str(row[10])+" "
#   song_music_feature_str += str(row[11])+" "
#   song_music_feature_str += str(row[12])+" "
#   song_music_feature_str += str(row[13])+" "
#   song_music_feature_str += str(row[14])+" "
#   song_music_feature_str += str(row[15])+" "
#   song_music_feature_str += str(row[16])+" "
#   song_music_feature_str += row[17].replace(","," ")+" "
#   song_music_feature_str += row[18].replace(","," ")+" "
#   song_music_feature_str += row[19].replace(","," ")+" "
#   song_music_feature_str += row[20].replace(","," ")
#   has_feature_data = "true"

cur.execute("""SELECT * FROM music_feature_matrix WHERE id=%s""", (model_id))

has_model_data = "false"
music_feature_matrix = "";
row_song_id = "";
column_music_feature = "";
augment_music_feature = "";
augment_matrix = "";


for row in cur.fetchall() :
   music_feature_matrix = row[1];
   row_song_id = row[2];
   column_music_feature = row[3];
   augment_music_feature = row[4];
   augment_matrix = row[5];
   has_model_data = "true"

if (has_model_data=="true") :

   song_id_array = row_song_id.split(',')
   input_song_feature_key = song_id_array.index(song_id)
   #input_song_matrix = np.matrix(song_music_feature_str)

   # model
   music_feature_matrix = json.loads(music_feature_matrix)
   music_feature_matrix = np.matrix(music_feature_matrix)

   # augment
   augment_matrix = np.matrix(augment_matrix)

   similar_music_model = np.concatenate((music_feature_matrix, augment_matrix), axis=1)
   #print similar_music_model
   #print( "matrix shape --> %d rows x %d columns" % similar_music_model.shape )

   normalize_min = similar_music_model.getA().min(axis=0)
   normalize_range = similar_music_model.getA().ptp(axis=0)
   input_song_matrix = similar_music_model.getA()[input_song_feature_key]
   #print input_song_matrixdebugg

   input_song_matrix_normalized = (input_song_matrix - normalize_min) / normalize_range
   #print input_song_matrix_normalized;
   similar_music_model_normalized = (similar_music_model.getA() - normalize_min) / normalize_range
   #print similar_music_model_normalized;

   for music_feature_index, music_feature_value in enumerate(similar_music_model_normalized):
      print music_feature_value
      print ";"

   #similar = np.dot(similar_music_model_normalized,input_song_matrix_normalized.T) / (np.linalg.norm(similar_music_model_normalized)*np.linalg.norm(input_song_matrix_normalized))

   #similar_music_dic = {}
   #for similar_index, similar_value in enumerate(similar):
   #   similar_music_dic[song_id_array[similar_index]] = similar_value

   #dist = (similar_music_model_normalized - input_song_matrix_normalized)**2
   #dist = np.sum(dist, axis=1)
   #dist = np.sqrt(dist)
   #print dist

   #similar_music_dic = {}
   #for dist_index, dist_value in enumerate(dist):
      #similar_music_dic[song_id_array[dist_index]] = dist_value

   #similar_music_sort_dic = list(sorted(similar_music_dic, key=similar_music_dic.__getitem__, reverse=True))
   #
   #similar_song_string = ""
   #for similar_song_id in similar_music_sort_dic :
   #   similar_song_string += similar_song_id+":"+str(similar_music_dic[similar_song_id])+","
   #
   #similar_song_string = similar_song_string[:-1]
   #print similar_song_string