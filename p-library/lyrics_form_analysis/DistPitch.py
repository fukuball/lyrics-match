# -*- coding: utf-8 -*-
from SimElement import SimElement

class DistPitch(SimElement):

	def similarity(self, p1, p2):
		"""
		正規化的音高距離
		"""
		denom = float(p1 + p2)
		distance = 0.0
		
		if denom != 0.0:
			distance = abs(p1 - p2) / denom

		return distance

		
	
