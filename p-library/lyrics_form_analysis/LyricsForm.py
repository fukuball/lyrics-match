# -*- coding: utf-8 -*-

import copy
import time
import math
import numpy 
import networkx as nx
from Visualization import Visualization
from SSMOperation import SSMOperation
from ChildrenFinder import ChildrenFinder
from FormFinder import FormFinder

class LyricsForm:

	def __init__(self):

		self.__viz = Visualization()
		self.__ssm = SSMOperation()

		self.__LENRATE = 6 / 7.0
		self.__TOPK = 1
	



	def formAnalysis(self, SSM, isViz = False, topk = 5):
		self.__TOPK = topk

		"""
		視覺化Matrix
		"""
		if isViz:
			self.__SSMViz(SSM)

		"""
		計算 SSM 中所有的 Block Family
		"""
		startTime = time.time()
		(self.__allFamilyM, cohesionM) = self.__allBlockFamily(SSM)
		endTime = time.time()
		print "LyricsForm: Family Matrix Construction Time = %.2fsec" % (endTime - startTime)

	
		"""
		產生 Cohesion Matrix
		"""
		#self.__viz.grayMatrix(cohesionM, "Cohesion Matrix")


		"""
		Block Family Combination
		"""
		self.__allFamilyM = numpy.insert(self.__allFamilyM, 0, 0, axis = 0)
		startTime = time.time()
		ff = FormFinder(self.__allFamilyM, self.__TOPK)
		combineList = ff.computing()
		endTime = time.time()

		print "LyricsForm: Block Combination Time = %.2fsec" % (endTime - startTime)
		print



		"""
		combineList 轉換成詞式格式
		"""
		lineNum = SSM.shape[0]
		formList = self.__resultForm(combineList, lineNum)


		return formList

	

	def __resultForm(self, combineList, lineNum):

		resultList = []

		for combine in combineList:
			lyricsLine = set(range(1, lineNum + 1))
			form = []
			familyList = []
			cohesionList = []
			lengthList = []
			startList = []

			for coor in combine["coors"]:
				cohesion = self.__allFamilyM[coor[0]][coor[1]]["cohesion"]
				family = self.__allFamilyM[coor[0]][coor[1]]["family"]
	
				lineNumFamily = map(lambda block: [block[0] + 1, block[1] + 1], family)

				familyList.append(lineNumFamily)
				cohesionList.append(cohesion)
				lengthList.append(len(lineNumFamily))
				startList.append(lineNumFamily[0][0])

				for block in lineNumFamily:
					start = block[0]
					end = block[1]
					lyricsLine -= set(range(start, end + 1))


			if len(cohesionList) > 0:
				"""
				判斷副歌
				"""
				remainFamilyIdx = range(len(familyList))

				if len(remainFamilyIdx) != 2:
					print "family number != 2"
					exit()

				vote = [0, 0]
				vote[numpy.argmax(cohesionList)] += 1
				vote[numpy.argmax(lengthList)] += 1
				vote[numpy.argmax(startList)] += 1
				chorusIdx = numpy.argmax(vote)
				

				"""
				長度較長的family
				"""
				#maxLength = max(lengthList)
				#remainFamilyIdx = [familyIdx for familyIdx in remainFamilyIdx if lengthList[familyIdx] == maxLength]


				"""
				內聚力較大的family
				"""
				#maxCohesion = max(map(lambda idx: cohesionList[idx], remainFamilyIdx))
				#error = 10e-5
				#remainFamilyIdx = [familyIdx for familyIdx in remainFamilyIdx if abs(cohesionList[familyIdx] - maxCohesion) < error]


				"""
				樣式段落位置較前面的 family
				"""
				#maxStart = 0
				#chorusIdx = None

				#for familyIdx in remainFamilyIdx:
				"""
				family 中第一個block的start line 最小的
				"""
				#	if familyList[familyIdx][0][0] > maxStart:
				#		chorusIdx = familyIdx
				#		maxStart = familyList[familyIdx][0][0]



				chorus = {"label": "chorus", "group": familyList[chorusIdx]}
				form.append(chorus)

				familyList.pop(chorusIdx)


				"""
				判斷主歌
				"""
				for i in range(len(familyList)):
					#verse = {"label": "verse" + str(i + 1), "group": familyList[i]}
					verse = {"label": "verse", "group": familyList[i]}
					form.append(verse)



				"""
				判斷前段、橋段與尾聲
				"""
				if len(lyricsLine) > 0:
					lyricsLine = sorted(list(lyricsLine))
					prevLineNum = lyricsLine[0] - 1
					block = []
					remainBlocks = []


					for i in range(0, len(lyricsLine)):
						
						if prevLineNum + 1 != lyricsLine[i]:
							"""
							一個 block 形成
							"""
							remainBlocks.append([ block[0], block[-1] ])
							block = [lyricsLine[i]]
						else:
							block.append(lyricsLine[i])


						prevLineNum = lyricsLine[i]



					remainBlocks.append([ block[0], block[-1] ])

					
					"""
					加入前段
					"""
					if len(remainBlocks) > 0 and remainBlocks[0][0] == 1:
						form.append({"label": "intro", "group": [remainBlocks.pop(0)]})

					"""
					加入尾聲
					"""
					if len(remainBlocks) > 0 and remainBlocks[-1][1] == lineNum:
						form.append({"label": "outro", "group": [remainBlocks.pop(-1)]})

					"""
					加入橋段
					"""
					if len(remainBlocks) > 0:
						form.append({"label": "bridge", "group": remainBlocks})



					
				resultList.append({"score": combine["score"], "form": form})
				


				
		return resultList

			





	def __allBlockFamily(self, M):
		M = copy.deepcopy(M)


		"""
		記錄所有 family 的資料結構 table
		"""
		familyM = []

		for i in range(M.shape[0] / 2):
			familyM.append([None] * M.shape[0])



		"""
		將 SSM 中相似度為 1.0 的值去除，記錄到 exOneArray 中
		"""
		exOneArray = numpy.extract(M != 1.0, M)



		"""
		計算 Children 與 Parent 之間的相似度(Similarity)門檻值
		"""
		simT = exOneArray.mean() + exOneArray.std()
		#simT = exOneArray.mean()


		"""
		產生 ChildrenFinder 物件 cf，並且傳入這首歌詞的總行數
		"""
		cf = ChildrenFinder()


		"""
		Cohesion Matrix
		"""
		cohesionM = numpy.ones(M.shape, float)


		"""
		計算所有的 Parent Block (start line & size) 的 Children
		"""
		for size in range(2, len(M) / 2 + 1):
			for start in range(0, M.shape[0] - size):

				"""
				建立 SSM 的 Corridor(廊道) Matrix 
				"""
				corridorMask = numpy.zeros(M.shape)
				corridorMask[start: start + size] = 1
				corridorM = M * corridorMask


				"""
				找出 start 到 start + size  parent block 所框出的 children matrix 範圍
				"""
				childrenMatrix = M[start: start + size, start + size: M.shape[1] ]


				"""
				計算 Children 與 Parent 之間的長度(Length)門檻值
				"""
				lenT = math.ceil(float(size) * self.__LENRATE)
				
				#lenT = float(size)

				#if lenT > 7:
				#	lenT -= 1


				"""
				利用 Children Finding Algorithm 計算出此 Parent 最佳的 Repeating Pattern 所形成的 Children
				"""
				blockFamily = cf.children(childrenMatrix, lenT = lenT, simT = simT)

				if size == 2 and start == 0:
					print blockFamily

				
				"""
				有找到 children 才需要進一步考慮
				"""
				if blockFamily != []:

					"""
					Family Block Range 移動到絕對位置的 Range，Family 的 Block 都是從 第1行 開始算起
					並且加入 Parent Block 本身到 Block Family 中
					"""
					for i in range(len(blockFamily)):
						blockFamily[i] = [lineNum + (start + size) for lineNum in blockFamily[i]]

					blockFamily.insert(0, [start, start + size - 1])


					"""
					計算此 family 所形成的 complete graph G(V, E) ，E 上的 weight 表示兩個 block 之間的相似度
					以及 family 的 cohesion 
					"""
					familyGraph, familyCohesion = self.__familyGraphBuild(blockFamily, M)


					"""
					family 的覆蓋長度總合
					"""
					familyCoverage = sum(map(lambda block: block[1] - block[0] + 1, blockFamily))


					"""
					將 familyBlock 記錄到 Block Matrix
					"""
					familyM[size - 1][start] = {"graph": familyGraph, "family": blockFamily, 
								"cohesion": familyCohesion, "coverage": familyCoverage}


					cohesionM[size - 1, start] = familyCohesion
				else:
					cohesionM[size - 1, start] = 0.0



				

				"""
				視覺化檢查工具
				"""
				#self.__viz.grayMatrix(corridorM, "Row Mask SSM: start= " + str(start) + " size= " + str(size))

				#pathMask = cf.getPathMask()
				#familyMask = cf.getFamilyMask()

				#corridorM[start: start + size, start + size: M.shape[1] ] = pathMask
				#self.__viz.grayMatrix(corridorM, "Path Mask")

				#corridorM[start: start + size, start + size: M.shape[1] ] = familyMask
				#self.__viz.grayMatrix(corridorM, "Family Mask")



		return familyM, cohesionM




	def __familyGraphBuild(self, family, M):
		"""
		建立 family 的 complete graph
		"""
		familyGraph = nx.complete_graph(len(family))
		cohesion = 0.0


		for i in range(len(family) - 1):
			for j in range(i + 1, len(family)):
				
				"""
				選擇長度較短的 block length 為 i 軸
				"""
				shortIdx = i
				longIdx = j

				shortLen = family[i][1] - family[i][0] + 1
				longLen = family[j][1] - family[j][0] + 1


				if shortLen > longLen:
					"""
					交換
					"""
					shortIdx, longIdx = j, i
					shortLen, longLen = longLen, shortLen



				sim = 0.0
				iSlice = slice( family[shortIdx][0], family[shortIdx][1] + 1 )
				windowSize = shortLen


				"""
				計算兩個 block 的相似度所需跑的迴圈數
				"""
				loopNum = longLen - shortLen + 1


				for offset in range(loopNum):

					jSlice = slice( family[longIdx][0] + offset, family[longIdx][0] + windowSize + 1)
					tempSim = M[iSlice, jSlice].trace() / windowSize

					if tempSim > sim:
						sim = tempSim
				

				"""
				將計算好的相似度(sim)放入 family graph 的 edge 上
				"""
				familyGraph[i][j]["sim"] = sim
				cohesion += sim


		cohesion = cohesion / (len(family) * (len(family) - 1) / 2.0)


		#print "block family", family
		#print "family graph", familyGraph.edge
		#print "cohesion", cohesion
		#raw_input()

		return familyGraph, cohesion
		



	def SSMGen(self, lines, simObject, matrixType='sim'):
		"""
		產生自比較矩陣 Self Matrix
		"""
		
		startTime = time.time()

		"""
		宣告句子相似度矩陣 type: numpy.array
		"""
		SSM = numpy.zeros([len(lines), len(lines)])

		
		"""
		Self Matrix 建立
		"""
		for i in range(len(lines)):
			for j in range(i, len(lines)):
				lineSim = simObject.similarity(lines[i][:], lines[j][:])
				
				"""
				對稱的矩陣
				"""
				SSM[i][j] = SSM[j][i] = lineSim


		"""
		計算 Matrix 中最大的數值是多少
		"""
		maxValue = SSM.max()
		print "LyricsForm: Matrix Max Value : %f" % maxValue


		"""
		因為 DTW 演算法計算兩序列的距離，如果超過無限大，則會為回傳 -1
		所以，如果 Matrix 中存在負的值，便將此值設為 Matrix 中的最大數值的
		"""
		for rowIdx in range(SSM.shape[0]):
			SSM[rowIdx] = map(lambda ele: ele < 0.0 and maxValue or ele, SSM[rowIdx])


		"""
		如果 Similarity Object 是計算距離的話，就將 Matrix 中的數值從距離轉換成相似度，值越大越像
		"""


		#print simObject.__class__.__name__
		#if 'Dist' in simObject.__class__.__name__:
		if 'dist' in matrixType:	
			#由 Matrix 中最大的距離來當作最低的相似度

			"""
			minDist = M.min()
			maxDist = M.max()
			M = 1 - ((M - minDist) / (maxDist - minDist))
			"""

			maxM = numpy.ones(SSM.shape) * maxValue
			SSM = maxM - SSM
	
		SSM = self.__ssm.localNormalize(SSM)

		endTime = time.time()

		print "LyricsForm: SSM Construction Time = %.2fsec" % (endTime - startTime)
		print "LyricsForm: Matrix Shape = %s" % str(SSM.shape)

		#print "LyricsForm: SSM Visualization..."
		#self.__SSMViz(SSM)


		return SSM



	
	def __SSMViz(self, SSM):
		"""	
		建立好的 Self Matrix 裡頭的每個 Element 
		有可能是 Distance 也有可能是 Similarity
		目前是將 Distance 都轉換成 Similarity 
		最後會得到一個 SSM
		"""


		"""
		將 SSM 做 Local Normalize，也就是除以 SSM 中的最大值，讓 SSM 中的值介在 [0, 1]
		此步驟只對 Distance Matrix 以及 沒有 Normalize 的 Similarity Matrix 有效果
		"""
		#self.__viz.grayMatrix(SSM, "Local Normalized SSM: " + self.__simObjClassName)
		self.__viz.grayMatrix(SSM, "Local Normalized SSM: ")


		#SSM = self.__ssm.secondOrder(SSM)
		#self.__viz.grayMatrix(SSM, "Second Order SSM: " + self.__simObjClassName)

		"""
		擷取 Exact Path 出現的位置
		"""
		#exactMask = self.__ssm.masker(SSM, 1.0)
		#self.__viz.grayMatrix(exactMask, "Exact Mask: " + self.__simObjClassName)

		
		"""
		SSM Enhancement
		enhance 函數的最後一個值是設定 L, L = 4
		"""
		#enM = self.__ssm.enhance(SSM, 2)
		#self.__viz.grayMatrix(enM, "Enhanced SSM: " + self.__simObjClassName)


		"""
		Higher Order Matrix
		"""
		#SSM = self.__ssmSecondOrder(SSM)
		#SSM = self.__ssmNormalization(SSM)
		#self.__viz.grayMatrix(SSM, "Second Order SSM: " + self.__simObjClassName)



		"""
		擷取 Enhanced Matrix 中的 Approximate Path 出現的位置
		門檻值 = 平均值 + 一個標準差
		"""
		#threshold = enM.mean() + enM.std()
		#approxMask = self.__ssm.masker(enM, threshold)
		#self.__viz.grayMatrix(approxMask, "Approximate Mask: " + self.__simObjClassName)



		"""
		將 exact mask 與 approximate mask 做聯集
		"""
		#mask = map(numpy.bitwise_or, approxMask, exactMask)
		#self.__viz.grayMatrix(mask, "Total Mask: " + self.__simObjClassName)


		#self.__viz.grayMatrix(SSM * mask, "Original SSM Mask: " + self.__simObjClassName)
		#self.__viz.grayMatrix(enM * mask, "Enhanced SSM Mask: " + self.__simObjClassName)



	
	def __matrix2ssm(self, M):
		M = copy.deepcopy(M)
		
		"""
		計算 Matrix 中最大的數值是多少
		"""
		maxValue = M.max()
		print "LyricsForm: Matrix Max Value : %f" % maxValue


		"""
		因為 DTW 演算法計算兩序列的距離，如果超過無限大，則會為回傳 -1
		所以，如果 Matrix 中存在負的值，便將此值設為 Matrix 中的最大數值的
		"""
		for rowIdx in range(M.shape[0]):
			M[rowIdx] = map(lambda ele: ele < 0.0 and maxValue or ele, M[rowIdx])


		"""
		如果 Similarity Object 是計算距離的話，就將 Matrix 中的數值從距離轉換成相似度，值越大越像
		"""
		if 'Dist' in self.__simObjClassName:
			#由 Matrix 中最大的距離來當作最低的相似度

			"""
			minDist = M.min()
			maxDist = M.max()
			M = 1 - ((M - minDist) / (maxDist - minDist))
			"""

			mm = numpy.ones(M.shape) * maxValue
			M = mm - M


		return M






	

if __name__ == '__main__':
	import sys
	import codecs
	import os
	from LyricsInput import FromFile

	reload(sys)
	sys.setdefaultencoding('utf-8')


	def fileSelect(dirPath, fileProcess):
		#lines = FromFile(fileProcess).process(dirPath + '新不了情.txt')
		#lines = FromFile(fileProcess).process(dirPath + '朋友.txt')
		#lines = FromFile(fileProcess).process(dirPath + '擁抱.txt')
		#lines = FromFile(fileProcess).process(dirPath + '朋友未分段.txt')
		#lines = FromFile(fileProcess).process(dirPath + '擁抱未分段.txt')
		#lines = FromFile(fileProcess).process(dirPath + '新不了情_未分段.txt')
		lines = FromFile(fileProcess).process(dirPath + 'hand.txt')

		return lines





	def pinyinFormAnalysis():
		"""
		利用注音的方法
		"""
		print
		print "Main: Pinyin Form Analysis"

		from AlgoStructAlign import AlgoStructAlign
		from SimPinyin import SimPinyin
		from InputProcess import Pinyin2Tuple

		dirPath = 'LyricsFormTest/pinyin/old_famous/'
		fileNameList = os.listdir(dirPath)

		fileProcess = Pinyin2Tuple()


		for fileName in fileNameList:
			print "Main: %s" % fileName
			
			lines = FromFile(fileProcess).process(dirPath + (fileName.decode('big5')))
			#lines = fileSelect(dirPath, fileProcess)

			simObject = SimPinyin()
			simObject = AlgoStructAlign(simObject)
			LyricsForm(simObject).formAnalysis(lines)


	def toneFormAnalysis():
		"""
		利用聲調的方法
		"""
		print
		print "Main: Pitch Tone Form Analysis"

		from AlgoClassicalDTW import AlgoClassicalDTW
		from AlgoDDTW import AlgoDDTW
		from DistNote import DistNote
		from InputProcess import Str2Int
		from InputProcess import Tone2Pitch
		from LocalConstraint import StepType2
		from LocalConstraint import StepType3
		from LocalConstraint import StepType1

		dirPath = 'LyricsFormTest/tone/old_famous/'
		fileNameList = os.listdir(dirPath)

		fileProcess = Tone2Pitch()
		fileProcess = Str2Int(fileProcess)


		for fileName in fileNameList:
			print "Main: %s" % fileName
			#lines = fileSelect(dirPath, fileProcess)
			lines = FromFile(fileProcess).process(dirPath + (fileName.decode('big5')))

			simObject = DistNote()
			simObject = AlgoClassicalDTW(simObject, stepType = StepType2())
			simObject = AlgoDDTW(simObject)
			LyricsForm(simObject).formAnalysis(lines)


	#pinyinFormAnalysis()
	toneFormAnalysis()
	

