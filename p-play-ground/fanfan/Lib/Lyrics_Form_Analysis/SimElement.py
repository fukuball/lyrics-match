# -*- coding: utf-8 -*-
from abc import abstractmethod
from SimilarityComputing import SimilarityComputing

# similarity object 的最後一個層級 沒有再下去了
class SimElement(SimilarityComputing):

	def printElements(self, e1, e2):
		print "Input Elements:"
		print "e1 = %s" % str(e1)
		print "e2 = %s" % str(e2)
