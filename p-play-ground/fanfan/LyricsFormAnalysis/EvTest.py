# -*- coding: utf-8 -*-

import ImportPath
ImportPath.Import()

from Evaluation import Evaluation as ev


#truth = [{"blocks": [[1, 3]], "group": "A"}
#		, {"blocks": [[4, 5]], "group": "B"}]


#estimate = [{"blocks": [[1, 2]], "group": "A"}
#		, {"blocks": [[3, 3], [5, 5]], "group": "B"}
#		, {"blocks": [[4, 4]], "group": "C"}]


""""
truth = [{"blocks": [[1, 4]], "group": "A"},
		{"blocks": [[5, 8], [17, 20], [33, 36]], "group": "B"},
		{"blocks": [[9, 16], [21, 28], [37, 44]], "group": "C"},
		{"blocks": [[29, 32]], "group": "D"},
		{"blocks": [[45, 48]], "group": "E"}]
estimate = [{"blocks": [[1, 4], [45, 48]], "group": "A"},
		{"blocks": [[5, 12], [17, 24], [33, 40]], "group": "B"},
		{"blocks": [[13, 16], [25, 28], [41, 44]], "group": "C"},
		{"blocks": [[29, 32]], "group": "D"}]
"""

"""
truth = [{"blocks": [[1, 3],[5, 7]], "group": "A"}
		, {"blocks": [[4, 4], [9, 10]], "group": "B"}
		, {"blocks": [[8, 8]], "group": "C"} ]
	


estimate = [{"blocks": [[1, 2], [5, 6], [9, 10]], "group": "A"}
		, {"blocks": [[3, 4]], "group": "B"}
		, {"blocks": [[7, 8]], "group": "C"}]
"""

"""
truth = [{"blocks": [[1, 2],[5, 6], [9, 10]], "group": "A"}
		, {"blocks": [[3, 4], [7, 8]], "group": "B"} ]

estimate = [{"blocks": [[3, 4], [7, 8]], "group": "A"},
		{"blocks": [[1, 2],[5, 6], [9, 10]], "group": "B"}]
"""

truth = [{"blocks": [[1, 3],[7, 9]], "group": "A"}
		, {"blocks": [[4, 6], [10, 12]], "group": "B"} ]

estimate = [{"blocks": [[1, 1], [3, 3], [5, 5], [7, 7], [9, 9], [11, 11]], "group": "A"},
		{"blocks": [[2, 2],[4, 4], [6, 6], [8, 8], [10, 10], [12, 12]], "group": "B"}]

f = ev().pairwiseFScore(estimate, truth)
print "f score", f

under, over = ev().NCE(estimate, truth, 12)

print "under", under
print "over", over



