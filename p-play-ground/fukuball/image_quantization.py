from scipy.cluster.vq import kmeans, vq
from numpy import array, reshape, zeros
import Image

vqclst = [2, 10, 100, 256]

data = Image.read('stallman.png')
(height, width, channel) = data.shape

data = reshape(data, (height*width, channel))
for k in vqclst:
    print 'Generating vq-%d...' % k
    (centroids, distor) = kmeans(data, k)
    (code, distor) = vq(data, centroids)
    print 'distor: %.6f' % distor.sum()
    im_vq = centroids[code, :]
    Image.write('result-%d.jpg' % k, reshape(im_vq,
        (height, width, channel)))