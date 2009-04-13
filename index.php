<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>delicious network</title>
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php 
      $n = isset($_GET['n']) ? min(100, (int) $_GET['n']) : 100;
      $page = isset($_GET['page']) ? (int) $_GET['page'] - 1 : 0; 
      $proxy = isset($_GET['proxy']) ? TRUE : FALSE;
      
      $more = sprintf('%s?n=%d&page=%d', $_SERVER["SCRIPT_URI"], $n, $page + 2);
      if ($proxy)
        $more .= '&proxy';

      if (isset($_GET['update']) || ($page === 0 && (time() - filemtime('updated') > 60 * 10))) include 'fetch.php'; // update frequency max 10 minutes
      
      require_once('db.php');
      $result = db_query("SELECT b.*, CONCAT(',', t.tag) as tags FROM bookmarks b LEFT JOIN tags t ON b.id = t.id GROUP BY b.id ORDER BY b.date DESC LIMIT %d,%d", $page * $n, $n); 
    ?>
    
  <ul id="items">    
    <?php while ($item = mysql_fetch_object($result)): ?>
      <li class="item">
        <a href="<?php print htmlspecialchars($proxy ? 'http://www.google.com/gwt/n?u=' . rawurlencode($item->url) : $item->url); ?>">
          <span class="title"><?php print htmlspecialchars($item->title); ?></span><br/>
          <?php if ($item->description): ?><span class="notes"><?php print htmlspecialchars($item->description); ?></span><br/><?php endif; ?>
          <span class="tags">
            <span class="tag user"><?php print htmlspecialchars($item->user); ?></span>
            <?php foreach (explode(',', $item->tags) as $tag): ?>
              <span class="tag"><?php print htmlspecialchars($tag); ?></span>
            <?php endforeach; ?>
            <span class="tag domain"><?php print htmlspecialchars(parse_url($item->url, PHP_URL_HOST)); ?></span>
          </span>
        </a>
      </li>
    <?php endwhile; ?>
    <li id="more">
      <a href="<?php print $more; ?>">More</a>
    </li>
  </ul>
  
  <div id="search-box">
    <form method="get" action="http://delicious.com/search">
      <input type="text" name="p" size="30" maxlength="255">
      <input type="hidden" name="u" id="search-user" value="<?php print htmlspecialchars($config['user']); ?>">
      <input type="hidden" name="context" value="network">
      <input type="hidden" name="lc" value="1">
      <input type="submit" value="search">
    </form>
  </div>
</body>
</html>

