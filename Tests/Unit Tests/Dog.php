<?php

require_once('C:\wamp64\www\app\Website\phpimports\functions.php');

/**
* Adds a dog entry to the dog table
*
* @return string success or fail message
*/
function addDog($id, $name, $section, $country, $image)
{
  $data = new mysqli('localhost', 'phpconnect', 'phpconnect1234', 'dog', '3306', 'var/run/mysqld.sock');
    $query = <<<_STRING
        INSERT INTO `dog` (dog_id, name, section, country, image) VALUES ('$id', '$name', '$section', '$country', '$image')
_STRING;
    $result = queryData($query);

    if (!$result)
        return 'Entry added';
    else
    {
        return "<p class='submit'>Entry added</p>";
    }
}// End addDog


class Dog
{
  /**
  * @var string
  * @access private
  */
  protected $name;

  /**
  * @var int
  * @access private
  */
  private $id;

  /**
  * @var string
  * @access private
  */
  protected $section;

  /**
  * @var string
  * @access private
  */
  protected $country;

  /**
  * @var string
  * @access private
  */
  protected $image;

  public function __construct($id, $name, $section, $country, $image)
  {
    $this->_id = $id;
    $this->_name = $name;
    $this->_section = $section;
    $this->_country = $country;
    $this->_image = $image;
  }

  public function getID()
  {
    return $this->_id;
  }

  public function getName()
  {
    return $this->_name;
  }

  public function getSection()
  {
    return $this->_section;
  }

  public function getCountry()
  {
    return $this->_country;
  }

  public function getImage()
  {
    return $this->_image;
  }

}

?>
