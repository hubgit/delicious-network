<?php

require 'config.inc.php';

mysql_connect('localhost', $config['db']['user'], $config['db']['pass']) or die('Could not connect to MySQL database' . "\n"); // SERVER, DB USERNAME, DB PASSWORD
mysql_select_db($config['db']['db']) or die('Could not select database' . "\n"); // DATABASE

mysql_query('SET CHARACTER SET utf8');

function db_query(){
  $params = func_get_args();
  $query = array_shift($params);
 
  foreach ($params as $key => $value)
    if (!is_int($value))
      $params[$key] = mysql_real_escape_string($value);
  
  $sql = vsprintf($query, $params);
  
  $result = mysql_query($sql);
  if (mysql_errno())
    exit(sprintf('MySQL error %d: %s', mysql_errno(), mysql_error()));
    
  return $result;
}


