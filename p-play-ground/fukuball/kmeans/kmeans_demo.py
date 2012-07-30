"""
K-means demo, V. 1.6, May 6 2007, by leonardo maffi

1) It randomly samples some pixels from the input image, to perform a faster processing.
2) Applies k-means algorithm on the dataset, computing k cluster centers, that are the colors
   of the paletted image.
3) Saves a palette file, that can be read with PaintShopPro.

This program requires Python, PIL and the k_means module.

Images and starting ideas from:
Alessandro Giusti, <giusti@leet.it>, http://www.leet.it/home/lale/clustering/
"""

# User parameters:
in_filename = "banner.png"
n_samples = 5000 # 1000-20000, slow for n_samples>5000
n_colors = 32 # 2-256
use_perceptualColorDistance = True # Otherwise it uses eucleadean distance. Useful.
verbose_error = True # True if each iteration of k-means you want to see the error
minerr = 0.00005 # Min error for the k-means iterations, for example 0.00005

# --------------------

from random import choice
import Image # From PIL
from kmeans import kmeans, choose_initial_plusplus
from colorDist import perceptualColorDistance01

assert 257 > n_colors > 1
in_name, suffix = in_filename.rsplit(".", 1)
out_filename = "%s_%d_%d%s.pal" % (in_name, n_samples, n_colors,
                                   ["","_percept"][use_perceptualColorDistance])

im = Image.open(in_filename)

# Be sure to have a truecolor image
if im.mode != "RGB": im = im.convert(mode='RGB')

data = im.getdata()
points = [choice(data) for i in xrange(n_samples)]

"""
If you want to use the YIQ color you can use this instad of the following line,
but few experiments show that YIQ doesn't give better palettes in this program.
from colorsys import rgb_to_yiq, yiq_to_rgb
pointsFP = []
for r,g,b in points:
    r = max(0.0, min(1.0, r/255.0))
    g = max(0.0, min(1.0, g/255.0))
    b = max(0.0, min(1.0, b/255.0))
    pointsFP.append( rgb_to_yiq(r,g,b) )
"""

# This is for normal RGB colorspace (but the k-means function works with floats)
pointsFP = [(r/255.0, g/255.0, b/255.0) for (r,g,b) in points]

if use_perceptualColorDistance:
    clusters, centroids = kmeans(data=pointsFP,
                                 k=n_colors,
                                 t=minerr,
                                 distfun=perceptualColorDistance01,
                                 maxiter=50,
                                 chooser=choose_initial_plusplus,
                                 verbose=verbose_error)
else:
    clusters, centroids = kmeans(data=pointsFP,
                                 k=n_colors,
                                 t=minerr,
                                 maxiter=50,
                                 chooser=choose_initial_plusplus,
                                 verbose=verbose_error)

"""
If you want to use the YIQ color you can use this instad of the following line.
palette = []
for y,i,q in centroids:
    r,g,b = yiq_to_rgb(y,i,q)
    r = int(round(r*255))
    g = int(round(g*255))
    b = int(round(b*255))
    palette.append( (r,g,b) )
"""

# This is for normal RGB colorspace (but the k-means function works with floats)
palette = [tuple(int(round(coord*255)) for coord in point) for point in centroids]

# remove duplicated colors from the palette, theoretically they are all different,
#   but in practice they sometimes aren't...
# Then sort colors by an approximation of luminance
palette = sorted(set(palette), key=sum)

# Add duplicated colors (the last one) to produce a palette of 256 colors
palette.extend( palette[-1] for i in xrange(256 - len(palette)) )

# Save a palette file, suitable for PaintShopPro
print "Writing:", out_filename
f = file(out_filename, "w")
print >>f, """JASC-PAL
0100
256"""
for (r, g, b) in palette:
    print >>f, r, g, b
f.close()

# Note: maybe a color quantization that uses perceptualColorDistance as distance function can
#   produce better images.