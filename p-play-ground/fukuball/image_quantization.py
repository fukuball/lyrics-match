from scipy.cluster.vq import kmeans, vq
from numpy import array, reshape, zeros
from mltk import image

vqclst = [2, 10, 100, 256]

data = image.read('stallman.png')
(height, width, channel) = data.shape

data = reshape(data, (height*width, channel))
for k in vqclst:
    print 'Generating vq-%d...' % k
    (centroids, distor) = kmeans(data, k)
    (code, distor) = vq(data, centroids)
    print 'distor: %.6f' % distor.sum()
    im_vq = centroids[code, :]
    image.write('result-%d.jpg' % k, reshape(im_vq,
        (height, width, channel)))