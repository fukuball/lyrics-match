# -*- coding: utf-8 -*-


class Tokenizer:

	def __init__(self):

		# 記錄斷詞完的結果，list 中的每個 element 為一個 dictionary
		# 每一個 dictionary 有兩個 key 分別是 term 與 pos
		self.__result = []


		# 設定網路回傳的封包buffer 大小，以byte為單位
		self.bufSize = 4096


		# 設定 CKIP 回傳的段詞資料中所用的分隔符號，目前設定為一個全形的空白
		self.__WSPACE = '　'.decode('utf-8')


		# 設定 tokenizer.ini 中 username 與 password 的 key 對應的多值 value 之間的分隔符號
		self.__INISEP = ','

		# 設定睡眠秒數
		self.__SLEEPSEC = 3


		# 記錄 tokenizer.ini 的設定檔資訊
		# 這是一個 dictionary 資料，有四個 key 分別是 username、password、host 與 port
		self.__config = {}
		self.__configuration()

		#print "Tokenizer: CKIP configuration setup =\n %s" % str(self.__config)



	def ckip(self, sentence):
		"""
		描述：對輸入的句子餵給中研院的 CKIP 斷詞系統做斷詞
		輸入：一個中文句子，此中文句子是個 unicode 的字串
		輸出：一個斷詞的串列，每個詞彙是一個 dictionary ，其中包含 term 與 pos 的 key
		"""

		import socket
		import random
		import time


		connectInfo = (self.__config['host'], int(self.__config['port']))

		# 要傳輸給 ckip service 的 xml 格式
		msgTemplate = '''<?xml version="1.0" ?>
				<wordsegmentation version="0.1">
				<option showcategory="1" />
				<authentication username="{0}" password="{1}" />
				<text>{2}</text>
				</wordsegmentation>'''


		while True:
			try:
				# 亂數選取帳號、密碼
				auth = random.randint(0, len(self.__config['username']) - 1)


				# 將 username , password 以及句子嵌進 xml 格式裡
				msg = msgTemplate.format(self.__config['username'][auth], 
							self.__config['password'][auth], 
							sentence.encode('big5'))


				# 建立 socket 連線
				sp = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
				sp.connect(connectInfo)


				# 傳送 xml 給 CKIP
				# 中研院回傳只支援 Big5或是 UTF-16 的編碼
				# 在這邊選擇用  big5 編碼
				sp.send(msg)


				# 接收 CKIP 的回應資料
				data = sp.recv(self.bufSize) 

				sp.close()


			except socket.error as e:
				# socket 發生的例外處理

				print "Tokenizer: %s, Sleep %s seconds." % (str(e), str(self.__SLEEPSEC))
				sp.close()
				time.sleep(self.__SLEEPSEC)
				continue
			else:

				# CKIP 有時候會回傳空字串，這算是失敗的結果
				if data.strip() == '':
					print "Tokenizer: Response Space String, Sleep %s seconds." % (str(e), str(self.__SLEEPSEC))
					time.sleep(self.__SLEEPSEC)
					continue


				# 剖析 CKIP 回傳的 XML 資訊
				tokenList = self.__dataParse(data.decode('big5'))


				# 確定將一句歌詞斷詞完成
				if tokenList != None:
					self.__result = self.__symbolSeparation(tokenList)

					import random
					print "Tokenizer: One Sentence Tokenizing finished. %d" % random.randint(1, 9)
					break


		return self.__result



	def __configuration(self):
		"""
		描述：剖析 tokenizer.ini 設定檔，並且記錄在 self.__config 變數中
		輸入：無，會自動讀取 tokenzier.ini 設定檔
		輸出：無，會將資訊記錄到 self.__config 變數中
		"""

		import ConfigParser
		cp = ConfigParser.RawConfigParser()

		cp.read('tokenizer.ini')

		# 找出 ini 設定檔中的每個 section
		for section in cp.sections():

			# 找出每個 section 中的 key=value 的 pair
			kvList = cp.items(section)


			# 將每個 key=value 的 pair 記錄到 self.__config
			for pair in kvList:
				self.__config[pair[0]] = pair[1]


		# username 與 password 的 key 是對應到多值的 value，value之間是用逗號隔開
		self.__config['username'] = self.__config['username'].split(self.__INISEP)
		self.__config['password'] = self.__config['password'].split(self.__INISEP)



	def __symbolSeparation(self, tokenList):
		"""
		描述：將 CKIP 回傳的斷詞資訊中的 term 與 pos 分開
		輸入：一個 tokenList，每個 token 是一個字串
		輸出：將 term 與 pos 分開的資訊分別除傳到 result list 回傳
		"""

		result = []

		for token in tokenList:
			startIdx = token.find('(')
			pos = token[startIdx + 1: -1]
			term = token[:startIdx]

			result.append({"term": term, "pos": pos})

		return result




	def __dataParse(self, data):
		"""
		描述：將 CKIP 回傳的 XML 資訊做剖析
		輸入：CKIP 回傳的 XML 資料
		輸出：如果 XML 中的 processstatus 是正確的話，就將斷詞的結果擷取出來，轉成串列並回傳
		      如果 processstatus 不正確的話，將回傳 None
		"""

		from lxml import etree

		root = etree.fromstring(data)		
		status = root.find('processstatus')
		code = status.get('code')

		# 中研院斷詞伺服器會傳成功的 code 為零
		if code == '0': 
			tokenString = root.find('result/sentence').text.strip(self.__WSPACE)
			return tokenString.split(self.__WSPACE)

		# 錯誤的 code 會是 1, 2 或 3
		else:
			print "Tokenizer: CKIP response error code = %s" % status.text
			return None



if __name__ == '__main__':
	t = Tokenizer()

	tokenList =  t.ckip('每一個早晨 在都市的邊緣 我是孤獨的假面'.decode('utf-8'))

	for token in tokenList:
		print "|%s| |%s|" % (token["term"], token["pos"])
