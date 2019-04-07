<?php
	$hn = 'localhost';
	$db = 'project';
	$un = 'phpconnect';
	$pw = 'phpconnect1234';
	$socket = '/var/run/mysqld/mysqld.sock';
	$connection = new mysqli($hn, $un, $pw, $db, 3306, $socket);
	if ($connection->connect_error) die($connection->connect_error);
	
	
	function closeConnection()
	{
		global $connection;
		$connection->close();
	}
	
	function queryMysql($query)
	{
		global $connection;
		$result = $connection->query($query);
		return $result;
	}
	
	function mysql_sanitize_db_input_info($string)
	{
		global $connection;
		return mysql_entities_fix_string($string);
	}
	
	function mysql_sanitize_password($string)
	{
		global $connection;
		$string = mysql_sanitize_db_input_info($string);
		return $string;
	}
	
	function mysql_entities_fix_string($string)
	{
		global $connection;
		return htmlentities(mysql_fix_string($string));
	}
	
	function mysql_fix_string($string)
	{
		global $connection;
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return $connection->real_escape_string($string);
	}
?>