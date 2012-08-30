# -*- coding: utf-8 -*-
from SimElement import SimElement
from DistPitch import DistPitch
from DistDuration import DistDuration
from numpy.linalg import norm

class DistNote(SimElement):
	def __init__(self, pitchRate = 0.2, durationRate = 0.3, labelRate = 0.5):
		self.__pitchRate = pitchRate
		self.__durationRate = durationRate
		self.__labelRate = labelRate

	def similarity(self, n1, n2):

		"""
		一個Note 是由 (Pitch, Duration, Segment Label) 所構成
		"""
		pitch1 = n1[0]
		pitch2 = n2[0]
		pitchDist = DistPitch().similarity(pitch1, pitch2)

		duration1 = n1[1]
		duration2 = n2[1]
		durationDist = DistDuration().similarity(duration1, duration2)

		label1 = n1[2]
		label2 = n2[2]
		labelDist = (label1 == label2) and 0 or 1

		#distance = pitchDist * self.__pitchRate + \
		#		durationDist * self.__durationRate + \
		#		labelDist * self.__labelRate
		distance = norm((pitchDist, durationDist, labelDist)) / norm((1, 1, 1))

		print distance
		
		return distance

		
	
