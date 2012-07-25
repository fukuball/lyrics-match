#-*- coding: utf-8 -*-

import codecs



class Separation:

	def __init__(self):
		pass

dirPath = 'C:/Users/18Fan/Dropbox/研究/程式實作/tempFast/lyrics/'.decode('utf-8') 




	filePath = dirPath + fileName
	fp = codecs.open(filePath, 'r', 'utf-8')
	lines = fp.readlines()
	fp.close()

	if lines[0][0] == unicode(codecs.BOM_UTF8):
		lines[0] = lines[0][1:]
	
	pinyinfp = codecs.open(dirPath + "pinyin_" + fileName, 'w', 'utf-8')
	tonefp = codecs.open(dirPath + "tone_" + fileName, 'w', 'utf-8')


	for line in lines:
		tempLine = line.strip('\r\n')
		tempLine = tempLine.strip(' ')
		pinyinList = tempLine.split(',')

		if pinyinList[0] == '':
			pinyinfp.write('\r\n')
			tonefp.write('\r\n')
			continue
	
		pinyinLine = []
		toneLine = []

		for pinyin in pinyinList:
			if pinyin == '':
				pinyinLine.append(pinyin)
				continue

			pinyinLine.append(pinyin[:-1])
			toneLine.append(pinyin[-1])


		pinyinStr = ','.join(pinyinLine)
		toneStr = ','.join(toneLine)

		pinyinfp.write(pinyinStr + '\r\n')
		tonefp.write(toneStr + '\r\n')
	
	pinyinfp.close()
	tonefp.close()
			




