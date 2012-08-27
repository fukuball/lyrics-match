<?php
/**
 * word_net_parse.php
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-schedule/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

require_once dirname(dirname(__FILE__))."/p-config/application-setter.php";

$db_obj = LMDBAccess::getInstance();
$select_sql = "SELECT id,term FROM lyrics_term_unique WHERE id=17";

$query_result = $db_obj->selectCommand($select_sql);

foreach ($query_result as $query_result_data) {

   $unique_term_id = $query_result_data['id'];
   $unique_term = $query_result_data['term'];
   echo "unique_term_id: ".$unique_term_id." \n";
   echo "unique_term: ".$unique_term." \n";

   $select_sql2 = "SELECT id,word_net FROM lyrics_term_unique WHERE word_net LIKE '%,$unique_term,%' ORDER BY id LIMIT 1";

   $query_result2 = $db_obj->selectCommand($select_sql2);

   $get_word_net = 0;
   foreach ($query_result2 as $query_result_data2) {
      $get_word_net = 1;
      $word_net = $query_result_data2['word_net'];

      $update_sql = "UPDATE ".
                    "lyrics_term_unique ".
                    "SET pass_to_word_net='1', word_net='$word_net' ".
                    "WHERE ".
                    "id='$unique_term_id' ".
                    "LIMIT 1";

      $query_result3 = $db_obj->updateCommand($update_sql);

      echo "unique_term: $unique_term_id $unique_term , get word_net: $word_net \n";

   }

   if ($get_word_net == 0) {

      $word = iconv("UTF-8", "Big5", $unique_term);
      $link = 'http://cwn.ling.sinica.edu.tw/_process.asp?inputword='.urlencode($word).'&radiobutton=1';

      // get song detail
      $yql_query = urlencode('SELECT * FROM html WHERE url="'.$link.'"');
      $wordnet_page_html = file_get_contents('http://query.yahooapis.com/v1/public/yql?q='.$yql_query.'&format=json');
      $wordnet_page_dom = json_decode($wordnet_page_html);

      $table = $wordnet_page_dom->query->results->body->table;

      $result_num = 0;
      if (is_array($table[0]->tr)) {
         $result_num = $table[0]->tr[0]->td->p->font[1]->content;
      } else {
         $update_sql = "UPDATE ".
                       "lyrics_term_unique ".
                       "SET pass_to_word_net='1' ".
                       "WHERE ".
                       "id='$unique_term_id' ".
                       "LIMIT 1";
         $query_result3 = $db_obj->updateCommand($update_sql);
      }

      if (is_numeric($result_num) && $result_num>0) {

         echo "result_num: ".$result_num." \n";
         $word_net_link = $table[1]->tr->td->table->tr[2]->td->table->tr[2]->td[2]->p->a;
         $word_net_string = ',';

         print_r($table[1]->tr->td);

         //if (!empty($word_net_link)) {
         //   foreach ($word_net_link as $word_net) {
         //
         //      $word_net_content = $word_net->content;
         //
         //      $nums = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
         //      $word_net_content = str_replace($nums, "", $word_net_content);
         //
         //      $word_net_string = $word_net_string.$word_net_content.',';
         //   }
         //}

         echo "word_net: ".$word_net_string." \n";

         if ($word_net_string!=',') {
            $update_sql = "UPDATE ".
                          "lyrics_term_unique ".
                          "SET pass_to_word_net='1', word_net='$word_net_string' ".
                          "WHERE ".
                          "id='$unique_term_id' ".
                          "LIMIT 1";

            $query_result3 = $db_obj->updateCommand($update_sql);

            echo "unique_term: $unique_term_id $unique_term , get word_net: $word_net_string \n";
         } else {
            $update_sql = "UPDATE ".
                          "lyrics_term_unique ".
                          "SET pass_to_word_net='1' ".
                          "WHERE ".
                          "id='$unique_term_id' ".
                          "LIMIT 1";
            $query_result3 = $db_obj->updateCommand($update_sql);
         }

      }
   }

}




?>