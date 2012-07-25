#-*- coding: utf-8 -*-

import codecs
import os
import sys

# 加入自己的模組路徑
import ImportPath
ImportPath.Import()

# import 自己的模組
from LyricsInput import FromFile
from PhoneticFile import Chinese2Phonetic
from PhoneticFile import PhoneticSeparation
from POSFile import Chinese2POS
from SenStructFile import SenStruct
from Evaluation import Evaluation

reload(sys)
sys.setdefaultencoding('utf-8')




toneDirPath = '/var/www/html/lyrics-match/p-play-ground/fanfan/LyricsSample/聲調/'.decode('utf-8')
pinyinDirPath = '/var/www/html/lyircs-match/p-play-ground/fanfan/LyricsSample/注音/'.decode('utf-8')
lyricsDirPath = '/var/www/html/lyircs-match/p-play-ground/fanfan/LyricsSample/原詞/'.decode('utf-8')
tempDirPath = '/var/www/html/lyrics-match/p-play-ground/fanfan/LyricsSample/注音聲調暫存/'.decode('utf-8')
posDirPath = '/var/www/html/lyrics-match/p-play-ground/fanfan/LyricsSample/詞性/'.decode('utf-8')
senStructDirPath = '/home/fanfan/www/html/lyrcs-match/p-play-ground/fanfan/LyricsSample/句結構分佈/'.decode('utf-8')





def rankingNumber(formList, groundTruth):

	for rank in range(len(formList)):

		print "Run: #%d = %.4f,%s" % (rank + 1, formList[rank]["score"], str(formList[rank]["form"]))

		if formList[rank]["form"] == groundTruth:
			print "Run: Ground Truth Ranking # = %d" % (rank + 1)
			return


	print "Run: Ground Truth not in List"


def eva(formList, groundTruth):
	ev = Evaluation()

	for rank in range(len(formList)):
		fscore = ev.pairwiseFScore(formList[rank]["form"], groundTruth)
		print "Run: #%d = %.4f" % (rank + 1, fscore)
		#print formList[rank]["form"]





def run(fileName, groundTruth):

	if not os.path.exists(tempDirPath + fileName):
		c2p = Chinese2Phonetic(tempDirPath)
		c2p.process(lyricsDirPath + fileName)
	else:
		print "%s is in Temp Directory." % fileName



	if not os.path.exists(toneDirPath + fileName) or not os.path.exists(pinyinDirPath + fileName):
		ps = PhoneticSeparation(toneDirPath, pinyinDirPath)
		ps.process(tempDirPath + fileName)
	else:
		print "%s is in Tone and Pinyin Directory." % fileName



	#if not os.path.exists(posDirPath + fileName) or not os.path.exists(lenDirPath + fileName):
	if not os.path.exists(posDirPath + fileName):
		pos = Chinese2POS(posDirPath, lenDirPath)
		pos.process(lyricsDirPath + fileName)
	else:
		print "%s is in POS Directory." % fileName



	if not os.path.exists(senStructDirPath + fileName):
		sen = SenStruct(senStructDirPath)
		sen.process(lyricsDirPath + fileName)
	else:
		print "%s is in Sentence Structure Directory." % fileName



	from LyricsForm import LyricsForm
	from InputProcess import Pinyin2Tuple
	from DistEuclidean import DistEuclidean
	from SimPinyin import SimPinyin
	from SimPOS import SimPOS
	from AlgoStructAlign import AlgoStructAlign
	from AlgoDistDTW import AlgoDistDTW
	from AlgoDistDDTW import AlgoDistDDTW
	from DistSenStruct import DistSenStruct
	from DistPitch import DistPitch
	from InputProcess import Str2Int
	from InputProcess import Tone2Pitch
	from LocalConstraint import StepType2
	from LocalConstraint import StepType3
	from LocalConstraint import StepType1


	LF = LyricsForm()


	"""
	句子字數分佈
	"""
	print
	print "Main: Sentence Structure for %s" % fileName

	fileProcess = Str2Int()
	lines = FromFile(fileProcess).process(senStructDirPath + fileName)


	"""
	SSM相似度計算用Alignment的方法
	"""
	simObject = DistEuclidean()
	simObject = AlgoStructAlign(simObject, 3.0)
	#simObject = DistSenStruct()
	senSSM = LF.SSMGen(lines, simObject, matrixType = 'dist')
	formList = LF.formAnalysis(senSSM, False)
	#rankingNumber(formList, groundTruth)
	eva(formList, groundTruth)



	"""
	注音SSM
	"""
	print
	print "Main: Pinyin for %s" % fileName

	fileProcess = Pinyin2Tuple()
	lines = FromFile(fileProcess).process(pinyinDirPath + fileName)

	simObject = SimPinyin()
	simObject = AlgoStructAlign(simObject, -1.0, isRhyme = True)
	pinyinSSM = LF.SSMGen(lines, simObject)
	formList = LF.formAnalysis(pinyinSSM)
	#rankingNumber(formList, groundTruth)
	eva(formList, groundTruth)

	
	"""
	聲調SSM
	"""
	print
	print "Main: Tone for %s" % fileName

	fileProcess = Tone2Pitch()
	fileProcess = Str2Int(fileProcess)
	lines = FromFile(fileProcess).process(toneDirPath + fileName)


	# DTW 
	simObject = DistEuclidean()
	simObject = AlgoDistDTW(simObject, stepType = StepType2())
	toneSSM = LF.SSMGen(lines, simObject, 'dist')
	formList = LF.formAnalysis(toneSSM)
	#rankingNumber(formList, groundTruth)
	eva(formList, groundTruth)


	"""
	# Derivative DTW
	simObject = DistEuclidean()
	simObject = AlgoDistDTW(simObject, stepType = StepType2())
	simObject = AlgoDistDDTW(simObject)
	LyricsForm(simObject).formAnalysis(lines)
	"""


	"""
	詞性 SSM
	"""
	print
	print "Main: POS for %s" % fileName
	

	lines = FromFile().process(posDirPath + fileName)
	simObject = SimPOS()
	simObject = AlgoStructAlign(simObject, -1.0)
	posSSM = LF.SSMGen(lines, simObject)
	formList = LF.formAnalysis(posSSM)
	#rankingNumber(formList, groundTruth)
	eva(formList, groundTruth)


	"""
	線性組合 SSM
	"""
	print 
	print "Main: Linear Combination SSM"
	a = 0.25
	b = 0.25
	c = 0.25
	d = 1 - (a + b + c)

	hybridSSM = a * senSSM +  b * pinyinSSM +  c * toneSSM + d * posSSM
	formList = LF.formAnalysis(hybridSSM)
	#rankingNumber(formList, groundTruth)
	eva(formList, groundTruth)







#run('1986_掌聲響起.txt'.decode('utf-8'), [[[1, 4], [7, 10]], [[5, 6], [11, 12], [13, 14]]])
#run('1992_瀟灑走一回_win.txt'.decode('utf-8'), [[[1, 2], [7, 8]], [[3, 6], [9, 12], [13, 16]]])
#run('1991_哭砂.txt'.decode('utf-8'), [[[1, 3], [4, 6], [11, 13]], [[7, 10], [14,17]]])
#run('1990_我很醜可是我很溫柔.txt'.decode('utf-8'), [[[1, 4], [9, 12]], [[5, 8], [13, 16], [17, 20]]])
#run('1993_吻別_win.txt'.decode('utf-8'), [[[1, 8], [11, 18]], [[9, 10], [19, 20], [21, 22]]])
run('1990_這城市有愛_win.txt'.decode('utf-8'), [ {"group": [[1, 7], [8, 14], [23, 29]], "label": "verse1"}, 
						{"group": [[15, 22], [30, 37]], "label": "chorus"}, 
						{"group": [[38, 38]], "label": "outro"} ])
#run('2001_張學友_我真的受傷了.txt'.decode('utf-8'), [[[3, 6], [11, 14], [19, 22]], [[7, 10], [15, 18]]])

#run('突然好想你.txt'.decode('utf-8'), [[[1, 2], [3, 4], [7, 8], [13, 14]], [[5, 6], [11, 12]]])

#run('1994_祝福_win.txt'.decode('utf-8'))
#run('1996_領悟.txt'.decode('utf-8'), [[[1, 14], [23, 31]], [[15, 22], [32, 38], [39, 46]]])
#run('1997_許茹芸_破曉.txt'.decode('utf-8'))
#run('1996_姊妹.txt'.decode('utf-8'))
#run('1990_我想有個家_win.txt'.decode('utf-8'))
#run('1994_用心良苦.txt'.decode('utf-8'), [])
#run('1998_要知道你的感覺.txt'.decode('utf-8'))
#run('為什麼.txt'.decode('utf-8'))

