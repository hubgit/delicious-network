<?php header('Content-Type: application/atom+xml;charset=UTF-8'); ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>hublicious' Delicious network memes</title>
    <link rel="alternate" type="text/html" href="http://alf.hubmed.org/delicious-network/"/>
    <link rel="self" type="application/atom+xml" href="http://alf.hubmed.org/delicious-network/memes.atom.php"/>
    <id>http://alf.hubmed.org/delicious-network/</id>
    <updated><?php print date(DATE_ATOM); ?></updated>
    <author><name>Delicious network</name></author>
      
    <?php 
      require 'db.php';
      
      $n = 2;
      $days = 3;
      
      $result = db_query("SELECT COUNT(*) as n, `hash`, `url`, `date` FROM bookmarks WHERE `date` > %d GROUP BY `hash` HAVING n >= %d ORDER BY n DESC", time() - 60 * 60 * 24 * $days, $n); 
    ?>
    
      <?php while ($item = mysql_fetch_object($result)): $result2 = db_query("SELECT b.*, CONCAT(',', t.tag) as tags FROM bookmarks b LEFT JOIN tags t ON b.id = t.id WHERE `hash` = '%s' GROUP BY b.id", $item->hash); ?>
        <entry>
          <title><?php print htmlspecialchars($item->url); ?></title>
          <link rel="alternate" type="text/html" href="<?php print htmlspecialchars($item->url); ?>"/>
          <id><?php print 'http://alf.hubmed.org/delicious-network/items/' . md5($item->url); ?></id>
          <published><?php print date(DATE_ATOM, $item->date); ?></published>         
          <updated><?php print date(DATE_ATOM, $item->date); ?></updated>         
          <summary><?php print htmlspecialchars($item->url); ?></summary>
          
           <content type="xhtml">
              <div xmlns="http://www.w3.org/1999/xhtml">
                 <a href="<?php print htmlspecialchars($item->url); ?>"><span class="url title"><?php print htmlspecialchars($item->url); ?></span></a>
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
                    </li>
                    <?php endwhile; ?>
                  </ul>
              </div>
          </content>
        </entry>
      <?php endwhile; ?>

</feed>
    
