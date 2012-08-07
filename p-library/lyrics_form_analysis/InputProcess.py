# -*- coding: utf-8 -*-
from LyricsInput import LyricsInput

class Pinyin2Tuple(LyricsInput):
	"""
	Transform Pinyin to tuple (INIT, FINAL)
	"""

	def __init__(self, processObj = None):
		self.__processObj = processObj

		self.__initList = ['', 'ㄅ'.decode('utf-8'), 'ㄆ'.decode('utf-8'), 'ㄇ'.decode('utf-8'), 'ㄈ'.decode('utf-8'), 'ㄉ'.decode('utf-8'), 'ㄊ'.decode('utf-8'), 'ㄋ'.decode('utf-8'), 'ㄌ'.decode('utf-8'), 'ㄍ'.decode('utf-8'), 'ㄎ'.decode('utf-8'), 'ㄏ'.decode('utf-8'), 'ㄐ'.decode('utf-8'), 'ㄑ'.decode('utf-8'), 'ㄒ'.decode('utf-8'),'ㄓ'.decode('utf-8'), 'ㄔ'.decode('utf-8'), 'ㄕ'.decode('utf-8'), 'ㄖ'.decode('utf-8'), 'ㄗ'.decode('utf-8') ,'ㄘ'.decode('utf-8') ,'ㄙ'.decode('utf-8')]

		self.__finalList = ['', 'ㄧ'.decode('utf-8'), 'ㄨ'.decode('utf-8'), 'ㄩ'.decode('utf-8'), 'ㄚ'.decode('utf-8'), 'ㄛ'.decode('utf-8'), 'ㄜ'.decode('utf-8'), 'ㄝ'.decode('utf-8'), 'ㄞ'.decode('utf-8'), 'ㄟ'.decode('utf-8'), 'ㄠ'.decode('utf-8'), 'ㄡ'.decode('utf-8'), 'ㄢ'.decode('utf-8'), 'ㄣ'.decode('utf-8'), 'ㄤ'.decode('utf-8'), 'ㄥ'.decode('utf-8'), 'ㄦ'.decode('utf-8'), 'ㄧㄚ'.decode('utf-8'), 'ㄧㄛ'.decode('utf-8'), 'ㄧㄝ'.decode('utf-8'), 'ㄧㄞ'.decode('utf-8'), 'ㄧㄠ'.decode('utf-8'), 'ㄧㄡ'.decode('utf-8'), 'ㄧㄢ'.decode('utf-8'), 'ㄧㄣ'.decode('utf-8'), 'ㄧㄤ'.decode('utf-8'), 'ㄧㄥ'.decode('utf-8'), 'ㄨㄚ'.decode('utf-8'), 'ㄨㄛ'.decode('utf-8'), 'ㄨㄞ'.decode('utf-8'), 'ㄨㄟ'.decode('utf-8'), 'ㄨㄢ'.decode('utf-8'), 'ㄨㄣ'.decode('utf-8'), 'ㄨㄤ'.decode('utf-8'), 'ㄨㄥ'.decode('utf-8'), 'ㄩㄝ'.decode('utf-8'), 'ㄩㄢ'.decode('utf-8'), 'ㄩㄣ'.decode('utf-8'), 'ㄩㄥ'.decode('utf-8')]
	
	
	def process(self, lines):
		print "InputProcess: Pinyin2Tuple"

		self.__lines = lines

		def transform(pinyin):
			"""
			Transform Pinyin Element to 2-tuple (Init Index, Final Index)

			"""

			if pinyin is unicode('', 'utf-8'):
				return ''

			pinyinInit = pinyin[0]

			try:
				initIdx = self.__initList.index(pinyinInit)
			except ValueError as e:
				initIdx = 0
				#補一個空聲母的 utf 字
				pinyin = '空' + pinyin


			finalIdx = self.__finalList.index(pinyin[1:])


			pinyinTuple = (initIdx, finalIdx)

			return pinyinTuple
	
		
		for i in range(len(self.__lines)):
			self.__lines[i] = map(transform, self.__lines[i])


		self.processNext(self.__processObj, self.__lines)
	

class Str2Int(LyricsInput):
	"""
	Transform String to Integer
	"""

	def __init__(self, processObj = None):
		self.__processObj = processObj

	
	def process(self, lines):
		print "InputProcess: Str2Int"

		self.__lines = lines

		for i in range(len(self.__lines)):
			for j in range(len(self.__lines[i])):
				try:
					self.__lines[i][j] = int(self.__lines[i][j])
				except ValueError:
					self.__lines[i][j] = self.__lines[i][j]

				#self.__lines[i] = map(int, self.__lines[i])
		
		self.processNext(self.__processObj, self.__lines)




class Tone2Pitch(LyricsInput):
	"""
	Transform Chinese Tone to Pitch
	"""

	def __init__(self, processObj = None):
		self.__processObj = processObj
	
	def process(self, lines):
		print "InputProcess: Tone2Pitch"

		self.__lines = lines


		# 字調轉旋律的規則
		def transform(tone):
			#pitchList = [0, 4, 2, 1, 3]
			#pitchList = [0, 75, 64, 61, 71]
			pitchList = [None, [75, 75], [64, 69], [64, 61], [71, 65], None]
			return pitchList[tone]

		for i in range(len(self.__lines)):
			self.__lines[i] = map(transform, self.__lines[i])

			if self.__lines[i][0] == None:
				self.__lines[i][0] == [75, 75]

			# 若是清聲則與前一個聲調一樣音高走勢	
			self.__lines[i] = map(lambda (idx, contour): contour or self.__lines[i][idx - 1], enumerate(self.__lines[i]))

			self.__lines[i] = self.__flatten(self.__lines[i])

		self.processNext(self.__processObj, self.__lines)

	def __flatten(self, x):
		"""flatten(sequence) -> list

		Returns a single, flat list which contains all elements retrieved
		from the sequence and all recursively contained sub-sequences
		(iterables).

		Examples:
		>>> [1, 2, [3,4], (5,6)]
		[1, 2, [3, 4], (5, 6)]
		>>> flatten([[[1,2,3], (42,None)], [4,5], [6], 7, MyVector(8,9,10)])
		[1, 2, 3, 42, None, 4, 5, 6, 7, 8, 9, 10]"""

		result = []
		for el in x:
		#if isinstance(el, (list, tuple)):
			if hasattr(el, "__iter__") and not isinstance(el, basestring):
				result.extend(self.__flatten(el))
			else:
				result.append(el)

		return result
