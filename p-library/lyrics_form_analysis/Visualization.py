# -*- coding:utf-8 -*-
import numpy
import pylab


class Visualization:
	def __init__(self):
		self.__axisMax = 30.0
	
	def drawMergeCurve(self):
		t = numpy.arange(0.0, 5, 0.01)
		maxMerge = t[-1]

		y = numpy.sin(2*numpy.pi*t/(t[-1] * 4))
		y1 = t / t[-1]

		y2 = numpy.sqrt(1 - (t - t[-1])**2 * (1 / t[-1] ** 2))


		y3 = (1 / (1 + numpy.exp(-t))  - 0.5) * 2
		


		pylab.plot(t, y)
		pylab.plot(t, y1)
		pylab.plot(t, y2)
		pylab.plot(t, y3)
		pylab.show()


	def drawGompertz(self, a, b, c):
		t = numpy.arange(-5.0, 10.0, 0.01)
		#y = a*numpy.exp(b*numpy.exp(c*t))
		y1 = a*numpy.exp(b*numpy.exp(c*t))
		y2 = a*numpy.exp(-1*numpy.exp(c*t))

		pylab.plot(t, y1)
		pylab.plot(t, y2)
		pylab.xlabel('time(s)')
		pylab.ylabel('value')
		pylab.title("Gompertz Function a=%.2f  b=%.2f  c=%.2f" % (a, b, c))

		pylab.show()

	def testdrawMatrix(self):
		import numpy
		import pylab

		t = numpy.arange(0.0, 1.0+0.01, 0.01)
		s = numpy.cos(2 * 2 * numpy.pi * t)
		pylab.plot(t, s)

		pylab.xlabel('time(s)')
		pylab.ylabel('voltage (mV)')
		pylab.title('About as simple as it gets')
		pylab.savefig('simple_plot')

		pylab.show()




	def grayMatrix(self, W, title = "SSM"):
		import numpy as N
		import pylab as P
		from copy import deepcopy
		from math import ceil
		from pylab import MultipleLocator
		"""
		Draws a Hinton diagram for visualizing a weight matrix. 
		Temporarily disables matplotlib interactive mode if it is on, 
		otherwise this takes forever.
		"""

		# 將輸入的 Matrix 轉換成 float 型態
		W = N.cast['float'](deepcopy(W))

		reenable = False

		if P.isinteractive():
			P.ioff()
		P.clf()

		height = W.shape[0]
		width = W.shape[1]


		#if not maxValue:
		#	maxValue = 2**N.ceil(N.log(N.max(N.abs(W)))/N.log(2))

		
		P.title(title)
		P.fill(N.array([0,width,width,0]),N.array([0,0,height,height]),'gray')
		P.axis('on')
		#P.axis('equal')
		P.axis('tight')
		P.axis('scaled')
		
		P.xlim(0, width)
		P.ylim(0, height)

		locatorX = MultipleLocator(1)
		locatorY = MultipleLocator(1)

		if width / self.__axisMax > 1:
			locatorX = MultipleLocator(ceil(width / self.__axisMax))


		axis = P.gca()
		axis.xaxis.set_major_locator(locatorX)
		axis.yaxis.set_major_locator(locatorY)



		#borderColor = 'red'
		#borderWidth = 0.2

		for x in xrange(width):
			for y in xrange(height):
				w = W[y][x]
				_x = x + 1
				_y = y + 1

				self.__blob(_x - 0.5, _y - 0.5, 1, str(w), 'black')



		if reenable:
			P.ion()

		P.show()



	def __blob(self, x,y,area,colour, edgeColor):
		import numpy as N
		import pylab as P
		"""
		Draws a square-shaped blob with the given area (< 1) at
		    the given coordinates.
		    """
		hs = N.sqrt(area) / 2
		xcorners = N.array([x - hs, x + hs, x + hs, x - hs])
		ycorners = N.array([y - hs, y - hs, y + hs, y + hs])
		P.fill(xcorners, ycorners, colour, edgecolor=edgeColor)
		

if __name__ == "__main__":
	#Visualization().drawGompertz(1, -10, -0.8)
	Visualization().drawMergeCurve()
	
