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

cur = db.cursor()
cur.execute("""SELECT s.mode, s.tempo, s.time_signature, s.energy, s.danceability, s.speechiness, s.loudness, mf.bar_count, mf.beat_count, mf.tatum_count, mf.section_count, mf.segment_count, mf.bar_avg_second, mf.beat_avg_second, mf.tatum_avg_second, mf.section_avg_second, mf.segment_avg_second, mf.pitch_avg_vector, mf.timbre_avg_vector, mf.pitch_std_vector, mf.timbre_std_vector FROM music_feature mf INNER JOIN song s ON (mf.song_id = s.id) WHERE mf.is_deleted = '0' AND s.is_deleted ='0' AND mf.song_id=%s ORDER BY mf.id""", (song_id))

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
   song_music_feature_str += row[17].replace(","," ")

   #print row[0]
   #if ($query_result_data['mode']==1) {
   #   $mode = 10;
   #} else {
   #   $mode = -10;
   #}
   #
   #$matirx =   $matirx.
   #            $mode." ".
   #            $query_result_data['tempo']." ".
   #            $query_result_data['time_signature']." ".
   #            $query_result_data['energy']." ".
   #            $query_result_data['danceability']." ".
   #            $query_result_data['speechiness']." ".
   #            $query_result_data['loudness']." ".
   #            $query_result_data['bar_count']." ".
   #            $query_result_data['beat_count']." ".
   #            $query_result_data['tatum_count']." ".
   #            $query_result_data['section_count']." ".
   #            $query_result_data['segment_count']." ".
   #            $query_result_data['bar_avg_second']." ".
   #            $query_result_data['beat_avg_second']." ".
   #            $query_result_data['tatum_avg_second']." ".
   #            $query_result_data['section_avg_second']." ".
   #            $query_result_data['segment_avg_second'].
   #            "; ";
   #
   #$augment_matrix = $augment_matrix.
   #                  str_replace(",", " ", $query_result_data['pitch_avg_vector'])." ".
   #                  str_replace(",", " ", $query_result_data['timbre_avg_vector'])." ".
   #                  str_replace(",", " ", $query_result_data['pitch_std_vector'])." ".
   #                  str_replace(",", " ", $query_result_data['timbre_std_vector']).
   #                  "; ";

print song_music_feature_str