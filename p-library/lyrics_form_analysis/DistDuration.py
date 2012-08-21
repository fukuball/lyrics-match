# -*- coding: utf-8 -*-
from SimElement import SimElement

class DistDuration(SimElement):

	def similarity(self, p1, p2):
		distance = abs(p1 - p2) / 8.0

		return distance

		
	
