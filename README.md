# 「再三推詞」以曲找詞歌詞推薦系統網頁

華語流行音樂中，歌詞與歌曲都扮演重要的角色。台灣近代流行音樂的發展，校園民歌扮演重要的角色。而校園民歌的濫觴就是源自於楊弦以詩入歌，以余光中的詩作「鄉愁四韻」為詞而譜曲。後來，李雙澤高呼「唱自己的歌」後，開啟台灣校園民歌的熱潮。在這段時間，不少民歌的歌詞是採用知名作家的詩作，如三毛、余光中、席慕容 、蔣勳等人的作品。

目前也有很多華語流行音樂是翻唱自其他國家的歌曲，一般的作法都是保留原曲的主旋律，再填上中文的歌詞。例如《古老的大鐘》被歌手李聖傑翻唱，一般誤以為《古老的大鐘》源自於日本民謠，其實原作者是美國人 Henry Clay Work 於 1876 年作曲，在 1940 年代隨著戰爭的結束，傳入日本，經過翻譯成為了日本的民謠。因此，一首百年歌曲透過新的翻唱，填上新的歌詞，就可注入新的生命。

又如庾澄慶唱的「情非得已」與蘇永康唱的「幸福離我們很近」，都是由湯小康作曲的相同旋律，但歌詞卻不相同。又如鄧麗君唱的國語歌曲「誰來愛我」與李翊君唱的台語歌曲「苦海女神龍」也是相同的旋律，不同的歌詞。但是詞曲的搭配是門學問。影響歌詞的優劣的因素包括歌詞與歌曲的互相搭配、文字技巧、歌詞的情感深度等。

因此，本系統「再三推詞」是一個以曲找詞的舊曲新詞推薦系統，本系統提供使用者以輸入的歌曲，查詢適合搭配的歌詞。系統將根據詞曲搭配性，推薦適合搭配的歌詞，並以歌聲模擬軟體試唱。對華語流行音樂來說，提供舊曲新詞的音樂加值應用。對唱歌的愛好者來說，可以有老歌新唱的趣味性。

## System Functions

本系統提供使用者上傳音樂來取得適合搭配此首音樂的推薦歌詞清單。在推薦的歌詞結果中，系統會顯示歌曲音符與推薦歌詞的對應，因此使用者可以很容易的將推薦歌詞與上傳的歌曲配唱，同時，我們也將歌詞推薦結果用「虛擬歌手」配唱給使用者聆聽，讓使用者可以更深入的了解歌詞推薦結果在實際配唱起來的感覺。本系統提供的功能簡介如下：

(1)  歌曲結構分析
我們在寫作時，會將文章依據主題分段，每段又包含多個字句。當讀者在閱讀文章時，就可以依據段落來閱讀。相較於文章，音樂資料亦具有結構性。樂句就如同文章中的字句，多個樂句則堆疊成另一個結構「曲式」。曲式是指一首音樂的結構，對樂曲的整體面貌起著決定性的作用。
流行音樂歌曲的曲式與歌詞的詞式、歌曲的樂句與歌詞的字句之間，都具備搭配的對應關係。流行音樂的曲式，由主歌(Verse)、副歌(Chorus)、前奏、尾奏、間奏或過門所組成。常見的曲式諸如[前奏]→[主歌]→[副歌]→[間奏]→[主歌]→[副歌]→[尾奏]或[前奏]→[主歌]→[副歌]→[間奏]→[主歌]→[副歌]→[副歌]→[尾奏]等。曲式的每段又由多個樂句所組成。每個樂句則由多個連續的音符所組成。
本系統的歌曲結構分析採Bottom-Up的方式，首先利用音軌與音程的關係，擷取出主旋律。接著由主旋律中，利用Data Mining的Support Vector Machine學習樂句的分段點，以找出樂句。根據樂句兩兩之間特徵相似度，建立Self-Similarity Matrix。最後利用Non-Negative Matrix Factorization由Self-Similarity Matrix找出曲式的分段點，進而得出曲式。
(2)  歌詞結構分析
歌詞也有歌詞的結構。系統首先將歌詞斷詞，接著根據字數結構、拼音結構、詞性結構、聲調音高等特徵，建立歌詞倆倆詞行之間的Self-Similarity Matrix。接著利用Dynamic Programming找出詞式結構。
(3)  詞曲結構對應
分析出歌曲結構與歌詞結構後，系統採Bottom-Up的方式，依序做詞曲的搭配。首先，系統先將歌詞中的字句結構與歌曲的樂句結構做第一層次的最佳化對應(Alignment)。接下來系統根據歌詞聲韻與歌曲旋律的搭配、歌詞唱音節奏與歌曲旋律節奏的搭配，以Alignment演算法進行詞式與曲式的搭配，計算出詞曲的搭配分數，再根據搭配分數排名產生歌詞推薦清單。同時也產生歌詞中每個單字與歌曲中每個音符的對應關係，以供歌聲模擬軟體模擬試唱。
(4)  歌詞推薦
使用者要對一首歌曲配詞時，可以將音樂midi上傳到本系統，系統分析歌曲的音樂結構後，會比對現有的流行音樂歌詞，並分析計算與上傳音樂的結構符合的流行音樂歌詞，最後將比對結果最佳的數首歌詞推薦給使用者。
(5)  虛擬歌手試唱
系統針對使用者的查詢所回傳的結果，除了依照推薦分數排名的歌詞(或歌曲)之外，還提供虛擬歌手模擬人聲試唱，讓使用者試聽系統推薦的詞曲之效果。我們利用YAMAHA所開發的語音合成軟體VOCALOID。輸入旋律(MIDI)與歌詞，VOCALOID可以合成人聲的歌聲。其中，「初音未來」是目前非常熱門的虛擬歌手。本系統將推薦的歌詞與詞曲結構對應的結果，輸出成「初音未來」合成軟體支援的格式，將配唱結果由「初音未來」演唱，讓使用者可以聆聽各推薦歌詞實際配唱時帶來的感覺，將推薦結果用更直覺的方式呈現給使用者。

# 華語流行音樂之詞式分析與詞曲結構搭配之排比與同步 作者：范斯越

![ScreenShot](https://raw.github.com/fukuball/cfa/master/p-asset/image/cfa-head-photo.png)

## 摘要

目前大部分的聽眾主要是透過歌詞與樂曲的搭配來了解音樂所要表達的內容，因此歌詞創作在目前的音樂工業是很重要的一環。一般流行音樂創作是由作曲人與作詞人共同完成，然而有另一種方式是將既有的詩詞做為歌詞，接著重新譜曲的方式產生新的流行音樂。這種創作方式是讓舊有的詞或曲注入新的生命力，得以流傳到現在。因此本研究希望可以為一首旋律推薦適合配唱的歌詞，以對數位音樂達到舊曲新詞的加值應用。本論文包括兩個部分，分別為：(1)自動分析歌詞的詞式，找出每個段落的位置與其段落的標籤；(2)詞曲結構搭配，找出相符合結構的詞與曲，並且同步每個漢字與音符。

本論文的第一部分為詞式分析，首先將歌詞擷取四個面向的特徵值，分別為(1)句字數結構；(2)拼音結構；(3)詞性；(4)聲調音高。第二步驟，利用這四種特徵值分別建立詞行的自相似度矩陣(Self Similarity Matrix)，並且利用這四個特徵的自相似度矩陣產生一個線性組合自相似度矩陣。第三步驟，建立在自相似度矩陣上我們做段落分群以及家族(Family)組合找出最佳的分段方式，最後將找出的分段方式利用我們整理出來的規則讓電腦自動標記段落標籤。第二部分為詞曲結構搭配，首先我們將主旋律的樂句以及歌詞的詞句做第一層粗略的對應，第二步驟，將對應好的樂句與詞句做第二層漢字與音符細部的對應，最後整合兩層對應的成本當做詞曲搭配的分數。

我們以KKBOX音樂網站當做歌詞來源，並且請專家標記華語流行歌詞資料庫的詞式。實驗顯示詞式分析的Pairwise f-score準確率達到0.83，標籤回復準確率達到0.78。詞曲結構搭配中，查詢的歌曲其原本搭配的歌詞，推薦排名皆為第一名。

## Abstract

Nowadays, lots of pop music audiences understand the content of music via lyrics and melody collocation. In general, a Chinese pop music is produced by composer and lyricist cooperatively. However, another producing manner is composing new melody with ancient poetry. Therefore, we want to recommend present lyrics for a melody and then achieving value-added application for digital music. This thesis includes two subjects. The first subject is lyrics form analysis. This subject is finding the block of verse, chorus, etc., in lyrics. The second subject is structure alignment between lyrics and melody. We utilize the result of lyrics form analysis and then employ a 2-tier alignment to recommend present lyrics which is suitable for singing.

In lyrics form analysis, the first step, we investigate four types of feature from lyrics: (1) Word Count Structure; (2) Pinyin Structure; (3) Part of Speech Structure; (4) Word Tone Pitch. For the second step, we utilize these four types of feature to construct a SSM(Self Similarity Matrix), and blend these four types of SSM to produce a linear combination SSM. The third step is clustering blocks and finding the best Family combination based on SSM. Finally, a rule-based technique is employed to label blocks of lyrics. For the second subject, the first step is aligning music phrases and lyrics sentences roughly. The second step is aligning a word and a note for corresponding phrase and sentence. Finally, we integrated the cost of two-level alignment regarded as the lyrics and melody collocation score.

We collect lyrics from KKBOX, a music web site, and invite experts label ground truth of lyrics form. The experimental result of lyrics form analysis shows that the proposed method achieves the Pairwise f-score of 0.83, and the Label Recovering Ratio of 0.78. The experiment of structure alignment between lyrics and melody shows that the original lyrics of query melodies are ranked number one.

## Reference

01. F. Bronson, The Billboard Book of Number One Hits, Billboard Books, 1997.
02. M. Cooper, and J. Foote, “Summarizing Popular Music via Structural Similarity Analysis,” Proc. of IEEE Workshop on Applications of Signal Processing to Audio and Acoustics, 2003.
03. J. Foote, “Visualizing Music and Audio Using Self-Similarity,” Proc. of ACM International Conference on Multimedia, 1999.
04. J. Foote, “Automatic Audio Segmentation Using a Measure of Audio Novelty,”. Proc. of IEEE International Conference on Multimedia and Expo, 2001.
05. H. Fujihara, M. Goto, J. Ogata, K. Komatani, T. Ogata, and H. G. Okuno, “Automatic Synchronization between Lyrics and Music CD Recordings Based on Viterbi Alignment of Segregated Vocal Signals,” Proc. of IEEE International Symposium on Multimedia, 2006.
06. S. Fukayama, K. Nakatsuma, S. S. Nagoya, Y. Yonebayashi, T. H. Kim, S. W. Qin, T. Nakano, T. Nishimoto, and S. Sagayama, “Orpheus: Automatic Composition System Considering Prosody of Japanese Lyrics,” Proc. of International Conference on Entertainment Computing, 2009.
07. S. Fukayama, K. Nakatsuma, S. Sako, T. Nishimoto, and S. Sagayama, “Automatic Song Composition from the Lyrics Exploiting Prosody of Japanese Language,” Proc. of Conference on Sound and Music Computing, 2010.
08. D. Iskandar, Y. Wang, M. Y. Kan, and H. Li, “Syllabic Level Automatic Synchronization of Music Signals and Text Lyrics,” Proc. of ACM International Conference on Multimedia, 2006.
09. M. Y. Kan, Y. Wang, D. Iskandar, T. L. Nwe, and A. Shenoy, ”LyricAlly: Automatic Synchronization of Textual Lyrics to Acoustic Music Signals,” IEEE Transactions on Audio, Speech and Language Processing, Vol. 16, No. 2, 2008.
10. T. Kitahara, S. Fukayama, S. Sagayama, H. Katayose, and N. Nagata, “An Interactive Music Composition System based on Autonomous Maintenance of Musical Consistency,” Proc. of Conference on Sound and Music Computing, 2011.
11. K. Lee, and M. Cremer, “Segmentation-based Lyrics-Audio Alignment Using Dynamic Programming,” Proc. of International Conference on Music Information Retrieval, 2008.
12. M. Levy, and M. Sandler, “Structural Segmentation of Musical Audio by Constrained Clustering,” IEEE Transactions on Audio, Speech, and Language Processing, Vol. 16, No. 2, 2008.
13. H. Lukashevich, “Towards Quantitative Measures of Evaluating Song Segmentation,” Proc. of International Society for Music Information Retrieval, 2008.
14. N. C. Maddage, and K. C. Sim, “Word Level Automatic Alignment of Music and Lyrics Using Vocal Synthesis,” ACM Transactions on Multimedia Computing, Communications and Applications, Vol. 6, No. 3, 2010.
15. M. Mauch, H. Fujihara, and M. Goto, “Lyrics-to-Audio Alignment and Phrase-level Segmentation Using Incomplete Internet-style Chord Annotations,” Proc. of Conference on Sound and Music Computing, 2010.
16. A. Mesaros, and T. Virtanen, “Automatic Alignment of Music Audio and Lyrics,” Proc. of International Conference on Digital Audio Effects, 2008.
17. M. Mueller, P. Grosche, and N. Jianq, “A Segment-Based Fitness Measure for Capturing Repetitive Structures of Music Recordings,” Proc. of International Society for Music Information Retrieval, 2011.
18. M. Mueller, and F. Kurth, “Towards Structural Analysis of Audio Recordings in the Presence of Musical Variations,” EURASIP Journal on Advances in Signal Processing, 2007.
19. M. Mueller, and F. Kurth, “Enhancing Similarity Matrices for Music Audio Analysis,” Acoustics, Speech and Signal Processing, 2006.
20. E. Nichols, D. Morris, S. Basu, and C. Raphael, “Relationships between Lyrics and Melody in Popular Music,” Proc. of International Society for Music Information Retrieval, 2009.
21. H. R. G. Oliveira, F. A. Cardoso, and F. C. Pereira, “Tra-la-Lyrics: An Approach to Generate Text Based on Rhythm,” Proc. of International Joint Workshop on Computational Creativity, 2007.
22. J. Paulus, and A. Klapuri, “Music Structure Analysis using a Probabilistic Fitness Measure and a Greedy Search Algorithm,” IEEE Transactions on Audio, Speech, and Language Processing, Vol. 17, No. 6, 2009.
23. J. Paulus, M. Muller, and A. Klapuri, “Audio-Based Music Structure Analysis,” Proc. of International Society for Music Information Retrieval, 2010.
24. G. Peeters, “Sequence Representation of Music Structure Using Higher-order Similarity Matrix and Maximum-likelihood Approach,” Proc. of International Society for Music Information Retrieval, 2007.
25. S. Qin, S. Fukayama, T. Nishimoto, and S. Sagayama, “Lexical Tones Learning with Automatic Music Composition System Considering Prosody of Mandarin Chinese,” Proc. of Second Language Studies: Acquisition, Learning, Education and Technology, 2010.
26. A. Ramakrishnan A, and S. L. Devi, “An Alternate Approach Towards Meaningful Lyric Generation in Tamil,” Proc. of NAACL HLT Second Workshop on Computational Approaches to Linguistic Creativity, 2010.
27. A. Ramakrishnan A, S. Kuppan, and S. L. Devi, “Automatic Generation of Tamil Lyrics for Melodies,” Proc. of Workshop on Computational Approaches to Linguistic Creativity, 2009.
28. H. Sakoe, and S. Chiba, “Dynamic Programming Algorithm Optimization for Spoken Word Recognition,” IEEE Transactions on Acoustics, Speech, and Signal Processing, Nr. 1, p. 43-49, 1987.
29. Y. Wang, M. Y. Kan, T. L. Nwe, A. Shenoy, and J. Yin, “LyricAlly:Automatic Synchronization of Acoustic Musical Signals and Textual Lyrics,” Proc. of ACM International Conference on Multimedia, 2004.
30. C. H. Wong, W. M. Szeto, and K. H. Wong, “Automatic Lyrics Alignment for Cantonese Popular Music,” Multimedia Systems, Vol. 12, No. 4-5, 2007.
31. S. Yu, J. Hong, and C. C. J. Kuo, “Similarity Matrix Processing for Music Structure Analysis,” Proc. of the 1st ACM Workshop on Audio and Music Computing Multimedia, 2006.
32. 楊蔭瀏、孫從音、陳幼韓、何為與李殿魁，語言與音樂，丹青圖書有限公司，1986。
33. 謝峰賜，簡易詞曲創作入門，新鳴遠出版有限公司，1993。
34. 陳建銘，國語流行歌曲中的編曲工作，國立台灣大學音樂研究所碩士論文，2002。
35. 徐富美與高林傳，歌詞聲調與旋律聲調相諧和的電腦檢測，世界華語文教學研討會論文集，2003。
36. 黃志華，粵語歌詞創作談，三聯出版社，2003。
37. 楊漢倫，粵語流行曲導論，香港特別行政區政府教育局，2009。
38. 張嘉惠、李淑瑩、林書彥、黃嘉毅與陳志銘，以最佳化及機率分佈判斷漢字聲符之研究，自然語言與語音處理研討會論文集(ROCLING)，2010。
39. 胡又天，流行詞話，第三期，2011。
40. 陳富容，現代華語流行歌詞格律初探，逢甲人文社會學報，第22期，第75-100頁，2011。
41. 樂句(Phrase)，http://en.wikipedia.org/wiki/Phrase_(music)

## License

All rights reserved.
