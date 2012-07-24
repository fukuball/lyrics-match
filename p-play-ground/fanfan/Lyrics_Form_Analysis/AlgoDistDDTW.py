# -*- coding: utf-8 -*-

from AlgoSequence import AlgoSequence

class AlgoDistDDTW(AlgoSequence):

	def __init__(self, simElementObj):
		self.simElementObj = simElementObj
		self.__similarity = None
	
	def similarity(self, seq1, seq2):
		"""
		將輸入的序列做微分
		"""
		
		seq1 = self.__derivation(seq1)
		seq2 = self.__derivation(seq2)

		self.__similarity = self.simElementObj.similarity(seq1, seq2)

		return self.__similarity

	def getSimilarity(self):
		return self.__similarity
		

	def getAlignmentResult(self):
		pass


	def __derivation(self, seq):
		derSeq = []

		for i in range(1, len(seq) - 1):
			# 微分公式
			element = (seq[i] - seq[i - 1]) + ((seq[i + 1] - seq[i - 1]) / 2.0) / 2.0
			derSeq.append(element)

		return derSeq




