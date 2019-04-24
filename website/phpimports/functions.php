<?php
	$hn = 'localhost';
	$dbuser = 'project';
	$dbdata = 'dog';
	$un = 'phpconnect';
	$pw = 'phpconnect1234';
	$socket = '/var/run/mysqld/mysqld.sock';
	$connection = 	new mysqli($hn, $un, $pw, $dbuser, 3306, $socket);
	$data = 		new mysqli($hn, $un, $pw, $dbdata, 3306, $socket);
	if ($connection->connect_error) die($connection->connect_error);
	if ($data->connect_error) die($data->connect_error);
	
	
	function closeConnection()
	{
		global $connection;
		$connection->close();
	}
	
	function closeData()
	{
		global $data;
		$data->close();
	}
	
	function queryUser($query)
	{
		global $connection;
		$result = $connection->query($query);
		return $result;
	}
	
	function queryData($query)
	{
		global $data;
		$result = $data->query($query);
		return $result;
	}
	
	function mysql_sanitize_db_input_info($string)
	{
		return mysql_entities_fix_string($string);
	}
	
	function mysql_sanitize_password($string)
	{
		$string = mysql_sanitize_db_input_info($string);
		return $string;
	}
	
	function mysql_entities_fix_string($string)
	{
		return htmlentities(mysql_fix_string($string));
	}
	
	function mysql_fix_string($string)
	{
		global $connection;
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return $connection->real_escape_string($string);
	}
?>

Leave