# colorDist.py, V.1.0, Feb 10 2006.

"""
Module to computer an approximation of the perceptual distance between two colors.
Function perceptualColorDistance for byte-represented colors, perceptualColorDistance01 for
float colors in [0, 1].

Note: module colorsys in the Python standard library contains function for color conversions.
"""


def perceptualColorDistance(color1, color2):
    """perceptualColorDistance(color1, color2): return the distance between two given colors,
    computed perceptually. Colors must be sequences of 3 integer values in [0,255]."""
    # Adapted from a C version: http://www.compuphase.com/cmetric.htm
    rmean = (color1[0] - color2[0]) // 2
    dr = color1[0] - color2[0]
    dg = color1[1] - color2[1]
    db = color1[2] - color2[2]
    return (((512+rmean)*dr*dr)>>8) + 4*dg*dg + (((767-rmean)*db*db)>>8)


def perceptualColorDistance01(color1, color2):
    """perceptualColorDistance(color1, color2): return the distance between two given colors,
    computed perceptually. Colors must be sequences of 3 integer values in [0,1]."""
    # Adapted from a C version: http://www.compuphase.com/cmetric.htm
    rmean = (color1[0] - color2[0]) / 2
    dr = color1[0] - color2[0]
    dg = color1[1] - color2[1]
    db = color1[2] - color2[2]
    return ((2+rmean)*dr*dr) + 4*dg*dg + ((3-rmean)*db*db)