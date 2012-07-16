#!/usr/bin/python26
# -*- coding:utf-8 -*-

import numpy as np

A = np.floor(np.random.rand(4,4)*20-10) # generating a random
b = np.floor(np.random.rand(4,1)*20-10) # system Ax=b

U,s,V = np.linalg.svd(A) # SVD decomposition of A

print U