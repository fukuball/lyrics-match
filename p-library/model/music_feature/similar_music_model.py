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
cur.execute("""SELECT s.mode, s.tempo, s.time_signature, s.energy, s.danceability, s.speechiness, s.loudness, mf.bar_count, mf.beat_count, mf.tatum_count, mf.section_count, mf.segment_count, mf.bar_avg_second, mf.beat_avg_second, mf.tatum_avg_second, mf.section_avg_second, mf.segment_avg_second, mf.pitch_avg_vector, mf.timbre_avg_vector, mf.pitch_std_vector, mf.timbre_std_vector FROM music_feature mf INNER JOIN song s ON (mf.song_id = s.id) WHERE mf.is_deleted = '0' AND s.is_deleted ='0' AND mf.song_id=%s ORDER BY mf.id""", (song_id))

has_feature_data = "false"
song_music_feature_str = ""
for row in cur.fetchall() :
   if (row[0]==1) :
      song_music_feature_str += "10 "
   else :
      song_music_feature_str += "-10 "
   song_music_feature_str += str(row[1])+" "
   song_music_feature_str += str(row[2])+" "
   song_music_feature_str += str(row[3])+" "
   song_music_feature_str += str(row[4])+" "
   song_music_feature_str += str(row[5])+" "
   song_music_feature_str += str(row[6])+" "
   song_music_feature_str += str(row[7])+" "
   song_music_feature_str += str(row[8])+" "
   song_music_feature_str += str(row[9])+" "
   song_music_feature_str += str(row[10])+" "
   song_music_feature_str += str(row[11])+" "
   song_music_feature_str += str(row[12])+" "
   song_music_feature_str += str(row[13])+" "
   song_music_feature_str += str(row[14])+" "
   song_music_feature_str += str(row[15])+" "
   song_music_feature_str += str(row[16])+" "
   song_music_feature_str += row[17].replace(","," ")+" "
   song_music_feature_str += row[18].replace(","," ")+" "
   song_music_feature_str += row[19].replace(","," ")+" "
   song_music_feature_str += row[20].replace(","," ")
   has_feature_data = "true"

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

if (has_feature_data=="true" and has_model_data=="true") :
   input_song_matrix = np.matrix(song_music_feature_str)
   music_feature_matrix = json.loads(music_feature_matrix)
   print music_feature_matrix
   music_feature_matrix = np.matrix(music_feature_matrix)
   print music_feature_matrix
