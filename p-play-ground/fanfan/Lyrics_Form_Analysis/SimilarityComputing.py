# -*- coding: utf-8 -*-
from abc import ABCMeta, abstractmethod

class SimilarityComputing:
	__metaclass__ = ABCMeta

	@abstractmethod
	def similarity(self, obj1, obj2):
		pass
