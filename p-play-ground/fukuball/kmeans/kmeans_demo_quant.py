"""
K-means demo, V. 1.5, Jun 2 2007, by leonardo maffi, version that saves quantized image,
and it uses the C routine if available.

1) It randomly samples some pixels from the input image, to perform a faster processing.
2) Applies k-means algorithm on the dataset, computing k cluster centers, that are the colors
   of the paletted image.
3) Saves a palette file, that can be read with PaintShopPro.

This program requires Python, PIL and the k_means module.

Images and starting ideas from:
Alessandro Giusti, <giusti@leet.it>, http://www.leet.it/home/lale/clustering/

Note that this code produces results worse than the ones produced by PaintShopPro with the same
palette, I don't know why.
"""

# User parameters:
in_filename = "banner.png"
use_python_version = False
n_samples = 2000 # 1000-20000, slow for n_samples>5000
n_colors = 32 # 2-256
use_perceptualColorDistance = True # # Otherwise it uses eucleadean distance. Useful.
verbose_error = True # True if each iteration of k-means you want to see the error
maxerr = 0.001 # Min error for the k-means iterations, for example 0.00005
maxiter = 20 # n. max of iterations of the k-means algorithm

# --------------------

from random import choice
from time import clock
import Image # From PIL
from kmeans import kmeans, choose_initial_plusplus, ckmeans
from colorDist import perceptualColorDistance01, perceptualColorDistance

def quantize(data, palette_short):
    out_data = []
    for rgb in data:
        dist_min = 1e100
        closest_col = None
        for col_pos, pal_col in enumerate(palette_short):
            """
            # This is for euclidean distance
            dr = rgb[0] - pal_col[0]
            dg = rgb[1] - pal_col[1]
            db = rgb[2] - pal_col[2]
            d = dr*dr + dg*dg + db*db
            """
            d = perceptualColorDistance(rgb, pal_col)
            if d < dist_min:
                dist_min = d
                closest_col = col_pos
        out_data.append(closest_col)
    return out_data

import psyco; psyco.bind(quantize)

assert 257 > n_colors > 1
in_name, suffix = in_filename.rsplit(".", 1)
out_filename = "%s_%d_%d%s.png" % (in_name, n_samples, n_colors,
                                   ["","_percept"][use_perceptualColorDistance])

im = Image.open(in_filename)

# Be sure to have a truecolor image
if im.mode != "RGB": im = im.convert(mode='RGB')

data = im.getdata()
points = [choice(data) for i in xrange(n_samples)]

# The k-means function works with floats
pointsFP = [tuple(comp/255.0 for comp in point) for point in points]

time0 = clock()
if use_python_version:
    if use_perceptualColorDistance:
        clusters, centroids = kmeans(data=pointsFP,
                                     k=n_colors,
                                     t=maxerr,
                                     distfun=perceptualColorDistance01,
                                     maxiter=maxiter,
                                     chooser=choose_initial_plusplus,
                                     verbose=verbose_error)
    else:
        clusters, centroids = kmeans(data=pointsFP,
                                     k=n_colors,
                                     t=maxerr,
                                     maxiter=maxiter,
                                     chooser=choose_initial_plusplus,
                                     verbose=verbose_error)
else:
    clusters, centroids = ckmeans(data=pointsFP, k=n_colors, t=maxerr, maxiter=maxiter)
print "Just the k-means elapsed time:", round(clock() - time0, 2), "s"

# The k-means function works with floats
palette = [tuple(int(round(coord*255)) for coord in point) for point in centroids]

# remove duplicated colors from the palette, theoretically they are all different,
#   but in practice they sometimes aren't...
# Then sort colors by an approximation of luminance
palette = sorted(set(palette), key=sum)

# Copy of palette, to speed up quantization
palette_short = list(palette)

# Add duplicated colors (the last one) to produce a palette of 256 colors
palette.extend( palette[-1] for i in xrange(256 - len(palette)) )

# Create empty paletted output image
im_out = Image.new("P", im.size, 0)

# Flatten the list of colors, for PIL
flattened_palette = [component for color in palette for component in color]

# Put the computed palette in the output image
im_out.putpalette(flattened_palette)

# quantize the input image with the computed palette
out_data = quantize(data, palette_short)

# Put the computed data inside the output image
im_out.putdata(out_data)

# Save computed output image
im_out.save(out_filename)