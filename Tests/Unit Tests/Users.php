<?php

require_once('C:\wamp64\www\app\Website\phpimports\functions.php');

/**
* Adds a user entry to the user database
*
* @return string success or fail message
*/
function addUser($uid, $username, $email, $password, $level)
{
  $data = new mysqli('localhost', 'phpconnect', 'phpconnect1234', 'dog', '3306', 'var/run/mysqld.sock');
    $query = <<<_STRING
        INSERT INTO `dog` (uid, username, email, password, level) VALUES ('$uid', '$username', '$email', '$password', '$level')
_STRING;
    $result = queryData($query);

    if (!$result)
        return 'Entry added';
    else
    {
        return "<p class='submit'>Entry added</p>";
    }
}// End addDog

class Users
{
  /**
  * @var int
  * @access private
  */
  private $_uid;

  /**
  * @var string
  * @access private
  */
  private $_username;

  /**
  * @var string
  * @access private
  */
  private $_email;

  /**
  * @var string
  * @access private
  */
  private $_password;

  /**
  * @var int
  * @access private
  */
  private $_level;

    public function __construct($uid, $username, $email, $password, $level)
    {
      $this->_uid  = $uid;
      $this->_username = $username;
      $this->_email = $email;
      $this->_password = $password;
      $this->_level = $level;
    }

    public function getUID()
    {
      return $this->_uid;
    }// End getUID

    public function getUsername()
    {
      return $this->_username;
    }// End getUsername

    public function getEmail()
    {
      return $this->_email;
    }// End getEmail

    public function getPassword()
    {
      return $this->_password;
    }// End getPassword

    public function getLevel()
    {
      return $this->_level;
    }// End getLevel

}

?>
