# -*- coding: utf-8 -*-
from SimElement import SimElement

class DistEuclidean(SimElement):

	def similarity(self, v1, v2):
		import numpy.linalg as linalg

		# Euclidean 距離
		distance = linalg.norm(v1 - v2)

		return distance

		
	
