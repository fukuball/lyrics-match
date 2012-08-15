<?php
/**
 * word-count-list.php is the word count list content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/SongActionView
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

$db_obj = LMDBAccess::getInstance();

$select_sql = "SELECT s.*, p.name, CHAR_LENGTH( REPLACE( REPLACE( s.lyric,  ' ',  '' ) ,  '\n',  '' ) ) word_count FROM song s INNER JOIN performer p ON (s.performer_id=p.id) ORDER BY word_count";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {
   $id = $query_result_data['id'];
   $name = $query_result_data['name'];
   $title = $query_result_data['title'];
   $lyric = $query_result_data['lyric'];
   $word_count = $query_result_data['word_count'];
   ?>
   <tr width="1000px">
      <td width="100px">
         <?=$id?>
      </td>
      <td width="100px">
         <?=$name?>
      </td>
      <td width="200px">
         <?=$title?>
      </td>
      <td width="500px">
         <?=nl2br($lyric)?>
      </td>
      <td width="100px">
         <?=$word_count?>
      </td>
   </tr>
   <?php
}

?>