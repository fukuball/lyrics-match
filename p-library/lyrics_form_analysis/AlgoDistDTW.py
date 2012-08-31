# -*- coding: utf-8 -*-
from AlgoSequence import AlgoSequence
from LocalConstraint import StepType1
from numpy import zeros
from numpy import insert

class AlgoDistDTW(AlgoSequence):
	
	def __init__(self, simObject, stepType = StepType1()):
		self.__simObject = simObject
		self.__stepType = stepType
		self.__varInit()


		"""
		常數定義
		"""
		# 依據 Step Pattern，計算 i, j 兩軸最大的步數
		allStartCoor = []

		for path in self.__stepType.stepPattern:

			# 加入最遠的的座標到 allStartCoor 串列
			allStartCoor.append(path[0])


		# i 軸的最大步數
		self.__IMAXSTEPSIZE = abs(min(allStartCoor, key = lambda coor: coor[0])[0]) # 最後的 [0] 表示 i 軸


		# j 軸的最大步數
		self.__JMAXSTEPSIZE = abs(min(allStartCoor, key = lambda coor: coor[1])[1]) # 最後的 [1] 表示 j 軸


		# 計算 Accumulate Cost Table 的起始填表座標 
		# 因為 Accumulate Cost Table 與 Local Cost Table 之後會增廣，所以要依據 i, j軸的最大步數來計算
		self.__STARTCOOR = (self.__IMAXSTEPSIZE + 1, self.__JMAXSTEPSIZE + 1)


		# 路徑回朔的結束座標
		self.__STOPCOOR = (self.__IMAXSTEPSIZE, self.__JMAXSTEPSIZE)


		# 無限大定義
		self.__INF = 1e6

	
	def __varInit(self):
		from numpy import zeros

		# 輸入的兩條序列
		self.__seqI = ''
		self.__seqJ = ''


		# 兩條序列 DTW 計算後的距離
		self.__distance = 0.0

		# Accumulate Cost Table 和 Local Cost Table
		self.__tableAccu = None
		self.__tableLocal = None


		# Backtracking Table
		self.__tablePrev = None


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



	def similarity(self, seq1, seq2):
		"""
		描述：計算兩序列的 DTW 距離 (distance)
		輸入：兩條序列，每條序列是一個 list
		輸出：正規化過後的距離
		"""


		# 先初始化該用的變數
		self.__varInit()

		# 記錄輸入的兩個序列
		self.__seqI = seq1
		self.__seqJ = seq2

		if len(self.__seqI) > len(self.__seqJ):
			self.__seqI, self.__seqJ = self.__seqJ, self.__seqI


		# 產生 Accumulate Cost Table 和 Local Cost Table
		self.__tableAccu = zeros([len(self.__seqI), len(self.__seqJ)], float)
		self.__tableLocal = zeros(self.__tableAccu.shape, float)

		self.__tablePrev = zeros(self.__tableAccu.shape, tuple)


		# 初始化 Accumulate Cost Table 和 Local Cost Table
		self.__initTable()


		# 填滿 Accumulate Cost Table
		# 利用 DP 計算兩條序列經過 DTW 演算法，計算後的距離(distance)
		self.__tableComputing()
		self.__distance = self.__tableAccu[-1][-1]


		if self.__distance >= self.__INF:
			self.__similarity = -1
		else:

			# 回朔排比的最佳對應路徑
			self.__pathIdxList = []
			backStartCoor = (self.__tableAccu.shape[0] - 1, self.__tableAccu.shape[1] - 1)
			self.__backTracking(backStartCoor, self.__pathIdxList)
			self.__pathLength = len(self.__pathIdxList)
			self.__similarity = self.__distance / self.__pathLength
			

			# 將排比的路徑座標轉換成序列的 Index
			self.__pathIdxList = [tuple(map(lambda pair: pair[0] - pair[1], zip(coor, self.__STARTCOOR))) for coor in self.__pathIdxList]


		#print "AlgoDTW: Path Length = %d " % self.__pathLength
		#print "AlgoDTW: Path  = \n %s " % self.__pathIdxList
		

		return self.__similarity 


	def __initTable(self):
		"""
		描述：初始化 Accumulate Cost Table 與 Local Cost Table，兩個 Table 都會因應不同的 Step Type 做增廣
		輸入：無
		輸出：無，兩個 table 會填好初始的數值
		"""

		# Local Cost Table 利用 similarity object 計算兩序列中兩兩元素的相似度
		for i in range(len(self.__seqI)):
			for j in range(len(self.__seqJ)):
				self.__tableLocal[i][j] = self.__simObject.similarity(self.__seqI[i], self.__seqJ[j])


		# 將 Local Cost Table 以及 Accumulate Cost Table 依據 i, j 兩軸最大的步數，做 Table 增廣
		# 目的是要避免在利用 Step Pattern 計算 Accumulate Cost Table 時，出現 Index Out Of Bound
		self.__tableLocal = self.__tableAugment(self.__tableLocal)
		self.__tableAccu = self.__tableAugment(self.__tableAccu)

		self.__tablePrev = self.__tableAugment(self.__tablePrev)




		# Accumulate Cost Table 初始化
		# 主要是利用 Step Pattern 搭配已經填表好的 Local Cost Table，來計算 Accumulate Cost Table 的第一行與第一列

		# 填充 Accumulate Cost Table 起始點的分數
		#self.__tableAccu[self.__STOPCOOR] = self.__tableLocal[self.__STOPCOOR]
		self.__tableAccu[self.__STOPCOOR] = 0.0

		# 開始填 Accumulate Cost Table 的第一行 (i 軸)
		for i in range(self.__STOPCOOR[0] + 1, self.__tableAccu.shape[0]):
			nowCoor = (i, self.__STOPCOOR[1])
			pathCosts = self.__stepPathCost(nowCoor)
			self.__tableAccu[nowCoor] = min(map(lambda pathCost: pathCost["cost"], pathCosts))
	

		# 開始填 Accumulate Cost Table 的第一列 (j 軸)
		for j in range(self.__STOPCOOR[1] + 1, self.__tableAccu.shape[1]):
			nowCoor = (self.__STOPCOOR[0], j)
			pathCosts = self.__stepPathCost(nowCoor)
			self.__tableAccu[nowCoor] = min(map(lambda pathCost: pathCost["cost"], pathCosts))



	def __tableAugment(self, table):
		"""
		描述：將輸入的 table 在第一列與第一行前面擴增 值為無限大的行或是列
		輸入：要增廣的 table，numpy.array matrix
		輸出：增廣的 table
		"""

		#rowInsert = zeros(self.__IMAXSTEPSIZE)
		rowInsert = zeros(self.__IMAXSTEPSIZE + 1)
		temp = insert(table, rowInsert, self.__INF, axis = 0)

		#colInsert = zeros(self.__JMAXSTEPSIZE)
		colInsert = zeros(self.__JMAXSTEPSIZE + 1)
		temp = insert(temp, colInsert, self.__INF, axis = 1)

		return temp


	def __tableComputing(self):
		"""
		描述：運用 DP 的概念，依據DTW演算法，來填充 Accumulate Cost Table，得到計算兩條序列的距離(distance)
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
				self.__tableAccu[nowCoor] = min(map(lambda pathCost: pathCost["cost"], pathCosts))


		#print "AlgoDTW: Accumulate Matrix:\n %s " % repr(self.__tableAccu)


	def __backTracking(self, nowCoor, pathIdxList):
		"""
		描述：回朔兩條序列經過DTW後，排比的對應位置
		輸入：目前的原點座標，要記錄路徑的 list
		輸出：無，pathIdxList 會儲存好回朔的路徑 index list
		"""


		# 將目前的對應座標加到 pathIdxList 裡面

		if nowCoor == self.__STOPCOOR:
			return
		else:
			pathIdxList.insert(0, nowCoor)

			# 先檢查目前的做標是否超過計算邊界
			# 利用 nowCoor 與 self.__STOPCOOR 兩個座標做比較
			isOutList = map(lambda pair: pair[0] < pair[1], zip(nowCoor, self.__STOPCOOR))

			if True not in isOutList:

				# 計算目前座標是從哪裡來
				pathCost = self.__stepPathCost(nowCoor)

				for pCost in pathCost:

					# 回朔只有回朔一條路徑，而不是全部都找
					# 此條路徑預期是步數最少的路徑

					if pCost["cost"] == self.__tableAccu[nowCoor]:
						comePath = self.__stepType.stepPattern[ pCost["pathNum"] ]

						# 加入此路徑的中途座標點
						for i in range(len(comePath) - 1, 0, -1):
							prevCoor = tuple(map(sum, zip(nowCoor, comePath[i])))
							pathIdxList.insert(0, prevCoor)


						# 計算前一個起始的座標位置
						prevStartCoor = tuple(map(sum, zip(nowCoor, comePath[0]))) # [0]表示起始點

						return  self.__backTracking(prevStartCoor, pathIdxList)

		


	def __stepPathCost(self, nowCoor):
		"""
		敘述：計算 Step Pattern 中的每一條 Path 到 目前原點的 Cost
		輸入：目前原點的座標位置，一個 tuple (i, j)
		輸出：每條 Path 的 Cost 所形成的 dict {"pathNum": 路徑編號, "cost": 路徑成本}

		"""
		pathCosts = []

		for i in range(len(self.__stepType.stepPattern)):
			absoluteStepPath = map(lambda coor: (coor[0] + nowCoor[0], coor[1] + nowCoor[1]) ,self.__stepType.stepPattern[i])
			pathCost = self.__tableAccu[absoluteStepPath.pop(0)]
			absoluteStepPath.append(nowCoor)
			pathCost += self.__stepType.weightVec[i] * sum(map(lambda coor: self.__tableLocal[coor], absoluteStepPath))


			"""
			# 取出 Step Path
			stepPath = self.__stepType.stepPattern[i]



			# 計算此 Step Path 起始點的絕對座標
			prevStartCoor = tuple(map(sum, zip(stepPath[0], nowCoor)))



			# 開始計算此 Step Path 到 目前原點的 Cost
			# 起始點 Cost 從 Accumulate Cost Table 取得
			pathCost = self.__tableAccu[prevStartCoor]
			

			# 中途座標的 Cost 從 Local Cost Table 取得
			for j in range(1, len(stepPath)):
				rCoor = stepPath[j]

				# 計算此 Step Path 中途點的絕對座標
				prevCoor = tuple(map(sum, zip(rCoor, nowCoor)))
				
				# 將中途座標的 Local Cost 與 Weight Vector 相乘後累加至 pathCost
				pathCost += self.__stepType.weightVec[i] * self.__tableLocal[prevCoor]

			# 加上目前原點座標的 Local Cost
			pathCost += self.__stepType.weightVec[i] * self.__tableLocal[nowCoor]
			print pathCost
			print
			"""
			pathCosts.append({"pathNum": i, "cost": pathCost})

		return pathCosts






if __name__ == "__main__":
	from DistPitch import DistPitch
	from DistEuclidean import DistEuclidean
	from LocalConstraint import *



	#seq1  = [1,1,1, 1, 2, 4]
	#seq1 = [4, 3, 3, 1, 3, 1, 4 ,1]
	#seq1 = [3, 2, 3, 2, 2, 1, 2, 1, 1, 2, 4, 4, 4]
	#seq1 = [3, 3, 4, 2, 2, 1, 2, 1]
	seq1 = [62, 69, 65, 64]

	#seq2 = [4, 3, 2, 1]
	#seq2 = [4, 3, 3, 1, 3, 1, 4 ,1]
	#seq2 = [5,1, 4]
	#seq2 = [5, 1, 9, 3, 10]
	seq2 = [62, 65, 65, 69, 65, 65, 64, 64]

	print "Main: seq1 = %d" % len(seq1)
	print "Main: seq2 = %d" % len(seq2)

	dtw = AlgoDistDTW(DistEuclidean(), StepType3())
	#dtw = AlgoDistDTW(DistPitch(), PitchToneType())
	

	print "Main: Distance = %.2f" % dtw.similarity(seq2, seq1)
	print "Main: Alignment Result = \n %s" % dtw.getAlignmentResult()

