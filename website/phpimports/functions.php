<?php
	$hn = 'localhost';
	$dbdata = 'dog';
	$un = 'phpconnect';
	$pw = 'phpconnect1234';
	$socket = '/var/run/mysqld/mysqld.sock';
	$data = 		new mysqli($hn, $un, $pw, $dbdata, 3306, $socket);
	if ($data->connect_error) die($data->connect_error);

	/**
	 * function to close the main database connection
	 */
	function closeData()
	{
		global $data;
		$data->close();
	}
	
	/**
	 * function to query the main database for the website
	 * 
	 * @global mysqli $data  The global database `mysqli` object.
	 * 
	 * @param string $query		The string query to send to the database.
	 * @return string The object that stores what returned from the database connection.
	 */
	function queryData($query)
	{
		global $data;
		$result = $data->query($query);
		return $result;
	}
	
	/**
	 * main function to clean up a string for insertion into a database.
	 * 
	 * Acts mostly as a placeholder name for a series of functions. Protects the database
	 * against methods of injection as well as ensuring that the formatting of certain characters
	 * is preserved.
	 * 
	 * @return string  the cleaned string that should be injection-proof
	 */
	function mysql_sanitize_db_input_info($string)
	{
		return mysql_entities_fix_string($string);
	}
	
	/**
	 * main function to clean up a password string to help in checking against a database.
	 * 
	 * Does the same thing as `mysql_sanitize_db_input_info()`, but is semantically created to specifically
	 * handle passwords.
	 * 
	 * @see mysql_sanitize_db_input_info()
	 * @return string  the cleaned password string that is safe to check against the database hash of it.
	 */
	function mysql_sanitize_password($string)
	{
		$string = mysql_sanitize_db_input_info($string);
		return $string;
	}
	
	/**
	 * performs the htmlentities() function while continuing down the cleaning function chain.
	 * 
	 * @return string  the string that is in the process of being cleaned and made injection-proof
	 */
	function mysql_entities_fix_string($string)
	{
		return htmlentities(mysql_fix_string($string));
	}
	
	/**
	 * performs several functions to clean a string
	 * 
	 * @return string  the string that is in the process of being cleaned and made injection-proof
	 */
	function mysql_fix_string($string)
	{
		global $data;
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return $data->real_escape_string($string);
	}
?>

