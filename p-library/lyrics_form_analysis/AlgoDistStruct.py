# -*- coding: utf-8 -*-
from AlgoSequence import AlgoSequence
from LocalConstraint import PitchToneType
from numpy import zeros
from math import log
from math import exp
from math import ceil
from numpy import argmin
from numpy import insert
from numpy import sin
from numpy import pi
from numpy import sqrt
from copy import deepcopy

class AlgoDistStruct(AlgoSequence):
	
	def __init__(self, pitchToneType = PitchToneType(), singWeight = 0.6, mergeWeight = 0.4):
		self.__pitchToneType = pitchToneType

		"""
		常數定義
		"""
		# 記錄PitchToneType 設定中，ㄧ字最多對幾個音符
		self.__MAXNOTE = max([abs(path[0][1]) for path in self.__pitchToneType.stepPattern])


		# 填表開始的座標位子
		self.__STARTCOOR = (1, 1)


		# 路徑回朔的結束座標
		self.__STOPCOOR = (0, 0)


		# 無限大定義
		self.__INF = 1e6

		# Cost 的 base
		self.__BASE = 1.5

		# 合併次數邊界
		self.__MERGELIMIT = 4.5


		# 權重參數
		self.__SINGWEIGHT = singWeight
		self.__MERGEWEIGHT = mergeWeight

	
	def __varInit(self):
		from numpy import zeros

		# 輸入的兩條序列
		self.__seqI = ''
		self.__seqJ = ''


		# 兩條序列 結構最小的總字數差距 
		self.__distance = 0.0

		# Accumulate Cost Table 
		self.__tableAccu = None


		# Word Range Table and Note Range Table
		self.__tableLyricsRange = None
		self.__tableMelodyRange = None


		# 記錄 最佳 Ancestor 的 Table
		self.__tablePrevCoor = None


		# 記錄回朔路徑的 index
		self.__pathIdxList = None




	def getSimilarity(self):
		"""
		描述：回傳兩個序列的距離，也就是 dissimilarity
		輸入：無
		輸出：兩條序列的距離 (distance)
		"""
		return self.__similarity



	def getAlignmentResult(self):
		"""
		描述：回傳兩個序列排比的對應結果
		輸入：無
		輸出：兩條序列對應的 index list
		"""
		return self.__pathIdxList



	def similarity(self, lyricsStruct = None, melodyStruct = None):
		"""
		描述：計算兩序列的結構對應最小 字數與音符 距離 (distance)，
			Row方向代表單句字數序列，Column方向代表樂句音符數序列
		輸入：兩條序列，每條序列是一個 list
		輸出：正規化過後的距離
		"""


		if lyricsStruct == None or melodyStruct == None:
			print "AlgoDistStruct: Please input the lyrics word structure or melody note structure"
			return


		# 先初始化該用的變數
		self.__varInit()


		# 記錄輸入的兩個序列
		self.__seqI = deepcopy(lyricsStruct)
		self.__seqI.insert(0, -1)
		self.__seqJ = deepcopy(melodyStruct)
		self.__seqJ.insert(0, -1)


		# 產生 Accumulate Cost Table 和 Local Cost Table
		self.__tableAccu = zeros([len(self.__seqI), len(self.__seqJ)], float)
		self.__tableLyricsRange = zeros(self.__tableAccu.shape, int)
		self.__tableMelodyRange = zeros(self.__tableAccu.shape, int)

		

		self.__tablePrevCoor = zeros(self.__tableAccu.shape, dtype = tuple)


		# 初始化 Accumulate Cost Table 和 Local Cost Table
		self.__initTable()



		# 填滿 Accumulate Cost Table
		# 利用 DP 計算兩條序列經過 DTW 演算法，計算後的距離(distance)
		self.__tableComputing()
		self.__distance = self.__tableAccu[-1][-1]


		#print "Accumulate Matrix:\n %s " % repr(self.__tableAccu)
		#print "Prev Matrix:\n %s " % repr(self.__tablePrevCoor)


		if self.__distance >= self.__INF:
			self.__similarity = -1
		else:

			# 回朔排比的最佳對應路徑
			self.__pathIdxList = []
			backStartCoor = (self.__tableAccu.shape[0] - 1, self.__tableAccu.shape[1] - 1)
			depth = self.__backTracking(backStartCoor, self.__pathIdxList)
			#self.__pathLength = len(self.__pathIdxList)
			#self.__similarity = self.__distance / depth
			denom = min(len(self.__seqI), len(self.__seqJ))
			self.__similarity = self.__distance / denom

			

			# 將排比的路徑座標轉換成序列的 Index
			self.__pathIdxList = [tuple(map(lambda pair: pair[0] - pair[1], zip(coor, self.__STARTCOOR))) for coor in self.__pathIdxList]


		#print "AlgoDTW: Path Length = %d " % self.__pathLength
		#print "AlgoDTW: Path  = \n %s " % self.__pathIdxList
		
		return self.__similarity 


	def __initTable(self):
		"""
		描述：初始化 Accumulate Cost Table , Lyrics Range Table 與 Melody Range Table
		輸入：無
		輸出：無，三個 table 會填好初始的數值
		"""
		

		# Accumulate Cost Table 初始化
		self.__tableAccu[self.__STOPCOOR] = 0.0
		self.__tableAccu[0, 1:] = self.__INF
		self.__tableAccu[1:, 0] = self.__INF

		
		# Lyrics Range Table 填表，每一格代表 字句 可以走 樂句 的最大格數
		self.__tableLyricsRange[0, 0:] = self.__INF
		self.__tableLyricsRange[1:, 0] = self.__INF

		for i in range(self.__STARTCOOR[0], self.__tableLyricsRange.shape[0]):
			for j in range(self.__STARTCOOR[1], self.__tableLyricsRange.shape[1]):
				#maxAlignNote = ((self.__seqI[i] - 1) * self.__MAXNOTE) + 1
				maxAlignNote = self.__seqI[i] * self.__MAXNOTE 
				
				noteCount = 0
				backStepNum = 0
				for backStep in range(j, 0, -1):
					noteCount += self.__seqJ[backStep]	

					if noteCount <= maxAlignNote:
						backStepNum += 1
					else:
						break

				self.__tableLyricsRange[i, j] = backStepNum



		# Melody Range Table 填表，每一格代表 樂句 可以走 字句 的最大格數
		self.__tableMelodyRange[0, 0:] = self.__INF
		self.__tableMelodyRange[1:, 0] = self.__INF

		for j in range(self.__STARTCOOR[1], self.__tableMelodyRange.shape[1]):
			for i in range(self.__STARTCOOR[0], self.__tableMelodyRange.shape[0]):
				maxAlignWord = self.__seqJ[j]

				wordCount = 0
				backStepNum = 0
				for backStep in range(i, 0, -1):
					wordCount += self.__seqI[backStep]	

					if wordCount <= maxAlignWord:
						backStepNum += 1
					else:
						break

				self.__tableMelodyRange[i, j] = backStepNum



	def __tableComputing(self):
		"""
		描述：運用 DP 的概念，來填充 Accumulate Cost Table
		輸入：無
		輸出：無，會將 Accumulate Cost Table 每個元素計算完成
		"""

		# 開始填充 Accumulate Cost Table
		startI = self.__STARTCOOR[0] 
		startJ = self.__STARTCOOR[1]

		for i in range(startI, self.__tableAccu.shape[0]):
			for j in range(startJ, self.__tableAccu.shape[1]):
				nowCoor = (i, j)
				pathCosts = self.__stepPathCost(nowCoor)

				minIdx = argmin([path["cost"] for path in pathCosts])
				self.__tableAccu[nowCoor] = pathCosts[minIdx]["cost"]
				self.__tablePrevCoor[nowCoor] = pathCosts[minIdx]["prev"]




	def __backTracking(self, nowCoor, pathIdxList):
		"""
		描述：回朔兩條序列經過DTW後，排比的對應位置
		輸入：目前的原點座標，要記錄路徑的 list
		輸出：tracking 的深度(depth)
			以及 pathIdxList 會儲存好回朔的路徑 index list
		"""

		if nowCoor == self.__STOPCOOR:
			return 0
		else:
			# 將目前的對應座標加到 pathIdxList 裡面
			#pathIdxList.insert(0, nowCoor)


			# 加入 ancestor 座標
			prevCoor = self.__tablePrevCoor[nowCoor]
			startI = prevCoor[0] + 1
			startJ = prevCoor[1] + 1

			if startJ == nowCoor[1]:

				# 走 column
				for i in range(nowCoor[0] , prevCoor[0], -1):
					pathIdxList.insert(0, (i, startJ))

			else:
				for j in range(nowCoor[1] , prevCoor[1], -1):
					pathIdxList.insert(0, (startI, j))


			return  self.__backTracking(prevCoor, pathIdxList) + 1

		


	def __stepPathCost(self, nowCoor):
		"""
		敘述：計算 Step Pattern 中的每一條 Path 到 目前原點的 Cost，每個原點的 Step Pattern 是動態計算
			依據 Lyrics Range Table 與 Melody Range Table
		輸入：目前原點的座標位置，一個 tuple (i, j)
		輸出：每條 Path 的 Cost 所形成的 dict {"prev": 路徑起始點, "cost": 路徑成本}
		"""
		pathCosts = []

		lyricsRange = self.__tableLyricsRange[nowCoor]
		melodyRange = self.__tableMelodyRange[nowCoor]


		# 計算 字句 所有可以對應樂句的 cost (Step Pattern 走 Row 的方向)
		phraseStart = nowCoor[1]
		phraseEnd = nowCoor[1] - lyricsRange
		phraseList = []
		sentenceList = [self.__seqI[nowCoor[0]]]
		noteCount = 0

		for phraseIdx in range(phraseStart, phraseEnd, -1):
			noteCount += self.__seqJ[phraseIdx]
			phraseList.append(self.__seqJ[phraseIdx])
			prev = (nowCoor[0] - 1, phraseIdx - 1)
			totalCost = self.__INF

			if self.__tableAccu[prev] != self.__INF and noteCount >= self.__seqI[nowCoor[0]]:
				totalCost = self.__tableAccu[prev] + self.__localCost(sentenceList, phraseList)
					
			pathCosts.append({"prev": prev, "cost": totalCost})



		# 計算 樂句 所有可以對應字句的 cost (Step Pattern 走 Column 的方向)
		sentenceStart = nowCoor[0]
		sentenceEnd = nowCoor[0] - melodyRange
		phraseList = [self.__seqJ[nowCoor[1]]]
		sentenceList = []
		wordCount = 0

		for sentenceIdx in range(sentenceStart, sentenceEnd, -1):
			wordCount += self.__seqI[sentenceIdx]
			sentenceList.append(self.__seqI[sentenceIdx])
			prev = (sentenceIdx - 1, nowCoor[1] - 1)
			totalCost = self.__INF

			if self.__tableAccu[prev] != self.__INF and wordCount >= ceil(self.__seqJ[nowCoor[1]] / float(self.__MAXNOTE)):
				totalCost = self.__tableAccu[prev] + self.__localCost(sentenceList, phraseList)

					
			pathCosts.append({"prev": prev, "cost": totalCost})


		# 如果目前的樂句與子句都對不起來的情況
		if pathCosts == []:
			pathCosts.append({"prev": None, "cost": self.__INF})


		return pathCosts





	def __singKernel(self, t, limit):

		if t > limit:
			return 1.0
		elif t == 1.0:
			return 0.0
		else:
			# sigmoid
			#return (1 / (1 + exp(-(float(t) - limit) * 5 / limit))) * 2
			
			#linear
			#return float(t) / limit

			# sine
			#return sin((2 * pi * (t  / (limit * 4))) + 3 *pi / 2 ) + 1.0
			return sin(2 * pi * t / (limit * 4))

			#ellipse
			#return -1 * sqrt(1 - ((t ** 2) / limit ** 2)) + 1


	def __mergeKernel(self, t, limit):

		if t > limit:
			return 1.0
		else:
			#sigmoid
			#return (1 / (1 + exp(-float(t) * 5 / limit)) - 0.5) * 2

			#linear
			#return float(t) / limit

			#sine
			return sin(2 * pi * t / (limit * 4))


			#ellipse
			#return sqrt(1 - ((t - limit) ** 2 / limit ** 2))


	def __localCost(self, sentenceList, phraseList):
		noteWordRate = sum(phraseList) / float(sum(sentenceList))
		mergeCount = len(sentenceList) + len(phraseList) - 2
		localCost = self.__SINGWEIGHT * self.__singKernel(noteWordRate, self.__MAXNOTE) + \
					self.__MERGEWEIGHT * self.__mergeKernel(mergeCount, self.__MERGELIMIT)

		#print self.__singKernel(noteWordRate, self.__MAXNOTE)
		#print self.__mergeKernel(mergeCount, self.__MERGELIMIT)
		return localCost



if __name__ == "__main__":
	from DistPitch import DistPitch
	from LocalConstraint import *
	from random import randint

	#sentenceSeq  = [4, 7, 1, 3]
	#sentenceSeq  = [8]
	sentenceSeq = [4, 13]
	#sentenceSeq = [3, 4, 5]
	#phraseSeq = [3, 5, 2, 5, 1, 4]
	#phraseSeq = [3, 2, 5]
	phraseSeq = [18]
	#phraseSeq = [2, 3, 2, 3, 6]
	#phraseSeq = [2, 2, 1, 5, 6]
	#phraseSeq = [2, 3, 4]


	struct = AlgoDistStruct(PitchToneType())
	

	print "Main: Distance = %.2f" % struct.similarity(sentenceSeq, phraseSeq)
	print "Main: Alignment Result = \n %s" % struct.getAlignmentResult()

