import logging, gensim, bz2
logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)

# load id->word mapping (the dictionary), one of the results of step 2 above
id2word = gensim.corpora.Dictionary.load_from_text('20120917_lyrics_wordids.txt')
print id2word

# load corpus iterator
mm = gensim.corpora.MmCorpus('20120917_lyrics_tfidf.mm')
print mm

# extract 20 LDA topics, using 1 pass and updating once every 1 chunk (10,000 documents)
lda = gensim.models.ldamodel.LdaModel(corpus=mm, id2word=id2word, num_topics=20, update_every=0, chunksize=1000, passes=20)
lda.print_topics(20)