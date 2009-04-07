<?php

require 'db.php';

$items = json_decode(file_get_contents(sprintf('http://feeds.delicious.com/v2/json/network/%s?count=100', rawurlencode($config['user']))));

$count = 0;
foreach ($items as $item){ 
  db_query(
   "INSERT IGNORE INTO bookmarks 
   (`user`,`hash`,`date`,`url`,`title`,`description`) 
   VALUES 
   ('%s','%s', %d, '%s', '%s', '%s')",
   $item->a, md5($item->u), strtotime($item->dt), $item->u, $item->d, $item->n
  );
   
  if (!$id = mysql_insert_id())
    break;
  
  $count++;
  
  if (!empty($item->t))
    foreach ($item->t as $tag)
      db_query("INSERT IGNORE INTO tags (`id`, `tag`) VALUES (%d, '%s')", $id, $tag);
    
}
?>
<div class="result">Added <span class="count"><?php print $count; ?></span> items.</div>
