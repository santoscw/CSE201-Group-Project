<?php

class Dog
{
  protected $breed;
  protected $size;
  protected $lifeExpec;

  public function __construct($breed, $size, $lifeExpec)
  {
    $this->_breed = $breed;
    $this->_size = $size;
    $this->_lifeExpec = $lifeExpec;
  }

  public function getBreed()
  {
    return $this->_breed;
  }

  public function getSize()
  {
    return $this->_size;
  }

  public function getLifeExpec()
  {
    return $this->_lifeExpec;
  }

}

?>
