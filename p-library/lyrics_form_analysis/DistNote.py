# -*- coding: utf-8 -*-
from SimElement import SimElement

class DistNote(SimElement):

	def similarity(self, n1, n2):

		"""
		一個Note 是由 (Pitch, Duration, Segment Label) 所構成
		"""
		p1 = n1[0]
		p2 = n2[0]
		
		denom = float(p1 + p2)
		distance = 0.0
		
		if denom != 0.0:
			distance = abs(p1 - p2) / denom

		return distance

		
	
