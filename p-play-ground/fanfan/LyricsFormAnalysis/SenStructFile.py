#-*- coding:utf-8 -*-

class SenStruct:

	def __init__(self, savePath):
		self.__savePath = savePath


	def process(self, filePath):
		import codecs


		fileName = filePath[filePath.rindex('/') + 1:]
		

		ofp = codecs.open(filePath, 'r', 'utf-8')
		lines = ofp.readlines()
		ofp.close()

		if lines[0][0] == unicode(codecs.BOM_UTF8):
			lines[0] = lines[0][1:]
	
		wfp = codecs.open(self.__savePath + fileName, 'w', 'utf-8')


		for line in lines:
			tempLine = line.strip('\r\n')
			tempLine = tempLine.strip(' ')
			sentences = tempLine.split(' ')

			if sentences[0] == '':
				wfp.write('\r\n')
				continue

			senLenList = map(len, sentences)
			senLenList = map(str, senLenList)
			senLenStr = ','.join(senLenList)
			wfp.write(senLenStr + '\r\n')

		wfp.close()


