# -*- coding: utf-8 -*-

from AlgoSequence import AlgoSequence
from numpy import zeros
from numpy import argmax
from numpy import argmin

class AlgoStructAlign(AlgoSequence):

	def __init__(self, simObject, pext, popen, pcross, isRhyme = False):

		# 記錄 similarity object
		self.__simObject = simObject


		# 記錄 similarity object 的 class 名稱
		self.__simObjName = self.__simObject.__class__.__name__

		self.__limitValueFunc = lambda x, y: max(x, y)
		self.__limitIdxFunc = lambda values: argmax(values)

		#負無限大
		self.__INF = -1e6


		if "dist" in self.__simObject.__class__.__name__.lower():
			self.__limitValueFunc = lambda x, y: min(x, y)
			self.__limitIdxFunc = lambda values: argmin(values)
			self.__INF = 1e6



		# 記錄是否要考慮韻腳
		self.__isRhyme = isRhyme

		if isRhyme:
			print "AlgoStructAlign: Rhyme Alignment Turn On."


		"""
		Penalty 分數定義 (參數設定)
		"""

		"""
		參數還要再調整
		"""

		# Open Penalty 的分數
		# 先設定為一次注音匹配分數的期望值
		#self.__POPEN = 1 * popen
		self.__POPEN = popen


		# Extension Penalty 的分數
		#self.__PEXT = 0.5 * self.__POPEN
		self.__PEXT = pext


		# Cross Penalty 的分數
		#self.__PCROSS = 2 * self.__POPEN
		self.__PCROSS = pcross
		#self.__PCROSS = 0.0


		# Non-Cross Penalty 的分數
		self.__PNONCROSS = 0.0


		"""
		常數定義
		"""
		# SPACE 所代表的字串符號
		self.__SPACE = ''


		# GAP 所代表的字串符號
		self.__GAP = '-'




		# 兩字注音match的最大相似度
		self.__MAXPINYIN = 1.0



	


	def __varInit(self):
		"""
		描述：初始化之後所有計算會用到的變數
		輸入：無
		輸出：無，初始化好該用的私有變數
		"""


		# 使用者輸入的兩條序列
		self.__seq1 = ''
		self.__seq2 = ''


		# 記錄兩條序列排比的對應關係
		self.__alignmentResult = []


		# 記錄兩條序列經過 Structure Alignment 計算後的相似度
		self.__similarity = 0.0


	def getSimilarity(self):
		"""
		描述：回傳使用者輸入的兩條序列的相似度
		輸入：無
		輸出：兩條序列經過 Structure Alignment 計算後的相似度
		"""

		return self.__similarity



	def similarity(self, seq1, seq2):
		""" 
		描述：計算兩條序列經由 Structure Alignment 計算的相似度
			長度較短的序列記錄在 self.__seqI，較長的序列記錄在 self.__seqJ

		輸入：兩條序列，每條是一個 list 其中空白是用 '' 字串符號來表示
		輸出：兩條序列經過 Structure Alignment 計算後的相似度
		"""


		# 變數宣告並且初始化
		self.__varInit()


		# 記錄兩條輸入序列
		self.__seqI = seq1[:]
		self.__seqJ = seq2[:]


		# 在兩條序列的最前面插入 GAP 字串符號
		# self.__seqI 與 self.__seqJ 的序列總長度都加一
		self.__seqI.insert(0, self.__GAP)
		self.__seqJ.insert(0, self.__GAP)


		# 計算兩條序列不包含 GAP 與 SPACE 字串符號的序列長度
		# self.__lenI 與 self.__lenJ 是記錄實際要排比的符號長度
		self.__lenI = len([element for element in self.__seqI if element != self.__SPACE and element != self.__GAP])
		self.__lenJ = len([element for element in self.__seqJ if element != self.__SPACE and element != self.__GAP])


		# 將長度較短序列的指定給 self.__seqI，較長的序列指定給 self.__seqJ
		if self.__lenI > self.__lenJ:
			temp = self.__seqI 
			self.__seqI = self.__seqJ
			self.__seqJ = temp

			temp = self.__lenI
			self.__lenI = self.__lenJ
			self.__lenJ = temp


		# 記錄 tableS 的 shape，i 軸是原本 self.__seqI 的長度，j 軸是原本 self.__seqJ的長度 
		# 此長度有包含 GAP 與 SPACE 字串符號
		self.__rowLen = len(self.__seqI)
		self.__colLen = len(self.__seqJ)

		
		# 產生 Score Table、Gap Table 以及 Local Similarity Table，三個 table shape 一樣
		self.__tableS = zeros([self.__rowLen, self.__colLen], float)
		self.__tableG = zeros(self.__tableS.shape, float)
		self.__tableL = zeros(self.__tableS.shape, float)


		# 初始化 table
		self.__initTable()


		# 填充 Score Table 與 Gap Table，利用 DP 技巧來求出兩序列的相似度
		self.__tableComputing()


		#正規化兩序列的相似度分數，目前以較短的序列當作分母
		"""
		正規化的參數需要再作調整
		"""
		#self.__similarity = self.__tableS[-1][-1] / ( self.__lenI  + self.__lenJ )
		self.__similarity = self.__tableS[-1][-1] /  self.__lenI


		if self.__similarity < 0:
			self.__similarity = 0.0



		# 回溯排比後的 index 對應路徑
		#tempResult = []
		tempResult = self.__backTracking()


		# 每一個 index pair 減 1 才會變成 input sequence 的 index
		# 目的是將 tempResult list 中的 index pair 轉換成使用者輸入的序列可以對應的 index
		transformPair = (-1, -1)

		for i in range(len(tempResult)):
			tempResult[i] = tuple(map(sum, zip(tempResult[i], transformPair)))


		self.__alignmentResult = tempResult


		# 回傳兩句子相似度的分數
		return self.__similarity




	def getAlignmentResult(self):
		""" 
		描述：回傳兩條序列排比後對應的 index，如果某個 index 是對應到 GAP 的情形，對應的會是 -1
		輸入：無
		輸出：兩條序列排比後對應的 index list，其中形式為 [(idxI1, idxJ2), (idxI2, idxJ5), ...] 
	
		"""

		return self.__alignmentResult



	def __initTable(self):
		"""
		描述：初始化 Score Table、Gap Table 以及 Local Simlarity Table
		輸入：無
		輸出：無，Score Table、Gap Table 以及 Local Simlarity Table 會有初始化的數值
		"""
	
		"""
		初始化 Score Table
		"""
		for i in range(1, self.__rowLen):
			self.__tableS[i][0] = self.__INF


		for j in range(1, self.__colLen):
			#self.__tableS[0][j] = self.__POPEN + (j - 1) * self.__PEXT

			if self.__seqJ[j - 1] == self.__GAP or self.__seqJ[j - 1] == self.__SPACE:
				self.__tableS[0][j] = self.__tableS[0][j - 1] + self.__POPEN
			else:
				if self.__seqJ[j] == self.__SPACE:
					self.__tableS[0][j] = self.__tableS[0][j - 1] + self.__PCROSS
				else:
					self.__tableS[0][j] = self.__tableS[0][j - 1] + self.__PEXT




		"""
		初始化 Gap Table
		"""
		# 尋找 SPACE 字串符號在 self._seqJ 出現的位置
		spaceIdxList = [idx for idx in range(self.__colLen) if self.__seqJ[idx] == self.__SPACE]
		self.__tableG[0] = self.__tableS[0][:]


		# 在 table G  中的 j 軸有出現 SPACE 字串符號的話，整行設為負無限大
		for j in range(self.__colLen):
			if j == 0 or j in spaceIdxList:
				for i in range(self.__rowLen):
					self.__tableG[i][j] = self.__INF


		"""
		初始化 Local Similarity Table
		"""
		# 將 self.__seqI 與 self__seqJ 中實際要排比的元素 index 取出來
		seqiIdxList = [idx for idx in range(self.__rowLen) if self.__seqI[idx] != self.__SPACE and self.__seqI[idx] != self.__GAP]
		seqjIdxList = [idx for idx in range(self.__colLen) if self.__seqJ[idx] != self.__SPACE and self.__seqJ[idx] != self.__GAP]
		

		for idxI in seqiIdxList:
			for idxJ in seqjIdxList:
				self.__tableL[idxI][idxJ] = self.__simObject.similarity(self.__seqI[idxI], self.__seqJ[idxJ])


		"""
		加重韻腳的分數，如果 similarity object 是注音的話
		"""
		if self.__isRhyme:
			seqiSpaceIdx = [idx for idx in range(self.__rowLen) if self.__seqI[idx] == self.__SPACE]
			seqjSpaceIdx = [idx for idx in range(self.__colLen) if self.__seqJ[idx] == self.__SPACE]

			seqiSpaceIdx.append(self.__rowLen)
			seqjSpaceIdx.append(self.__colLen)

			for idxI in seqiSpaceIdx:
				for idxJ in seqjSpaceIdx:

					if self.__tableL[idxI - 1][idxJ - 1] > 0:

						"""
						兩個字注音 match 最的相似度為 1.0
						"""
						self.__tableL[idxI - 1][idxJ - 1] = self.__MAXPINYIN
					else:
						self.__tableL[idxI - 1][idxJ - 1] = 0.0



	def __tableComputing(self):
		"""
		描述：填充 Score Table 與 Gap Table 
		輸入：無
		輸出：無，Score Table 與 Gap Table 為填充好計算的值
		"""


		for i in range(1, self.__rowLen):

			for j in range(1, self.__colLen):

				# 兩序列中空白有對應到的情況
				if self.__seqI[i] == self.__SPACE and self.__seqJ[j] == self.__SPACE:
					self.__tableS[i][j] = self.__tableS[i - 1][j - 1] + self.__PNONCROSS


				# 序列 I 的空白沒有對應到的狀況
				elif self.__seqI[i] == self.__SPACE:
					self.__tableS[i][j] = self.__tableS[i - 1][j] + self.__PCROSS


				# 序列 J 的空白沒有對應到的狀況
				elif self.__seqJ[j] == self.__SPACE:
					self.__tableS[i][j] = self.__tableS[i][j - 1] + self.__PCROSS


				# 兩序列中的元素對應到的情況
				else:
					#self.__tableG[i][j] = max(self.__tableS[i][j - 1] + self.__POPEN,
					#							self.__tableG[i][j - 1] + self.__PEXT)

					self.__tableG[i][j] = self.__limitValueFunc(self.__tableS[i][j - 1] + self.__POPEN,
																self.__tableG[i][j - 1] + self.__PEXT)


					#self.__tableS[i][j] = max(self.__tableS[i - 1][j - 1] + self.__tableL[i][j],
					#							self.__tableG[i][j])

					self.__tableS[i][j] = self.__limitValueFunc(self.__tableS[i - 1][j - 1] + self.__tableL[i][j],
																self.__tableG[i][j])


	def __backTracking(self):
		"""
		描述：回朔兩條序列排比的對應路徑，回朔一條路徑即停止，目前不把所有路徑找出來
		輸入：result list，通常是空串列 []
		輸出：無，直接修改輸入的 result list，將排比的路徑用 index tuple 一一加入 result list 中
		"""
		
		def trackTableG(i, j):
			#print "G", i,j
			#raw_input()
			path = None

			compareList = [self.__tableG[i][j - 1] + self.__PEXT, self.__tableS[i][j - 1] + self.__POPEN]
			#maxIdx = argmax(compareList)
			limitIdx = self.__limitIdxFunc(compareList)

			if limitIdx == 0:
				path = trackTableG(i, j - 1)
			else:
				path = trackTableS(i, j - 1)

			path.append( (0, j) )

			return path


		def trackTableS(i, j):
			#print "S", i, j
			#raw_input()
			path = None

			# 回朔的結束情況
			if i == 0 and j == 0:
				return []

			
			# 兩序列空白對應到的情況
			if self.__seqI[i] == self.__SPACE and self.__seqJ[j] == self.__SPACE:
				path = trackTableS(i - 1, j - 1)
				path.append( (i, j) )


			# 序列 I 的空白沒有對應到的狀況
			elif self.__seqI[i] == self.__SPACE:
				path = trackTableS(i - 1, j)
				path.append( (i, 0) )


			# 序列 J 的空白沒有對應到的狀況 或者 J 對到 GAP的情況
			elif self.__seqJ[j] == self.__SPACE or self.__seqI[i] == self.__GAP:
				path = trackTableS(i , j - 1)
				path.append( (0, j) )


			# 兩序列中的元素對應到的情況
			else:
				compareList = [self.__tableG[i][j], self.__tableS[i - 1][j - 1] + self.__tableL[i][j] ]
				#maxIdx = argmax(compareList)
				limitIdx = self.__limitIdxFunc(compareList)


				#if self.__tableS[i][j] == self.__tableG[i][j]:
				if limitIdx == 0:
					path = trackTableG(i, j)

				#elif self.__tableS[i][j] == self.__tableS[i - 1][j - 1] + self.__tableL[i][j]:
				else:
					path = trackTableS(i - 1, j - 1)
					path.append( (i, j) )

			return path



		i = self.__rowLen - 1
		j = self.__colLen - 1
		return trackTableS(i, j)
	






if __name__ == "__main__":
	import sys
	import codecs
	from SimPinyin import SimPinyin
	from SimPOS import SimPOS
	from InputProcess import Pinyin2Tuple
	from LyricsInput import FromFile
	from DistEuclidean import DistEuclidean

	reload(sys)
	sys.setdefaultencoding('utf-8')
	dirPath = 'SentenceTest/'

	"""
	檔案輸入處理
	"""
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case3突然好想妳.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case3突然好想妳.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case4_我很醜.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case4_我很醜.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case1擁抱.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case1擁抱.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case2擁抱_2.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case2擁抱_2.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + '掌聲響起.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + '掌聲響起.txt'.decode('utf-8'))
	lines = FromFile(Pinyin2Tuple()).process(dirPath + '偵錯.txt'.decode('utf-8'))
	orilines = FromFile().process(dirPath + '偵錯.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case3新不了情.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case3新不了情.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case4新不了情.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case4新不了情.txt'.decode('utf-8'))
	#lines = FromFile(Pinyin2Tuple()).process(dirPath + 'case1新不了情.txt'.decode('utf-8'))
	#orilines = FromFile().process(dirPath + 'case1新不了情.txt'.decode('utf-8'))


	seq1 = "N,Vt,,Vt,POST".split(",")
	seq2 = "N,Vt,POST".split(",")
	#seq1 = [4, '', 3]
	#seq2 = [9, '', 4, '', 3, '', 1]

	simObject = SimPOS()
	#simObject = DistEuclidean()
	#simObject = AlgoStructAlign(simObject, 3)
	simObject = AlgoStructAlign(simObject, -1.0)

	print simObject.similarity(seq1, seq2)

	print str(simObject.getAlignmentResult())

	

	"""
	#跑 Structure Alignment 演算法
	#利用 Decorator pattern 來將拼音的注音轉2-tuple 與 2-tuple 計算similarity 的兩的動作分開

	simObject = SimPinyin()
	simObject = AlgoStructAlign(simObject, -1.0, False)

	print "Main: Similarity = %.2f" % simObject.similarity(lines[0], lines[1])
	alignmentResult = simObject.getAlignmentResult()


	print "Main: Path Length = %d" % len(alignmentResult)
	print "Main: Alignment Index = \n %s" % str(alignmentResult)

	lenI = len([word for word in lines[0] if word != ''])
	lenJ = len([word for word in lines[1] if word != ''])



	
	iLineIdx = 0
	jLineIdx = 1

	if lenI > lenJ:
		iLineIdx = 1
		jLineIdx = 0
	
	

	for pair in alignmentResult:
	
		iLineEle = pair[0]
		jLineEle = pair[1]

		if iLineEle < 0 and jLineEle < 0:
			print '( -, - )'
		elif iLineEle < 0:
			print '( -, %s)' % (orilines[jLineIdx][jLineEle])
		elif jLineEle < 0:
			print '( %s, - )' % (orilines[iLineIdx][iLineEle])
		else:
			if orilines[iLineIdx][iLineEle] != '':
				print '( %s, %s ) %f' % (orilines[iLineIdx][iLineEle], orilines[jLineIdx][jLineEle] ,
							SimPinyin().similarity(lines[iLineIdx][iLineEle], lines[jLineIdx][jLineEle]) )
			else:
				print '(,)'
	

	"""

