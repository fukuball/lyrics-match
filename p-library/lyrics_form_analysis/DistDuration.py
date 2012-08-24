# -*- coding: utf-8 -*-
from SimElement import SimElement

class DistDuration(SimElement):
	def __init__(self):
		self.__MAXDURATION = 4.0

	def similarity(self, p1, p2):
		
		distance = abs(p1 - p2) / self.__MAXDURATION

		return distance

		
	
