import logging, gensim, bz2
logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)
from gensim import corpora, models, similarities
import sys
import numpy as np
import MySQLdb as mysql
import json
sys.path.append("/var/www/html/lyrics-match/p-library/model")
import ImportPath
ImportPath.Import()

import db_stage
CONST = db_stage._Const()

# connect to db
db = mysql.connect(host    = CONST.DBHOST,
                   user    = CONST.DBUSER,
                   passwd  = CONST.DBPASS,
                   db      = CONST.DBNAME,
                   charset = 'UTF8')

cur = db.cursor()
cur.execute("SET NAMES UTF8")
cur.execute("SET CHARACTER_SET_CLIENT=UTF8")
cur.execute("SET CHARACTER_SET_RESULTS=UTF8")
db.commit()

song_id = sys.argv[1];
model = sys.argv[2];

if model=='tf_lda' :
   mm = gensim.corpora.MmCorpus('/var/www/html/lyrics-match/p-library/model/music_feature/20120917_lyrics_tf.mm')
   lda = models.LsiModel.load('/var/www/html/lyrics-match/p-library/model/music_feature/20120917_tf_model.lda')
   index = similarities.MatrixSimilarity.load('/var/www/html/lyrics-match/p-library/model/music_feature/20120917_tf_lda.index')
else :
   mm = gensim.corpora.MmCorpus('/var/www/html/lyrics-match/p-library/model/music_feature/20120917_lyrics_tfidf.mm')
   lda = models.LsiModel.load('/var/www/html/lyrics-match/p-library/model/music_feature/20120917_model.lda')
   index = similarities.MatrixSimilarity.load('/var/www/html/lyrics-match/p-library/model/music_feature/20120917_lda.index')

lda.print_topics(20)
corpus_lda = lda[mm]
corpus_lda[0]