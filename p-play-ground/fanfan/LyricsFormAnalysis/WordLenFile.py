#-*- coding: utf-8 -*-

class Chinese2POS:

	def __init__(self, savePath):
		self.__savePath = savePath

	
	def process(self, filePath):
		from Tokenizer import Tokenizer
		import codecs


		fileName = filePath[filePath.rindex('/') + 1:]
		tk = Tokenizer()

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


			for i in range(len(sentences)):
				

				tokenList = tk.ckip((sentences[i].strip(' ')))

				posList = []

				for token in tokenList:
					posList.append(len(token["term"]))


				posStr = ''


				if i == 0 and len(sentences) == 1:
					posStr = ','.join(posList)
				elif i == 0:
					posStr = ','.join(posList) + ','
				elif i == len(sentences) - 1:
					posStr = ',' + ','.join(posList)
				else:
					posStr = ',' + ','.join(posList) + ','

				wfp.write(posStr)


			wfp.write('\r\n')


		wfp.close()
		print "Chinese2POS: %s Write Done!!" % fileName




