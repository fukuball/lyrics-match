#!/usr/bin/python26
# -*- coding:utf-8 -*-
#
# audio_word_kmeans.py
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
from scipy.cluster.vq import *
import MySQLdb as mysql
try:
    import json
except ImportError:
    import simplejson as json
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

matrix_id = sys.argv[1];

cur = db.cursor()

cur.execute("""SELECT * FROM music_audio_word_matrix WHERE id=%s""", (matrix_id))

matrix = ""
matrix_type = ""

for row in cur.fetchall() :
   matrix = row[1]
   matrix_type = row[2]

matrix_array = json.loads(matrix)

audio_word_array = np.array(matrix_array)
#print audio_word_array.shape
res, idx = kmeans2(audio_word_array, 50)

for code_word_id in idx :
   print code_word_id+1

#try:
#   cur.execute("""INSERT INTO music_audio_code_book (type,create_time,modify_time) VALUES (%s, NOW(), NOW())""",("timbre"))
#   db.commit()
#   print "success"
#except mysql.Error, e:
#   db.rollback()
#   print "An error has been passed. %s" %e
#
#code_book_id = cur.lastrowid
#
#for code_word in res :
#   code_word_json = json.dumps(code_word.tolist())
#   print code_word_json
#
#   try:
#      cur.execute("""INSERT INTO muisc_audio_code_word (code_book_id,audio_word,type,create_time,modify_time) VALUES (%s, %s, %s, NOW(), NOW())""",(code_book_id,code_word_json,"timbre"))
#      db.commit()
#      print "success"
#   except mysql.Error, e:
#      db.rollback()
#      print "An error has been passed. %s" %e
