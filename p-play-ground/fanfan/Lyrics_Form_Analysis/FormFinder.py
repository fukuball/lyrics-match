# -*- coding:utf-8 -*-

from copy import deepcopy
from copy import copy
from numpy import insert
from numpy import argmin
from numpy import zeros
from numpy import fromfunction

class FormFinder:

	def __init__(self, familyM, topK):
		"""
		記錄 support matrix & block info matrix
		"""
		self.__familyM = deepcopy(familyM)


		"""
		讓 parent block 的長度從 1 開始
		"""
		#self.__familyM = insert(self.__familyM, 0, 0, axis = 0)


		"""
		記錄使用者看 score top 多少的 combination 結果
		"""
		self.__topK = topK


		"""
		記錄 combination 結果的 list
		"""
		self.__topList = []

		for i in range(topK):
			self.__topList.append({"score": 0.0, "coors":[]})



		"""
		記錄歌詞的總行數
		"""
		self.__LYRICSLINE = self.__familyM.shape[1]


		"""
		Parent Block 最大的可能長度
		"""
		self.__MAXLEN = self.__familyM.shape[0]
	

		self.__MAXKIND = 3




	def computing(self):
		"""
		計算所有 Family 組合的結果 至多三種，至少兩種
		"""


		"""
		parent block 起始行數最多只到總行數除以 2 
		"""
		for start in range(self.__LYRICSLINE / 2):
			"""
			parent block 長度從 2 開始，長度為 1 的 parent block 意義不大
			"""
			for blockLen in range(2, self.__MAXLEN):

				if self.__familyM[blockLen][start] != None:
					self.__combination(blockLen, start, [(blockLen, start)])
				


		"""
		將 top list 依照 score 排序
		"""
		keyFunc = lambda block: block["score"]
		self.__topList = sorted(self.__topList, key = keyFunc, reverse = True)

		
		return self.__topList





	def getTopList(self):
		return self.__topList



	def __combination(self, blockLen, start, combineList):

		if len(combineList) == self.__MAXKIND:
			#print "max kind", combineList
			#raw_input()
			return 


		"""
		下一個串接的 parent block 起始行數
		"""
		nextStart = start + blockLen


		for nStart in range(nextStart, self.__LYRICSLINE):
			for nBlockLen in range(2, self.__MAXLEN):

				if self.__familyM[nBlockLen][nStart] != None:

					"""
					下一個串接的 family
					"""
					newFamily = self.__familyM[nBlockLen][nStart]["family"]
					isOverlap = False
					isInterleave = False


					"""
					判斷新的 family 是否有與舊的 family overlap 或是 interleave
					"""
					for coor in combineList:
						prevFamily = self.__familyM[coor]["family"]
						#print "prev", prevFamily
						#raw_input()

						if self.__overlapCombine(prevFamily, newFamily):
							isOverlap = True

						"""
						有一個 True 即可
						"""
						isInterleave = (isInterleave | self.__interleaveCheck(prevFamily, newFamily))



					"""
					確認 prevFamily 是否可以與 newFamily 組合
					"""
					if not isOverlap and isInterleave:
					#if not isOverlap:
						#print "last", blockLen, start
						#print "new", nBlockLen, nStart
						#print combineList
						#print
						#raw_input()
						#print "non overlap"

						newCombineList = combineList + [(nBlockLen, nStart)]
						score = self.__scoring(newCombineList)
						topScoreList = map(lambda candidate: candidate["score"], self.__topList)


						"""
						判斷 score 是否有擠進top list
						"""
						if score > min(topScoreList):
							minIdx = argmin(topScoreList)


							#print "form", form 
							#raw_input()

							self.__topList[minIdx]["score"] = score
							self.__topList[minIdx]["coors"] = newCombineList



						"""
						串接新的 block family
						"""
						self.__combination(nBlockLen, nStart, newCombineList)


		


	def __scoring(self, combineList):
		"""
		計算此 form 的 score
		"""
		infoList = map(lambda coor: self.__familyM[coor], combineList)


		cohesionList = map(lambda item: item["cohesion"], infoList)
		coverageList = map(lambda item: item["coverage"], infoList)
		blockLenList = map(lambda item: item["family"][0][1] - item["family"][0][0] + 1, infoList)

		totalCoverage = sum(coverageList)
		totalBlockLen = sum(blockLenList)

		
		precision = sum(cohesionList) / len(combineList)
		#precision = sum(map(lambda cohesion, coverage: cohesion * coverage / totalCoverage, cohesionList, coverageList))
		#precision = sum(map(lambda cohesion, blockLen: cohesion * blockLen / totalBlockLen, cohesionList, blockLenList))
		
		#denomi = self.__coverState(form)
		recall = totalCoverage / float(self.__LYRICSLINE)


		"""
		Global F score
		"""
		score = 2 * precision * recall / (precision + recall)

		return score
						
		


	def __coverState(self, form):
		denomi = self.__LYRICSLEN

		family1 = form[0]	
		family2 = form[1]	

		family = family1 + family2


		"""
		計算沒有 cover 的  range
		"""
		remainLineNum = set(range(1, self.__LYRICSLEN + 1))

		for block in family:
			start = block[0]
			end = block[1]

			remainLineNum -= set(range(start, end + 1))


		if len(remainLineNum) > 0:

			remainLineNum = list(remainLineNum)	
			prevLineNum = remainLineNum[0] - 1
			remainRange = []
			remainCover = []


			for i in range(0, len(remainLineNum)):
				
				if prevLineNum + 1 != remainLineNum[i]:
					"""
					一個 block 形成
					"""
					start = remainRange[0]
					end = remainRange[-1]

					"""
					remain cover list 加入 range 的長度
					"""
					remainCover.append([start, end])
					remainRange = [remainLineNum[i]]
				else:
					remainRange.append(remainLineNum[i])


				prevLineNum = remainLineNum[i]



			start = remainRange[0]
			end = remainRange[-1]

			"""
			remain cover list 加入 range 的長度
			"""
			remainCover.append([start, end])



			"""
			加入歌詞分佈 constraint
			"""
			#if len(remainCover) > 0: 

			if remainCover[0] == [1, 2]:
				denomi -= 2


			if remainCover[-1] == [self.__LYRICSLEN, self.__LYRICSLEN]:
				denomi -= 1


			if len(remainCover[1:-1]) == 1 and (remainCover[1][1] - remainCover[1][0] + 1) >= 2:
				denomi -= 2
	
		

		return denomi

			
		


	def __interleaveCheck(self, family1, family2):

		blockList = []

		for block in family1:
			blockList.append((1, block[0]))
		
			
		for block in family2:
			blockList.append((2, block[0]))


		keyFunc = lambda pair: pair[1]
		blockList = sorted(blockList, key = keyFunc)


		kindChange = 0

		"""
		沒有overlap，並且計算兩種 block family 的交錯次數
		"""
		for i in range(len(blockList)  - 1):
			if blockList[i][0] != blockList[i + 1][0]:
				kindChange += 1
		
		
		#print "f1, ", family1
		#print "f2, ", family2
		#print "block list", blockList
		#print "kind change", kindChange
		#raw_input()

		"""
		若兩family有交錯，則 kindChange 必定會大於等於 3 次
		"""
		if kindChange >= 3:
			return True
		else:
			return False
		



	def __overlapCombine(self, family1, family2):
		"""
		兩種 family
		"""
		keyFunc = lambda block: block[0]
		familyList = sorted(family1 + family2, key = keyFunc)


		for i in range(len(familyList) - 1):

			"""
			判斷 是否有 overlap
			"""
			if familyList[i + 1][0] >= familyList[i][0] and \
						familyList[i + 1][0] <= familyList[i][1]:
				#有overlap
				return True

		return False	



	
