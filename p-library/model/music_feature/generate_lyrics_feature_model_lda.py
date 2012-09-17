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
id2word = gensim.corpora.Dictionary.load_from_text('20120917_lyrics_wordids.txt')
#print id2word

# load corpus iterator
mm = gensim.corpora.MmCorpus('20120917_lyrics_tfidf.mm')
#print mm

# extract 20 LDA topics, using 1 pass and updating once every 1 chunk (10,000 documents)
lda = gensim.models.ldamodel.LdaModel(num_topics=30, id2word=id2word)
lda.VAR_MAXITER = 300
lda.VAR_THRESH = 0.0001
lda.update(mm, chunksize=2000, passes=300, update_every=1)

lda.print_topics(30)

corpus_lda = lda[mm]
for doc in corpus_lda: # both bow->tfidf and tfidf->lsi transformations are actually executed here, on the fly
   print doc

index = similarities.MatrixSimilarity(lda[mm])
index.save('20120917_lda.index')

cur.execute("""SELECT ltt.*,ltu.id term_id FROM lyrics_term_tfidf ltt INNER JOIN lyrics_term_unique ltu ON (ltt.term=ltu.term) WHERE song_id=%s""", (song_id))

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