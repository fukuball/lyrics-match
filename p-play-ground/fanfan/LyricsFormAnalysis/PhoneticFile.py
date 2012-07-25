#-*- coding:utf-8 -*-


class Chinese2Phonetic:

	def __init__(self, savePath):
		self.__savePath = savePath
		self.__fourTone = ['', '', 'ˊ'.decode('utf-8'), 'ˇ'.decode('utf-8'), 'ˋ'.decode('utf-8')]
	


	def process(self, filePath):
		from ChineseTone27 import ToneMapper
		import codecs


		fileName = filePath[filePath.rindex('/') + 1:]
		tm = ToneMapper()
		

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
				

				pinyinList = tm.mapping((sentences[i].strip(' ')).encode('utf-8'))


				for j in range(len(pinyinList)):
					if pinyinList[j] != '':
						tone = pinyinList[j][-1]

						if tone in self.__fourTone:
							pinyinList[j] = pinyinList[j][:-1] + str(self.__fourTone.index(tone))
						elif tone == '˙'.decode('utf-8'):
							pinyinList[j] = pinyinList[j][:-1] + str(4)
						else:
							pinyinList[j] = pinyinList[j] + str(1)


				pinyinStr = ''


				if i == 0 and len(sentences) == 1:
					pinyinStr = ','.join(pinyinList)
				elif i == 0:
					pinyinStr = ','.join(pinyinList) + ','
				elif i == len(sentences) - 1:
					pinyinStr = ',' + ','.join(pinyinList)
				else:
					pinyinStr = ',' + ','.join(pinyinList) + ','

				wfp.write(pinyinStr)


			wfp.write('\r\n')


		wfp.close()
		print "Chinese2Phonetic: %s Write Done!!" % fileName




class PhoneticSeparation:
	
	def __init__(self, tonePath, pinyinPath):
		self.__tonePath = tonePath
		self.__pinyinPath = pinyinPath
	

	def process(self, filePath):
		import codecs

		fileName = filePath[filePath.rindex('/') + 1:]

		ofp = codecs.open(filePath, 'r', 'utf-8')
		lines = ofp.readlines()
		ofp.close()

		if lines[0][0] == unicode(codecs.BOM_UTF8):
			lines[0] = lines[0][1:]
	
		tonefp = codecs.open(self.__tonePath + fileName, 'w', 'utf-8')
		pinyinfp = codecs.open(self.__pinyinPath + fileName, 'w', 'utf-8')


		for line in lines:
			tempLine = line.strip('\r\n')
			tempLine = tempLine.strip(' ')

			if tempLine == '':
				tonefp.write('\r\n')
				pinyinfp.write('\r\n')
				continue


			toneList = []
			pinyinList = []
			itemList = tempLine.split(',')

			for item in itemList:
				if item == '':
					pinyinList.append(item)
				else:
					toneList.append(item[-1])
					pinyinList.append(item[:-1])


			toneStr = ','.join(toneList)
			pinyinStr = ','.join(pinyinList)

			tonefp.write(toneStr + '\r\n')
			pinyinfp.write(pinyinStr + '\r\n')


		tonefp.close()
		pinyinfp.close()
	


