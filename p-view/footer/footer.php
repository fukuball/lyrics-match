<?php
/**
 * footer.php is the view of main footer
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-view/footer/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

?>
<footer id="footer">
   <p>
     <a href="<?=SITE_HOST?>/" title="首頁">
        首頁
     </a> |
     <a href="<?=SITE_HOST?>/music/" title="音樂">
        音樂
     </a>
      <br/>
     版權所有 ©2012  DM Lab all rights reserved.
   </p>
   <div id="google_translate_element"></div>
   <script type="text/javascript">
      function googleTranslateElementInit() {
         new google.translate.TranslateElement({pageLanguage: 'zh-TW', includedLanguages: 'en,ja,zh-CN,zh-TW', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
      }
   </script>
   <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</footer>