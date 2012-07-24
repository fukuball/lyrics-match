# -*- coding: utf-8 -*-
from numpy import zeros
from copy import deepcopy
from numpy import cast
from numpy import dot
from numpy import linalg

class SSMOperation:

	def localNormalize(self, M):
		M = deepcopy(M)

		maxValue = M.max()

		for rowIdx in range(M.shape[0]):
			M[rowIdx] = map(lambda value: value / maxValue, M[rowIdx])

		return M	

	

	def enhance(self, M, length):
		M = deepcopy(M)
		forwardM = zeros(M.shape)
		backwardM = zeros(M.shape)

		for i in range(M.shape[0]):

			for j in range(M.shape[1]):

				#forwardM[i, j] = (M[slice(i - self.__ENLEN / 2, i + self.__ENLEN / 2), 
							#slice(j - self.__ENLEN / 2, j + self.__ENLEN / 2)].trace()) / self.__ENLEN

				forwardM[i, j] = (M[slice(i, i + length), slice(j, j + length)].trace()) / length

				startI = i - length
				startJ = j - length

				if startI < 0:
					startI = 0

				if startJ <0:
					startJ = 0


				#backwardM[i, j] = (M[slice(startI, i), slice(startJ, j)].trace()) / self.__ENLEN

				



		#M = (forwardM + backwardM) / 2
		return forwardM



	def secondOrder(self, M):
		M = deepcopy(M)

		secondM = zeros(M.shape)

		for i in range(secondM.shape[0]):
			for j in range(secondM.shape[1]):
				rowVec = M[i, :]
				colVec = M[:, j]

				secondM[i, j] = dot(rowVec, colVec) / (linalg.norm(rowVec) * linalg.norm(colVec))


		return secondM
		
		#return dot(M, M)
		#return dot(M, M)
	



	def masker(self, M, threshold):
		M = deepcopy(M)

		for rowIdx in range(M.shape[0]):
			M[rowIdx] = map(lambda similarity: (similarity >= threshold), M[rowIdx])


		return cast['int'](M)
	

		
