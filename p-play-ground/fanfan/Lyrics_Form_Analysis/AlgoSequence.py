# -*- coding: utf-8 -*-

from abc import abstractmethod
from SimilarityComputing import SimilarityComputing

# similarity object 的中間層級 可以遞迴式的下去計算similarity
class AlgoSequence(SimilarityComputing):

	@abstractmethod
	def getSimilarity(self):
		pass


	@abstractmethod
	def getAlignmentResult(self):
		pass


