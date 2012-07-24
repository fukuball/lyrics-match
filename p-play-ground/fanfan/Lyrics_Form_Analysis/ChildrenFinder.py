# -*- coding: utf-8 -*-
from numpy import zeros
from copy import deepcopy
from numpy import argmax
from math import log

	
class ChildrenFinder:


	def __init__(self):

		"""
		初始化 Local Similarity 的 Table
		"""
		self.__tableL = None


		"""
		初始化 Accumulated Score 的 Table
		"""
		self.__tableA = None


		"""
		初始化 長度與相似度門檻值
		"""
		self.__lenT = 0.0
		self.__simT = 0.0


		"""
		初始化 blockFamily、familyMask以及pathMask
		"""
		self.__blockFamily = None
		self.__familyMask = None
		self.__pathMask = None





	def children(self, tableL, lenT = 0.0, simT = 0.0):
		self.__tableL = deepcopy(tableL)

		self.__lenT = lenT
		self.__simT = simT


		"""
		初始化 Accumulate Table
		"""
		self.__tableA = zeros(self.__tableL.shape)
		self.__tableA[:, 0] = self.__tableL[:, 0]


		"""	
		計算 Accumulate Table
		"""
		for j in range(1, self.__tableA.shape[1]):
			for i in range(self.__tableA.shape[0]):
				scoreList = self.__comeScore(i, j)
				self.__tableA[i, j] = tableL[i, j] + max(scoreList)

				#if self.__tableL[i, j] == 0.0:
				#	self.__tableA[i, j] = max(scoreList)
				#else:
				#	self.__tableA[i, j] = log(self.__tableL[i, j]) + max(scoreList)




		"""
		回朔計算的路徑, 也就是找出 children 所在的區間
		"""
		self.__backTracking()

		return self.getChildren()



	def getPathMask(self):
		return self.__pathMask



	def getFamilyMask(self):
		return self.__familyMask



	def getChildren(self):
		return deepcopy(self.__blockFamily)




	def __backTracking(self):
		"""
		記錄目前的 path 的 i 值
		"""
		nowI = -1


		"""
		記錄一個 Child Block 的 path，裡面是由 (i, j) 的 tuple 所組成的 list
		"""
		cBlockPath = []


		"""
		記錄 family block extraction 的結果
		資料結構為 [ {"sim": number, "block": [start, len]}, ...]
		"""
		self.__blockFamily = []


		"""
		初始化 mask
		"""
		self.__familyMask = zeros(self.__tableA.shape, int)
		self.__pathMask = zeros(self.__tableA.shape, int)




		"""
		回朔路徑
		"""
		for j in range(self.__tableA.shape[1], -1, -1):

			"""
			計算 nowI 是由之前哪個 i 過來的, prevI
			"""
			scoreList = self.__comeScore(nowI, j)
			prevI = argmax(scoreList)

			"""	
			判斷每個 block 的邊界
			如果 prevI + 1 != nowI 表示到了一個 child block 的邊界
			如果 nowI == -1 表示目前是在一開始回朔的 i
			"""
			if (prevI + 1 != nowI and nowI != -1) or j == 0:
				"""
				計算 child block 與 parent block 的相似度分數
				"""
				cBlockLen = len(cBlockPath)

				cBlockTotalSim = sum(map(lambda coor: self.__tableL[coor], cBlockPath))
				cBlockSim = cBlockTotalSim / cBlockLen

				if cBlockSim >= self.__simT and cBlockLen >= self.__lenT:

					start = cBlockPath[0][1]
					end = start + cBlockLen - 1
					#self.__blockFamily.insert(0, {"range": [start, end], "blockLen": cBlockLen})
					self.__blockFamily.insert(0, [start, end])

					
					"""
					做 familyMask
					"""
					for path in cBlockPath:
						self.__familyMask[path] = 1


					
				cBlockPath = [(prevI, j - 1)]

			else:
				cBlockPath.insert(0, (prevI, j - 1))



			"""
			做 pathMask
			"""
			if j != 0:
				self.__pathMask[prevI, j - 1] = 1


			"""
			將 nowI 設定為新找出的 prevI
			"""
			nowI = prevI




	def __comeScore(self, i, j):
		"""	
		計算所有到座標 (i, j) 可能的累積分數 List
		"""
		scoreList = []

		for k in range(self.__tableA.shape[0]):
			scoreList.append( self.__tableA[k, j - 1] + self.__transition(k, i) )

		return scoreList



	def __transition(self, k, i):
		"""
		設定 Transition Probability，盡量維持45度角走
		如果是 45 度角 權重是 1.1 其他則是 1.0
		"""
		if i == k + 1:
			return 1.3
		else:
			return 1.0
	





