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

for row in cur.fetchall() :
   print row['matrix']
#
#
#A = np.floor(np.random.rand(4,4)*20-10) # generating a random
#b = np.floor(np.random.rand(4,1)*20-10) # system Ax=b
#
#U,s,V = np.linalg.svd(A) # SVD decomposition of A
#
#print U