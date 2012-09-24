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

# load id->word mapping (the dictionary), one of the results of step 2 above
id2word = gensim.corpora.Dictionary.load_from_text('20120924_lyrics_wordids_ch.txt')
#print id2word

# load corpus iterator
mm = gensim.corpora.MmCorpus('20120924_lyrics_tfidf_ch.mm')
#print mm

lsi = gensim.models.lsimodel.LsiModel(corpus=mm, id2word=id2word, num_topics=100)
lsi.print_topics(100)
lsi.save('20120924_ch_model.lsi')

corpus_lsi = lsi[mm]
for doc in corpus_lsi:
   print doc

index = similarities.MatrixSimilarity(lsi[mm])
index.save('20120924_ch_lsi.index')

cur.execute("""SELECT ltt.*,ltu.id term_id FROM lyrics_term_tfidf_ch ltt INNER JOIN lyrics_term_unique_ch ltu ON (ltt.term=ltu.term) WHERE song_id=%s""", (song_id))

term_id = ""
tfidf = ""
new_doc_list = list()

for row in cur.fetchall() :
   term_id = row[10]
   tfidf = row[5]
   the_tuple = (int(term_id), float(tfidf))
   new_doc_list.append(the_tuple)

print new_doc_list

print "similarity..."

new_doc_lda = lda[new_doc_list]
sims = index[new_doc_lda]
sims = sorted(enumerate(sims), key=lambda item: -item[1])
print sims # print sorted (document number, similarity score) 2-tuples