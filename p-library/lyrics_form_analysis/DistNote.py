# -*- coding: utf-8 -*-
from SimElement import SimElement
from DistPitch import DistPitch

class DistNote(SimElement):
	def __init__(self):
		self.pitchRate = 0.2
		self.durationRate = 0.3
		self.labelRate = 0.5 

	def similarity(self, n1, n2):

		"""
		一個Note 是由 (Pitch, Duration, Segment Label) 所構成
		"""
		pitch1 = n1[0]
		pitch2 = n2[0]
		pitchDist = DistPitch.similarity(pitch1, pitch2)

		duration1 = n1[1]
		duration2 = n2[1]
		durationDist = DistPitch.similarity(duration1, duration2)

		label1 = n1[2]
		label2 = n2[2]
		labelDist = 1

		if label1 == lable2:
			labelDist = 0


		distance = pitchDist * self.pitchRate + \
				durationDist * self.__durationRate + \
				labelDist * self.__labelRate
		
		return distance

		
	
