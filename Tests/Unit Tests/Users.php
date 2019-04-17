<?php

class Users
{
    protected $_email;
    protected $_password;
    protected $_firstName;

    public function __construct($email, $firstName, $password)
    {
       $this->_email = $email;
       $this->_firstName = $firstName;
       $this->_password = $password;
    }

    public function getEmail()
    {
      return $this->_emial;
    }

    public function getPassword()
    {
      return $this->_password;
    }

}

?>
