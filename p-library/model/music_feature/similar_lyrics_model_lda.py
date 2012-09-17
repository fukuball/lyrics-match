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

index = similarities.MatrixSimilarity.load('20120917_lda.index')

cur.execute("""SELECT ltt.*,ltu.id term_id FROM lyrics_term_tfidf ltt INNER JOIN lyrics_term_unique ltu ON (ltt.term=ltu.term) WHERE song_id=%s""", (song_id))

term_id = ""
tfidf = ""
new_doc_list = list()

for row in cur.fetchall() :
   term_id = row[10]
   tfidf = row[5]
   the_tuple = (int(term_id), float(tfidf))
   new_doc_list.append(the_tuple)

new_doc_lda = lda[new_doc_list]
sims = index[new_doc_lda]
sims = sorted(enumerate(sims), key=lambda item: -item[1])

similar_lyrics_string = ""
for doc in sims:
   similar_lyrics_string += str(doc[0])+":"+str(doc[1])+","

similar_lyrics_string = similar_lyrics_string[:-1]
print similar_lyrics_string