# -*- coding: utf-8 -*-
from SimElement import SimElement

class DistSenStruct(SimElement):
	#def __init__(self, spaceWeight = 0.6):
	def __init__(self, spaceWeight = 2):
		self.__SPACEW = spaceWeight


	def similarity(self, list1, list2):
		"""
		# 正規化後的距離
		numOfWordDist = abs(sum(list1) - sum(list2)) / (float(sum(list1) + sum(list2)))
		numOfSpaceDist = abs(len(list1) - len(list2)) / (float(len(list1) + len(list2)))


		# 字數與空白的距離做結合，空白距離的權重較大
		distance = (1 - self.__SPACEW) * numOfWordDist + self.__SPACEW * numOfSpaceDist 
		"""

		numOfWordDist = abs(sum(list1) - sum(list2))
		numOfSpaceDist = abs(len(list1) - len(list2))
		distance = self.__SPACEW * numOfSpaceDist + numOfWordDist


		return distance

		
	

