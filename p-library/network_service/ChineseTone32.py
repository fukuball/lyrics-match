#-*- coding: utf-8 -*-

class ToneMapper:
	def __init__(self):
		self.__pinyinDict = {}
		self.__termPinyin = ['']

	def mapping(self, term):
		self.__pinyinDict = {}
		self.__termPinyin = ['']

		# urllib for python 3.x
		from urllib import request
		from urllib import parse

		headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1'}

		#把有中文的 url 轉換成 %xx的格式，才可以用在http的協定上
		quoteTerm = parse.quote_plus(term)
		url = 'http://cdict.net/?q=' + quoteTerm
		req = request.Request(url, None, headers)

		#連線發出請求
		response = request.urlopen(req)
		content = response.read()
		response.close()

		#分析回傳線上辭典的資料
		self.__contentParser(content.decode('utf-8', 'ignore'))

		#利用辭典的資料計算出term正確的拼音
		self.__pinYinComputing(term)
		
		print(self.__pinyinDict)
		print(term, self.__termPinyin)
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
				pinyinStr = pinyinStr[1:-1].strip().split(' ')
				#self.__pinyinDict[word.text.strip()] = pinyinStr
				self.__pinyinDict[word.text.strip()] = pinyinStr


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

ToneMapper().mapping('訴說')
