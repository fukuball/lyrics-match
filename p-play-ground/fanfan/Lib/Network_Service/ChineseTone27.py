#-*- coding: utf-8 -*-

class ToneMapper:
	def __init__(self):
		self.__pinyinDict = {}
		self.__termPinyin = ['']

	def mapping(self, term):
		self.__pinyinDict = {}
		self.__termPinyin = ['']


		# urllib2 for python 2.x version
		import urllib2
		import time
		quoteTerm = urllib2.quote(term)
		url = 'http://cdict.net/?q=' + quoteTerm
		request = urllib2.Request(url)
		request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1')

		response = None

		while response == None:
		
			try:
				response = urllib2.urlopen(request)
				content = response.read()
				response.close()
			except urllib2.HTTPError:
				print "HTTPError, Sleep 3 secs"
				time.sleep(3)




		#分析回傳線上辭典的資料
		self.__contentParser(content)

		#利用辭典的資料計算出term正確的拼音, term的編碼是utf-8
		self.__pinYinComputing(term.decode('utf-8'))

		import random
		print "ToneMapper: One Sentence Mapping Finished. %d" % random.randint(1, 9)
		#print(self.__pinyinDict)
		#print(term.decode('utf-8'), self.__termPinyin)
		return self.__termPinyin


	
	def __contentParser(self, content):
		from lxml import html

		root = html.fromstring(content)
		wordList = root.find_class('cword')


		for word in wordList:
			#取得漢字拼音element
			pinyin = word.getnext() 

			#只選取此漢字注音的低一個拼音
			if self.__pinyinDict.get(word.text.strip()) is None: 
				#漢字拼音的第一個子element是注音拼音
				pinyinStr = list(pinyin)[0].tail.strip()

				#用 slice 去掉中括號 並且用空白切割 轉換成一個 list
				pinyinStrList = pinyinStr[1:-1].strip().split(' ')
				self.__pinyinDict[word.text.strip()] = pinyinStrList



	def __pinYinComputing(self, term):
		#引入 regular expression module
		import re
		self.__termPinyin *= len(term)
		
		#拼音字典裡的 key 由長度最長到最小排序
		sortedKeys = sorted(self.__pinyinDict.keys(), reverse=True, key=lambda term: len(term))

		for key in sortedKeys:
			#用最長的key去全域匹配term中的位置
			matchIter = re.finditer(key, term)
			
			#從拼音字典取出 key 對應的拼音
			pinyinList = self.__pinyinDict[key]

			#可能會出現好幾個match的位置
			for m in matchIter:

				for index in range(m.start(), m.end()):

					#確認這個result slice沒有mapping成注音或是mapping成輕聲的時候，要更換成匹配的注音
					if self.__termPinyin[index] == '' or  '˙' in self.__termPinyin[index]:
						self.__termPinyin[index] = pinyinList[index - m.start()]


if __name__ == "__main__":
	import sys
	reload(sys)
	sys.setdefaultencoding('utf-8')
	term = '一個不需要華麗的地方'
	print 'decode:' + term.decode('utf-8')
	print 'non-decode:' + term

	pinyinList = ToneMapper().mapping(term)

	for pinyin in pinyinList:
		print pinyin


