#!/usr/bin/python26
# -*- coding:utf-8 -*-

import sys
import numpy as np
import MySQLdb as mysql

sys.path.append("/var/www/html/lyrics-match/p-config")

CONST = db_stage._Const()

print CONST.DBHOST

#def Import():
#  import sys
#  sys.path.append("C:/Users/18Fan/Dropbox/研究/程式實作/Lyrics_Process/Lyrics_Form_Analysis".decode('utf-8'))
#  sys.path.append("C:/Users/18Fan/Dropbox/研究/程式實作/Lyrics_Process/Network_Service".decode('utf-8'))
#
#
#  """
#  加入模組檔案路徑
#  sys.path.append(檔案路徑)
#  """
#
#
#
#
#"""
#在其他檔案加入模組範例
#"""
#import ImportPath
#ImportPath.Import()

#db = mysql.connect(host="localhost",   # your host, usually localhost
#                   user="john",        # your username
#                   passwd="megajonhy", # your password
#                   db="jonhydb")       # name of the data base
#
## you must create a Cursor object. It will let
##  you execute all the query you need
#cur = db.cursor()
#
## Use all the SQL you like
#cur.execute("SELECT * FROM YOUR_TABLE_NAME")
#
## print all the first cell of all the rows
#for row in cur.fetchall() :
#    print row[0]
#
#
#A = np.floor(np.random.rand(4,4)*20-10) # generating a random
#b = np.floor(np.random.rand(4,1)*20-10) # system Ax=b
#
#U,s,V = np.linalg.svd(A) # SVD decomposition of A
#
#print U