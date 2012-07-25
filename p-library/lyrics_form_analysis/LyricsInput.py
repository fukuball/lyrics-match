# -*- coding: utf-8 -*-
from abc import ABCMeta, abstractmethod

class LyricsInput:
	__metaclass__ = ABCMeta

	@abstractmethod
	def process(self, lines):
		pass

	def processNext(self, processObj, lines):
		if processObj is not None:
			processObj.process(lines)


class FromFile(LyricsInput):

	def __init__(self, processObj = None):
		self.__WINDOWSRET = '\r\n'
		self.__processObj = processObj
		pass
	

	def process(self, path):
		self.__lines = []

		import codecs

		fp = codecs.open(path.decode('utf-8'), 'r', 'utf-8')
		lines = fp.readlines()
		fp.close()

		#去除 utf-8 file 裡的 byte order mark(BOM)
		if lines[0][0] == unicode(codecs.BOM_UTF8, 'utf8'):
			#print 'FromFile: Strip UTF-8 BOM'
			lines[0] = lines[0][1:]


		#去除換行符號
		for i in range(len(lines)):
			lines[i] = lines[i].strip(self.__WINDOWSRET)

			#記錄有內容的行
			if lines[i].strip(' ') != '':
				self.__lines.append(lines[i].split(','))
				#self.__lines.append(lines[i])

				
		self.processNext(self.__processObj, self.__lines)

		#print "FromFile: The first line after all process is"
		#print self.__lines[0]
		return self.__lines




class FromDB(LyricsInput):

	def process(self):
		pass
