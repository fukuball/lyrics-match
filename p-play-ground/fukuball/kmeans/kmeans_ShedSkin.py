"""
k-means demo for ShedSkin
Version 1.0, Feb 17 2006, by leonardo maffi

This program can be modified to accept t and k from the command line, to accept the points from
stdin, and to print the output to stdout, so it can be used for something useful.
"""

def kmeans(data, k, t=0.0001, verbose=False):
    """K-means function. Parameters:
    data: list of data points
    k = desired number of clusters
    t = error tolerance (double t). Used as the stopping criterion, i.e. when the sum of
      squared euclidean distance (standard error for k-means) of an iteration is within the
      tolerable range from that of the previous iteration, the clusters are considered
      "stable", and the function returns a suggested value would be 0.0001

    Output:
    centroids: output address for the final centroids. user must make sure the memory is properly
    allocated, or leave None if not interested in the centroids.

    Explanation: this algorithm starts by partitioning the input points into k initial sets,
    either at random or using some heuristic. It then calculates the mean point, or
    centroid, of each set. It constructs a new partition by associating each point with the
    closest centroid. Then the centroids are recalculated for the new clusters, and
    algorithm repeated by alternate application of these two steps until convergence, which
    is obtained when the points no longer switch clusters (or alternatively centroids are no
    longer changed)."""
    # adapted from a C version by Roger Zhang, rogerz@cs.dal.ca
    # http://cs.smu.ca/~r_zhang/code/kmeans.c

    DOUBLE_MAX = 1.797693e308
    n = len(data)
    m = len(data[0]) # dimension
    assert data and k > 0 and k <= len(data) and m > 0 and t >= 0

    error = DOUBLE_MAX # sum of squared euclidean distance

    c = [None] * k # centroids
    c1 = [None] * k # temp centroids
    counts = [0] * k # size of each cluster
    labels = [0] * n # output cluster label for each data point

    # initialization
    for i in xrange(k):
        c1[i] = [0.0] * m
        # pick k points as initial centroids
        c[i] = tuple(data[i])

    # Be sure the initialized centroids are all different
    set_c = set(c)
    i = k
    while len(set_c) < k and i < n and i < 5 * k:
        set_c.add( tuple(data[i]) )
        i += 1
    c = [list(p) for p in set_c]
    if len(c) < k:
        # Fill in missing centroids
        c.extend( [c[0]] * (k-len(c)) )

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
                if counts[i]:
                    c[i][j] = c1[i][j] / counts[i]
                else:
                    c[i][j] = c1[i][j]

        if verbose: print "Error:", abs(error - old_error)
        if abs(error - old_error) < t:
            break

    return labels, c

# -------------------------------------------
from random import random, seed
from sys import argv
def test():
    seed(100)
    npoints = int(argv[1])
    k = int(argv[2])
    n = 2

    # Create npoints random Points in n-dimensional space, print them
    points = [[random() for i in xrange(n)] for i in xrange(npoints)]
    print "POINTS:"
    #for p in points: print p
    print

    # Cluster the points using the K-means algorithm, print the results
    print "K-MEANS CLUSTERS:"
    clusters, centroids = kmeans(data=points, k=k)
    #for cluster in clusters: print "Cluster:", cluster
    #print
    print centroids
test()