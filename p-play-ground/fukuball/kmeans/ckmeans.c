/*
ckmeans.c

Created on 2005-04-12 by Roger Zhang <rogerz@cs.dal.ca>
Modified by maffi leonardo, V.1.0, Jun 2 2007.

A simple k-means clustering routine, it returns the cluster labels
of the data points in an array. Here's an usage example:

   extern int *k_means(double**, int, int, int, double, double**);
   ...
   int *c = k_means(data_points, num_points, dim, 20, 1e-4, 0);
   for (i = 0; i < num_points; i++) {
       printf("data point %d is in cluster %d\n", i, c[i]);
   }
   ...
   free(c);

FUNCTION PARAMETERS:
- double **data: array of data points
- int n: number of data points
- int m: dimension
- int k: desired number of clusters
- double t: error tolerance
   - used as the stopping criterion, i.e. when the sum of
     squared euclidean distance (standard error for k-means)
     of an iteration is within the tolerable range from that
     of the previous iteration, the clusters are considered
     "stable", and the function returns
   - a suggested value would be 0.0001
- int maxiter: max number of iterations
- double **centroids: output address for the final centroids
   - user must make sure the memory is properly allocated, or
     pass the null pointer if not interested in the centroids

FUNCTION OUTPUT:
    int *labels = cluster labels of the data points in an array

NOTES:
- this function is provided as is with no warranty.
- the author is not responsible for any damage caused
   either directly or indirectly by using this function.
- anybody is free to do whatever he/she wants with this
   function as long as this header section is preserved.

COMPILED WITH:
  gcc -shared -O3 -s -o ckmeans.so ckmeans.c

TODO:
  - Avoid duplicate starting centroids.
  - Allow a given distance function too

REFERENCES:
- J. MacQueen, "Some methods for classification and analysis
   of multivariate observations", Fifth Berkeley Symposium on
   Math Statistics and Probability, 281-297, 1967.
- I.S. Dhillon and D.S. Modha, "A data-clustering algorithm
   on distributed memory multiprocessors",
   Large-Scale Parallel Data Mining, 245-260, 1999.
*/

#include <stdlib.h>
#include <assert.h>
#include <float.h>
#include <math.h>

int *k_means(double **data, int n, int m, int k, double t, int maxiter, double **centroids) {
    // output cluster label for each data point
    int *labels = (int*)calloc(n, sizeof(int));

    int h, i, j; // loop counters, of course :)
    int iter = 0; // counter of the iterations of the algorithm
    int *counts = (int*)calloc(k, sizeof(int)); // size of each cluster
    double old_error, error = DBL_MAX; // sum of squared euclidean distance
    double **c = centroids ? centroids : (double**)calloc(k, sizeof(double*));
    double **c1 = (double**)calloc(k, sizeof(double*)); // temp centroids

    assert(data && k > 0 && k <= n && m > 0 && t >= 0); // for debugging

    // initialization
    for (h = i = 0; i < k; h += n / k, i++) {
        c1[i] = (double*)calloc(m, sizeof(double));
        if (!centroids) {
            c[i] = (double*)calloc(m, sizeof(double));
        }
        // pick k points as initial centroids
        // This can pick duplicated centroids if the data are duplicated
        //   if centroids are duplicated at the beginning they keep being
        //   duplicated at the end of the algorithm.
        for (j = m; j-- > 0; c[i][j] = data[h][j]);
    }

    // main loop
    do {
        iter++;
        // save error from last step
        old_error = error, error = 0;

        // clear old counts and temp centroids
        for (i = 0; i < k; counts[i++] = 0) {
            for (j = 0; j < m; c1[i][j++] = 0);
        }

        for (h = 0; h < n; h++) {
            // identify the closest cluster
            double min_distance = DBL_MAX;
            for (i = 0; i < k; i++) {
                double distance = 0;
                //for (j = m; j-- > 0; distance += pow(data[h][j] - c[i][j], 2));
                for (j = m; j-- > 0; ) {
                    double diff = data[h][j] - c[i][j];
                    distance += diff * diff;
                }

                if (distance < min_distance) {
                    labels[h] = i;
                    min_distance = distance;
                }
            }
            // update size and temp centroid of the destination cluster
            for (j = m; j-- > 0; c1[labels[h]][j] += data[h][j]);
            counts[labels[h]]++;
            // update standard error
            error += min_distance;
        }

        for (i = 0; i < k; i++) { // update all centroids
            for (j = 0; j < m; j++) {
                c[i][j] = counts[i] ? c1[i][j] / counts[i] : c1[i][j];
            }
        }

    } while ((fabs(error - old_error) > t) && (iter < maxiter));

    // housekeeping
    for (i = 0; i < k; i++) {
        if (!centroids)
            free(c[i]);
        free(c1[i]);
    }

    if (!centroids)
        free(c);
    free(c1);

    free(counts);

    return labels;
}
