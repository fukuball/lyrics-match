#-*- coding: utf-8 -*-

class PronMapper:

	def __init__(self):
		self.__vowelStressList = None


	def mapping(self, enWord):

		# urllib2 for python 2.x version
		import urllib2
		import time
		quoteTerm = urllib2.quote(enWord)
		url = 'http://www.speech.cs.cmu.edu/cgi-bin/cmudict?stress=-s&in=' + quoteTerm
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
		self.__vowelStressList = self.__contentParser(content)


		import random
		print "PronMapper: One English Word Mapping Finished. %d" % random.randint(1, 9)

		return self.__vowelStressList


	
	def __contentParser(self, content):
		from lxml import html

		root = html.fromstring(content)
		xpath = ".//tt"
		pronTag = root.find(xpath)

		if pronTag.text != None:
			pronList = pronTag.text.split(" ")
			# Vowel Stress Extraction
			vowelStressList = [pron[-1] for pron in pronList if pron[-1].isdigit()]
			#print vowelStressList
			return vowelStressList
		else:
			return  None




if __name__ == "__main__":
	import sys
	reload(sys)
	sys.setdefaultencoding('utf-8')
	term = 'colitas'
	print 'term:' + term

	test = PronMapper().mapping(term)

	print test


