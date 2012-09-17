import logging, gensim, bz2
logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)

# load id->word mapping (the dictionary), one of the results of step 2 above
id2word = gensim.corpora.Dictionary.load_from_text('20120917_lyrics_wordids.txt')
print id2word

# load corpus iterator
mm = gensim.corpora.MmCorpus('20120917_lyrics_tfidf.mm')
print mm