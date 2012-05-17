<?php
/**
 * meta-include.php is head include meta
 * 
 * PHP version 5
 *
 * @category PHP
 * @package  /p-view/head/
 * @author   Fukuball Lin <fukuball@gamil.com>
 * @license  iNDIEVOX Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
 // hard code

if (!empty($item_obj)) {
   
   $this_item_class_name = get_class($item_obj);
   
   switch ($this_item_class_name) {
      
   default:
   
      $og_title = $page_title;
      $og_type = "website";
      $og_url = $current_page_full_url;
      $og_image = "http://sarasti.cs.nccu.edu.tw/p-asset/image/index-touch-icon.png";
      $og_description = "http://sarasti.cs.nccu.edu.tw/lyrics-match Lyrics Match Project";
   
      break;

   }
   
   if (empty($og_image)) {
      
      $og_image = "http://sarasti.cs.nccu.edu.tw/p-asset/image/index-touch-icon.png";
      
   }
   
   $og_description = strip_tags($og_description);
   $og_description = str_replace("\n", "", $og_description);
   $og_description = str_replace("\r", "", $og_description);
   $og_description = str_replace('&nbsp;', ' ', $og_description);
   $og_description = htmlspecialchars($og_description);
   
}

?>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<title><?php echo $page_title; ?></title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<meta name="description" content="<?php echo $og_description; ?>" />
<meta name="author" content="Brian Huang, Fukuball Lin, Sz-Yue Fan" />
<meta name="keywords" content="music, lyrics, pop music, chinese pop music, match, algorithm, research, nccu, 音樂, 歌詞, 流行音樂, 中文流行音樂, 華語流行音樂, 華文流行音樂, 詞曲搭配, 演算法, 研究, 研發, 開發, 政治大學, 政大" />
<meta property="fb:app_id" content="<?php echo FB_APP_ID; ?>"/>
<meta property="fb:admins" content="<?php echo FB_ADMIN_ID; ?>"/>
<meta property="og:title" content="<?php echo $og_title; ?>" />
<meta property="og:type" content="<?php echo $og_type; ?>" />
<meta property="og:url" content="<?php echo $og_url; ?>" />
<meta property="og:image" content="<?php echo $og_image; ?>" />
<meta property="og:description" content="<?php echo $og_description; ?>" />
<meta property="og:site_name" content="Lyrics Match" />