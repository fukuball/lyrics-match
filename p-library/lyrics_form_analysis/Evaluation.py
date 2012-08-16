# -*- coding: utf-8 -*-

from itertools import combinations
from itertools import chain
from math import log
from numpy import array
from numpy import zeros
from numpy import log2 #universal function
from numpy import where


class Evaluation:

	def __init__(self):
		pass


	def pairwiseFScore(self, estimate, truth):
		eSet = self.__pairwiseSetGen(estimate)
		tSet = self.__pairwiseSetGen(truth)

		interLen = float(len(eSet & tSet))


		"""
		Pairwise Precision
		"""
		precision = interLen / len(eSet)
		#print "precision", precision


		"""
		Pairwise Recall
		"""
		recall = interLen / len(tSet)
		#print "recall", recall


		"""
		Pairwise F score
		"""
		f = 2 * precision * recall / (precision + recall)

		return f
		
		
	
	def __pairwiseSetGen(self, form):
		"""
		產生group內兩兩關連的pair
		E.g. group = [1,2][5,6], 所有關聯 pair = (1, 2), (1, 5), (1, 6), (2, 5), (2, 6)
		"""

		linePairSet = set()

		for group in form:
			lineList = []

			for block in group["group"]:
				start = block[0]
				end = block[1]

				"""
				將 blocks 中的行數區間變成 range並且放到 lineList
				"""
				for lineNum in chain(range(start, end + 1)):
					lineList.append(lineNum)
				

			"""
			將 lineList 中的行數做兩兩的組合
			"""
			for linePair in combinations(lineList, 2):
				linePairSet.add(linePair)
					
		
		return linePairSet
				



	def NCE(self, estimate, truth, lineNum):
		"""
		Normalized Conditional Entropy
		"""
		eKind = len(estimate)
		tKind = len(truth)

		over = 1 - (self.__condEntropy(estimate, truth, float(lineNum)) / log(eKind, 2))
		under = 1 - (self.__condEntropy(truth, estimate, float(lineNum)) / log(tKind, 2))

		return over, under




	def __condEntropy(self, y, x, lineNum):
		"""
		Joint Probability Matrix P(Estimate, Truth)
		"""
		jpMatrix = zeros([len(y), len(x)], float)



		for i in range(len(y)):
			for j in range(len(x)):
				yRangeSet = self.__rangeSet(y[i])
				xRangeSet = self.__rangeSet(x[j])
				jpMatrix[i, j] = len(yRangeSet & xRangeSet) / lineNum
			

		"""
		Marginal Probability Vector
		"""
		mpVector = sum(jpMatrix)



		tempMatrix = jpMatrix / mpVector


		"""
		防止 log2(0.0) 數學上 undefined 的發生
		"""
		coorMatrix =  where(tempMatrix == 0.0)
		coorList = zip(coorMatrix[0], coorMatrix[1])

		for coor in coorList:
			tempMatrix[coor] = 1.0


		"""
		Conditional Entropy
		"""
		result = -1 * sum(sum(jpMatrix * log2(tempMatrix)))
		return  result




	def __rangeSet(self, group):
		"""
		將 group 裡的 blocks 轉成行數區間的集合 E.g. [[1, 3][5, 8]] --> set([1, 2, 3, 5, 6, 7, 8])
		"""
		rangeSet = set()	

		for block in group["group"]:
			blockRange = range(block[0], block[1] + 1)

			for element in blockRange:
				rangeSet.add(element)


		return rangeSet
