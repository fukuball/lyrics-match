# -*- coding: utf-8 -*-
from abc import ABCMeta, abstractmethod

class LyricsInput:
	__metaclass__ = ABCMeta

	@abstractmethod
	def process(self, lines):
		pass

	def processNext(self, processObj, lines):
		if processObj is not None:
			processObj.process(lines)


class FromFile(LyricsInput):

	def __init__(self, processObj = None):
		self.__WINDOWSRET = '\r\n'
		self.__processObj = processObj
		pass
	

	def process(self, path):
		self.__lines = []

		import codecs

		fp = codecs.open(path.decode('utf-8'), 'r', 'utf-8')
		lines = fp.readlines()
		fp.close()

		#去除 utf-8 file 裡的 byte order mark(BOM)
		if lines[0][0] == unicode(codecs.BOM_UTF8, 'utf8'):
			#print 'FromFile: Strip UTF-8 BOM'
			lines[0] = lines[0][1:]


		#去除換行符號
		for i in range(len(lines)):
			lines[i] = lines[i].strip(self.__WINDOWSRET)

			#記錄有內容的行
			if lines[i].strip(' ') != '':
				self.__lines.append(lines[i].split(','))
				#self.__lines.append(lines[i])

				
		self.processNext(self.__processObj, self.__lines)

		#print "FromFile: The first line after all process is"
		#print self.__lines[0]
		return self.__lines




class FromDB(LyricsInput):

	def process(self, songId):
		feature = {"word_count": [], "pinyin": [], "pos": [], "tone": []}

		import MySQLdb as mysql
		import MySQLdb.cursors as cursors
		import sys
		from InputProcess import Tone2Pitch

		sys.path.append("www/html/lyrics-match/p-config")
		import db_stage

		"""
		DB 連線設定
		"""
		CONST = db_stage._Const()

		conn = mysql.connect(host = CONST.DBHOST,
					user = CONST.DBUSER,
					passwd = CONST.DBPASS,
					db = CONST.DBNAME,
					charset = 'UTF8')

		dictCursor = conn.cursor(cursorclass = cursors.DictCursor)
		baseCursor = conn.cursor()

		dictCursor.execute("SELECT * FROM lyrics_consonant_mapping")
		conList = list(dictCursor.fetchall())
		
		dictCursor.execute("SELECT * FROM lyrics_vowel_mapping")
		vowelList = list(dictCursor.fetchall())

		dictCursor.execute("SELECT * FROM lyrics_tone_mapping")
		toneList = list(dictCursor.fetchall())

		dictCursor.execute("SELECT pos FROM lyrics_pos_mapping")
		posList = list(dictCursor.fetchall())
		posList = map(lambda pos: pos["pos"], posList)

		dictCursor.execute("SELECT line, offset, length FROM lyrics_line WHERE song_id = %d ORDER BY offset ASC" % songId)
		lineList = list(dictCursor.fetchall())



		for line in lineList:

			sql = "SELECT offset, length FROM lyrics_sentence WHERE song_id = %d AND offset >= %d AND offset < %d ORDER BY offset ASC" % \
									(songId, line["offset"], line["offset"] + line["length"])
			dictCursor.execute(sql)
			sentenceList = list(dictCursor.fetchall())
			

			wordCountLine = []
			pinyinLine = []
			toneLine = []
			posLine = []

			for sentence in sentenceList:
				# 選取 Word Table
				sql = "SELECT word, consonant, vowel, tone FROM lyrics_word WHERE \
						song_id = %d AND offset >= %d AND offset < %d ORDER BY offset ASC" % \
						(songId, sentence["offset"], sentence["offset"] + sentence["length"])
				dictCursor.execute(sql)


				# 裡面確定都是中文字
				wordList = list(dictCursor.fetchall())


				# 單句字數
				wordCountLine.append(len(wordList))
				wordCountLine.append('') #空字串表示分隔符號


				# 拼音
				for word in wordList:
					# 將聲母、韻母轉換成對應的 id
					print "|" + word["word"] + "|" + word["consonant"] + "|" + word["vowel"] + "|"
					#conId = [con["id"] for con in conList if con["consonant"] == word["consonant"]][0]
					matchId = [con["id"] for con in conList if con["consonant"] == word["consonant"]]
					print "conid", matchId
					if matchId != []:
						conId = matchId[0]

					#vowelId = [vowel["id"] for vowel in vowelList if vowel["vowel"] == word["vowel"]][0]

					matchId = [vowel["id"] for vowel in vowelList if vowel["vowel"] == word["vowel"]]
					print "vowelid", matchId

					if matchId != []:
						vowelId = matchId[0]

					pinyinLine.append((conId, vowelId))

				print 

				pinyinLine.append('') #空字串表示分隔符號


				# 聲調，不需要分隔符號
				for word in wordList:
					toneId = [tone["id"] for tone in toneList if tone["tone"] == word["tone"]][0]
					toneLine.append(toneId)


				# 選取 Term Table
				sql = "SELECT pos FROM lyrics_term WHERE \
						song_id = %d AND offset >= %d AND offset < %d ORDER BY offset ASC" % \
						(songId, sentence["offset"], sentence["offset"] + sentence["length"])
				dictCursor.execute(sql)

				termList = list(dictCursor.fetchall())

				
				# 詞性
				for term in termList:

					# 確定詞性有出現在 lyrics_pos_mapping 中
					if term["pos"] in posList:
						posLine.append(term["pos"])

				posLine.append('')


			feature["word_count"].append(wordCountLine)
			feature["pinyin"].append(pinyinLine)
			feature["tone"].append(toneLine)
			feature["pos"].append(posLine)


		Tone2Pitch().process(feature["tone"])


		dictCursor.close()
		conn.close()

		return feature



