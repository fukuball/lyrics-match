# -*- coding: utf-8 -*-

class StepType1:

	def __init__(self, weightVec = (2, 1, 1)):
		if len(weightVec) != 3:
			raise "LocalConstraint: Weight Vector Length should be 3 !!"
		else:
			print "LocalConstraint : Step Type 1 Setup"

			# 設定 Local Weight Vector，Weight 數值的順序要與 Step Path 的順序相同
			self.weightVec = weightVec


			# 設定 Step Pattern 步伐的樣式
			# stepPattern 變數是一個三層的 iterator:
			# 	第一層是表示一種 Step Pattern
			# 	第二層是表示一條 Step Path，每條 Path 的順序要與 Weight Vector 相同
			# 	第三層是表示一個 Step 相對於原點的座標位置，每個 Step 的順序是從最遠到最接近原點，每個座標用 tuple 表示
			self.stepPattern = [ [(-1, -1)], 
						[(0, -1)], 
						[(-1, 0)] ]


class StepType2:

	def __init__(self, weightVec = (1, 1, 1)):
		if len(weightVec) != 3:
			raise "LocalConstraint: Weight Vector Length should be 3 !!"
		else:
			print "LocalConstraint : Step Type 2 Setup"

			# 設定 Local Weight Vector，Weight 數值的順序要與 Step Path 的順序相同
			self.weightVec = weightVec


			# 設定 Step Pattern 步伐的樣式
			# stepPattern 變數是一個三層的 iterator:
			# 	第一層是表示一種 Step Pattern
			# 	第二層是表示一條 Step Path，每條 Path 的順序要與 Weight Vector 相同
			# 	第三層是表示一個 Step 相對於原點的座標位置，每個 Step 的順序是從最遠到最接近原點，每個座標用 tuple 表示
			self.stepPattern = [ [(-1, -1)], 
						[(-1, -2), (0, -1)], 
						[(-2, -1), (-1, 0)] ]

class StepType3:

	def __init__(self, weightVec = (1, 1, 1, 1, 1)):
		if len(weightVec) != 5:
			raise "LocalConstraint: Weight Vector Length should be 3 !!"
		else:
			print "LocalConstraint : Step Type 3 Setup"

			# 設定 Local Weight Vector，Weight 數值的順序要與 Step Path 的順序相同
			self.weightVec = weightVec


			# 設定 Step Pattern 步伐的樣式
			# stepPattern 變數是一個三層的 iterator:
			# 	第一層是表示一種 Step Pattern
			# 	第二層是表示一條 Step Path，每條 Path 的順序要與 Weight Vector 相同
			# 	第三層是表示一個 Step 相對於原點的座標位置，每個 Step 的順序是從最遠到最接近原點，每個座標用 tuple 表示
			self.stepPattern = [[(-1, -1)], 
						[(-1, -2), (0, -1)], 
						[(-2, -1), (-1, 0)],
						[(-1, -3), (0, -2), (0, -1)],
						[(-3, -1), (-2, 0), (-1, 0)]]


class PitchToneType:

	def __init__(self, weightVec = (1, 1.5, 2)):
		if len(weightVec) != 3:
			raise "LocalConstraint: Weight Vector Length should be 3 !!"
		else:
			print "LocalConstraint : Pitch Tone Type Setup"

			# 設定 Local Weight Vector，Weight 數值的順序要與 Step Path 的順序相同
			self.weightVec = weightVec


			# 設定 Step Pattern 步伐的樣式
			# stepPattern 變數是一個三層的 iterator:
			# 	第一層是表示一種 Step Pattern
			# 	第二層是表示一條 Step Path，每條 Path 的順序要與 Weight Vector 相同
			# 	第三層是表示一個 Step 相對於原點的座標位置，每個 Step 的順序是從最遠到最接近原點，每個座標用 tuple 表示
			self.stepPattern = [[(-1, -1)], 
						[(-1, -2), (0, -1)], 
						[(-1, -3), (0, -2), (0, -1)]]

