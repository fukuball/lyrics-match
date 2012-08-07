# -*- coding: utf-8 -*-

import sys
import MySQLdb as mysql
import MySQLdb.cursors as cursors
import ImportPath
ImportPath.Import()

import db_stage
from LyricsInput import FromDB
from Evaluation import Evaluation

from LyricsForm import LyricsForm
from InputProcess import Pinyin2Tuple
from DistEuclidean import DistEuclidean
from SimPinyin import SimPinyin
from SimPOS import SimPOS
from AlgoStructAlign import AlgoStructAlign
from AlgoDistDTW import AlgoDistDTW
from AlgoDistDDTW import AlgoDistDDTW
from DistSenStruct import DistSenStruct
from DistPitch import DistPitch
from InputProcess import Str2Int
from InputProcess import Tone2Pitch
from LocalConstraint import StepType2
from LocalConstraint import StepType3
from LocalConstraint import StepType1

reload(sys)
sys.setdefaultencoding('utf-8')


def formAnalysis(songId):
	FromDB().process(songId)




if __name__ == "__main__":

	"""
	DB 連線設定
	"""
	CONST = db_stage._Const()

	conn = mysql.connect(host = CONST.DBHOST,
				user = CONST.DBUSER,
				passwd = CONST.DBPASS,
				db = CONST.DBNAME,
				charset = 'UTF8')
	conn.cursor()

	dictCursor = conn.cursor(cursorclass = cursors.DictCursor)
	dictCursor.execute("SELECT id FROM song WHERE id = 3")
	songList = dictCursor.fetchall()


	for song in songList:
		formAnalysis(song["id"])


	dictCursor.close()
	conn.close()




