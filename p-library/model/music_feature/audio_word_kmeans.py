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
res, idx = kmeans2(audio_word_array, 3)

print res
print idx