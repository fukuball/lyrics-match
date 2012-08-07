"""
kmeans.py, V.2.0, Jun 2 2007, by leonardo maffi.

Related ckmeans.c and ckmeans.so files are optional.

c_version_available is True if available.

The C + ctypes version of the k-means is quite faster than the Psyco version
(tested with n_data = 2000, k = 32, epsilon = 0.001, no perceptual color distance, no verbose.
The Python version chooses the initial centroids distinct):

  Python V.2.5.1:           18.64 s   81   X
  1.5.2 on Python V.2.5.1:   2.1  s    9.1 X
  C + ctypes:                0.23 s    1   X


Possible improvements:
- Add a better choose initial to the C code
"""

import sys
from ctypes import cdll, c_int, c_double, POINTER, cast, addressof

try:
    kmeans_lib = cdll.LoadLibrary('ckmeans.so')
except: # WindowsError
    c_version_available = False
    print "ckmeans.so library not loadad."
else:
    c_version_available = True


def choose_initial(data, k, distfun):
    """Choose k initial *different* centroids randomly.
    This is the basic algorithm, this is faster and less likely to contain bugs.
    distfun is ignored."""
    from random import choice
    # Pick k random points as initial centroids
    # Temporary transform the points to tuples to allow their hashing
    set_c = set( tuple(choice(data)) for _ in xrange(k) )

    # Be sure the initialized centroids are all different
    # This code isn't clean because it's possibile that into data
    #   there aren't k *different* points.
    i = 0
    # keep trying adding more different points
    while len(set_c) < k and i < n and i < 4 * k:
        set_c.add( tuple(data[i]) )
        i += 1
    c = map(list, set_c)
    if len(c) < k:
        # Fill in missing centroids
        c.extend( [c[0]] * (k - len(c)) )

    return c


def choose_initial_plusplus(data, k, distfun=None):
    """Choose k initial *different* centroids randomly using the
    k-means++ euristic by David Arthur and Sergei Vassilvitskii.
    This often gives better clustering results, but it is slower than the
    basic choice of starting points."""
    # See article "k-means++: The Advantages of Careful Seeding" by
    #   David Arthur and Sergei Vassilvitskii.
    # See also: http://theory.stanford.edu/~sergei/kmeans
    from random import choice, randrange, random
    from itertools import izip
    from bisect import bisect

    def weigthed_choice(objects, frequences):
        if len(objects) == 1:
            return 0

        addedFreq = []
        lastSum = 0
        for freq in frequences:
            lastSum += freq
            addedFreq.append(lastSum)

        return bisect(addedFreq, random())

    if distfun is None:
        def distfun(p1, p2):
            return sum( (cp1 - cp2) * (cp1 - cp2) for cp1, cp2 in izip(p1, p2) )

    # choose an intial centroid randomly
    data = data[:]
    pos = randrange(len(data))
    centroids = set([tuple(data[pos])])
    del data[pos] # slow

    ntries = 0
    while len(centroids) < k and ntries < (k * 5):
        min_dists = [min( distfun(c, x) for c in centroids) for x in data]
        tot_dists = float(sum(min_dists))
        probabilities = [min_dists[i] / tot_dists for i, x in enumerate(data)]
        pos = weigthed_choice(data, probabilities) # this can be made faster
        centroids.add(tuple(data[pos]))
        ntries += 1
        del data[pos] # slow

    result = map(list, centroids)
    if len(result) < k:
        # Fill in missing centroids
        result.extend( [result[0]] * (k - len(result)) )

    return result


def kmeans(data, k, t=0.0001, distfun=None, maxiter=50, chooser=choose_initial, verbose=False):
    """kmeans(data, k, t=0.0001, distfun=None, maxiter=50, chooser=choose_initial, verbose=False):
    standard k-means function.

    Input parameters:
      data: list of data points
      k: desired number of clusters
      t: error tolerance (double t). Used as the stopping criterion, i.e. when the sum of
        squared euclidean distance (standard error for k-means) of an iteration is within the
        tolerable range from that of the previous iteration, the clusters are considered
        "stable", and the function returns a suggested value would be 0.0001
      distfunc: a optional function that given two points computes their distance, used by the
        k-means clustering. If not specified it's used the (square of) eucledean distance.
      maxiter: maximum number of iterations, another stopping criterion.
      chooser: function that given data and k return k points chosen as starting cluster
               centroids. By default it's used the choose_initial.
      verbose: True if you want a print of the error on each iteration of the algorithm.

    Output  (labels, c):
      labels: list of what cluster (centroid) each point is assigned to.
      c: list of computed centroids.

    Explanation: this algorithm starts by partitioning the input points into k initial sets,
    either at random or using some heuristic. It then calculates the mean point, or
    centroid, of each set. It constructs a new partition by associating each point with the
    closest centroid. Then the centroids are recalculated for the new clusters, and
    algorithm repeated by alternate application of these two steps until convergence, which
    is obtained when the points no longer switch clusters (or alternatively centroids are no
    longer changed).

    Note this code is almost a toy, it's slow, and probably fragile too (all basic k-means
    algorithms are fragile, there are point distributions that make them go bang).
    When k and/or the number of points is big then using C or ShedSkin can be very useful.
    Note that this code is very C-like because it's designed to work with ShedSkin (and
    because it comes from a C version)."""

    # Adapted from a C version by Roger Zhang, <rogerz@cs.dal.ca>
    # http://cs.smu.ca/~r_zhang/code/kmeans.c
    # It seems that Psyco is useless on this function, ShedSkin makes it about 40+ times faster.

    DOUBLE_MAX = 1.797693e308
    n = len(data)
    m = len(data[0]) # dimension
    assert data and k > 0 and k <= n and m > 0 and t >= 0

    error = DOUBLE_MAX # sum of squared euclidean distance

    counts = [0] * k # size of each cluster
    labels = [0] * n # output cluster label for each data point

    # c1 is an array of len k of the temp centroids
    c1 = []
    for i in xrange(k):
        c1.append([0.0] * m)

    # choose k initial centroids
    c = chooser(data, k, distfun)

    niter = 0
    # main loop
    while True:
        # save error from last step
        old_error = error
        error = 0

        # clear old counts and temp centroids
        for i in xrange(k):
            counts[i] = 0
            for j in xrange(m):
                c1[i][j] = 0

        # Note: a big block of code is duplicated to keep the program fast
        if distfun:
            for h in xrange(n):
                # identify the closest cluster
                min_distance = DOUBLE_MAX
                for i in xrange(k):
                    distance = distfun(data[h], c[i])
                    if distance < min_distance:
                        labels[h] = i
                        min_distance = distance

                # update size and temp centroid of the destination cluster
                for j in xrange(m):
                    c1[labels[h]][j] += data[h][j]
                counts[labels[h]] += 1
                # update standard error
                error += min_distance
        else:
            for h in xrange(n):
                # identify the closest cluster
                min_distance = DOUBLE_MAX
                for i in xrange(k):
                    distance = 0
                    for j in xrange(m):
                        diff = data[h][j] - c[i][j]
                        distance += diff * diff
                    if distance < min_distance:
                        labels[h] = i
                        min_distance = distance

                # update size and temp centroid of the destination cluster
                for j in xrange(m):
                    c1[labels[h]][j] += data[h][j]
                counts[labels[h]] += 1
                # update standard error
                error += min_distance

        for i in xrange(k): # update all centroids
            for j in xrange(m):
                c[i][j] = c1[i][j] / counts[i] if counts[i] else c1[i][j]

        niter += 1
        if verbose: print "%d) Error:" % niter, abs(error - old_error)
        if (abs(error - old_error) < t) or (niter > maxiter):
            break

    return labels, c


def ckmeans(data, k, t=0.0001, maxiter=5000):
    """ckmeans(data, k, t=0.0001, maxiter=5000): C+ctypes version of kmeans().
    See the kmeans() docstring for more info.
    Note: this version doesn't cheek that the initial centroids are all distinct,
      (so it may happen the resulting centroids can be < k)."""
    global kmeans_lib # This is the C library
    n = len(data) # number of points
    m = len(data[0]) # number of dimensions of each point

    TyPoint = c_double * m # type of a point, made of m doubles
    TyPoints = TyPoint * n # type of the point array (it's a 2D matrix of doubles)
    TyPunPoint = POINTER(TyPoint) # type of pointer to a point

    # allocation and initialization of the actual array of points,
    #   it's a contigous block of memory.
    # I've had to use the first * because it seems TyPoints() can't take a
    #   generator as input.
    cdata = TyPoints(*(TyPoint(*p) for p in data))

    # type of the array of pointers to points of the cdata array
    TyPuns = TyPunPoint * n

    # allocation and initialization of the actual array of pointers to points of
    #   the cdata array (so the C k_means function can find the start of each point).
    # I have had to use  cast(p, TyPunPoint)  to find the pointer to the point p
    puns = TyPuns( *(cast(p, TyPunPoint) for p in cdata) )

    # type of the output array of the centroids (they are points)
    TyCentroids = TyPoint * k

    # Allocation of the actual array of the centroids (initialized to 0 by ctypes),
    #   this is an output value.
    centroids = TyCentroids()

    # this is like TyPuns, it's the type of the array of pointers to the centroids
    TyPunsCentroids = TyPunPoint * k

    # allocation and initialization of the actual array of pointers to centroids
    puns_centroids = TyPunsCentroids( *(cast(p, TyPunPoint) for p in centroids) )

    # definition of the input types of the C k_means function
    kmeans_lib.k_means.argtypes = [TyPuns, c_int, c_int, c_int, c_double, c_int, TyPunsCentroids]

    # Type of the array of output labels (they are integers), they are allocated
    #   by the k_means function.
    # The labels is a list of what cluster (centroid) each point is assigned to.
    TyLabels = c_int * n

    # Definition of the output type of the C k_means function
    kmeans_lib.k_means.restype = POINTER(TyLabels)

    # Actual call of the C function, doubles must be converted explicitely,
    #   integers are being converted automatically.
    pr = kmeans_lib.k_means(puns, n, m, k, c_double(t), maxiter, puns_centroids)

    # Conversion (copy) of the labels array to a python list,
    #   (pr.contents creates a new data block at each call).
    labels = list(pr.contents)

    # Conversion (copy) of the centroids array of points to a Python list of list
    pycentroids = map(list, centroids)

    # Linux-Windows compatibility code
    if sys.platform.startswith('win'):
        libc = cdll.msvcrt

    # Call to the free() function of the C lib to free the memory of the array
    #   of labels allocated by the k_means.
    libc.free(addressof(pr.contents))

    # return the labels and centroids
    return labels, pycentroids


try: # Use psyco if available, useful for the Python version
    import psyco
    psyco.full()
except:
    print "psyco not available."


if __name__ == "__main__":
    use_python_version = False

    from random import uniform, choice, gauss
    from itertools import cycle

    print "A small demo:"
    data = [[2.0, 3.0],  [3.0, 1.0],  [4.0, 2.0], [11.0, 5.0],
            [12.0, 4.0], [12.0, 6.0], [7.0, 5.0], [8.0, 4.0],
            [8.0, 6.0]]

    labels, centroids = kmeans(data=data, k=3, maxiter=5,
                               chooser=choose_initial_plusplus, verbose=True)
    print "\nPython version, labels:", labels
    print "Python version, centroids:", centroids

    #labels, centroids = ckmeans(data, k=3, maxiter=5)
    print "\nC version, labels:", labels
    print "C version, centroids:", centroids
    print "\n-----------------------------------------------------\n"

    # Parameters
    k = 6 # Number of clusters
    n_points = 50 # Number of points
    n_coords = 2  # 2D
    lower, upper = -200, 200

    def shortRepr(lst):
        if isinstance(lst[0], list):
            return "[" + "], [".join(shortRepr(line) for line in lst) + "]"
        else:
            return ", ".join(str(round(el, 2)) for el in lst)

    # Create k clouds of random points in the n_coords-dimensional space
    orig_centers = [[uniform(lower, upper) for _ in xrange(n_coords)] for _ in xrange(k)]
    sigmas = [uniform(5, 50) for _ in xrange(k)] # this can change if lower, upper change
    clusters = zip(orig_centers, sigmas)
    points = []
    while len(points) < n_points:
        (cx, cy), sigma = choice(clusters)
        x, y = gauss(cx, sigma), gauss(cy, sigma) # approximated
        if lower < x < upper and lower < y < upper:
            points.append( [x, y] )

    print "Input points:\n", shortRepr(points), "\n"
    print "Original cloud centers:\n", shortRepr(orig_centers), "\n"

    # if the C version isn't available, then it must use the Python version
    if not c_version_available:
        use_python_version = True

    # Cluster the points using the K-means algorithm
    if use_python_version:
        print "Used the Python version of the algorithm:"
        print "k-means total error for each iteration:"
        labels, centroids = kmeans(data=points, k=k, maxiter=20,
                                   chooser=choose_initial_plusplus, verbose=True)
    else:
        print "Used the C+ctypes version of the algorithm:"
        labels, centroids = ckmeans(data=points, k=k, maxiter=20)

    # print the results
    print "\nLabels (what cluser each point is assigned to):\n", labels, "\n"
    print "Cluster centroids:\n", shortRepr(centroids)

    # Plot points and centroids with MatPlotLib, if available
    try:
        # Use MatPlotLib if available
        from pylab import plot, show
    except ImportError:
        pass
    else:
        # Separate input points according to their computed label
        clusters2 = [[] for _ in xrange(k)]
        for i, p in enumerate(points):
            clusters2[labels[i]].append(p)

        # Plot each cluster with a different color, as round points
        colors = cycle("rgbcmyk")
        for group, (cx, cy), color in zip(clusters2, centroids, colors):
            # transpose the points coordinates them as Pylab needs them
            px, py = zip(*group)
            # Plot group points
            plot(px, py, "." + color)
            # Add triangle to represent their centroid
            plot([cx], [cy], "^" + color)

        show()