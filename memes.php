<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>delicious network memes</title>
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php 
      $n = isset($_GET['n']) ? min(10, (int) $_GET['n']) : 1;
      $days = isset($_GET['days']) ? min(28, (int) $_GET['days']) : 3;
      $proxy = isset($_GET['proxy']) ? TRUE : FALSE;
    ?>
      
    <?php 
      require 'db.php';
      $result = db_query("SELECT COUNT(*) as n, `hash`, `url` FROM bookmarks WHERE `date` > %d GROUP BY `hash` HAVING n > %d ORDER BY n DESC", time() - 60 * 60 * 24 * $days, $n); 
    ?>
    
    <ul id="memes">   
      <?php while ($item = mysql_fetch_object($result)): $result2 = db_query("SELECT b.*, CONCAT(',', t.tag) as tags FROM bookmarks b LEFT JOIN tags t ON b.id = t.id WHERE `hash` = '%s' GROUP BY b.id", $item->hash); ?>
       <li>
         <a href="<?php print htmlspecialchars($proxy ? 'http://www.google.com/gwt/n?u=' . rawurlencode($item->url) : $item->url); ?>"><span class="url title"><?php print htmlspecialchars($item->url); ?></span></a>
         <ul>
           <?php while ($item2 = mysql_fetch_object($result2)): ?>
           <li class="item">
             <span class="tag user"><?php print htmlspecialchars($item2->user); ?></span>: <span class="title"><?php print htmlspecialchars($item2->title); ?></span><br/>
                <?php if ($item2->description): ?><span class="notes"><?php print htmlspecialchars($item2->description); ?></span><br/><?php endif; ?>
                <span class="tags">
                  <?php foreach (explode(',', $item2->tags) as $tag): ?>
                    <span class="tag"><?php print htmlspecialchars($tag); ?></span>
                  <?php endforeach; ?>
                </span>
              </a>
            </li>
            <?php endwhile; ?>
          </ul>
        </li>
      <?php endwhile; ?>
    </ul>
 
</body>
</html>

