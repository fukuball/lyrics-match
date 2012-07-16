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
cur.execute("""SELECT mf.song_id, mf.bar_count, mf.beat_count, mf.tatum_count, mf.section_count, mf.segment_count, mf.bar_avg_second, mf.beat_avg_second, mf.tatum_avg_second, mf.section_avg_second, mf.segment_avg_second, mf.pitch_avg_vector, mf.timbre_avg_vector, mf.pitch_std_vector, mf.timbre_std_vector, s.mode, s.tempo, s.time_signature, s.energy, s.danceability, s.speechiness, s.loudness FROM music_feature mf INNER JOIN song s ON (mf.song_id = s.id) WHERE mf.is_deleted = '0' AND s.is_deleted ='0' AND mf.song_id=%s ORDER BY mf.id""", (song_id))

for row in cur.fetchall() :
   print row[0]