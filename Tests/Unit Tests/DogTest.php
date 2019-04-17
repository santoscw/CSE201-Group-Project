<?php

use PHPUnit\Framework\TestCase;

require_once('Dog.php');

class DogTest extends TestCase
{
  public function testBreed()
  {
    $mockDog = new Dog('German Shepard', '174', '13');

    $this->assertSame($mockDog->getBreed(), 'German Shepard');
  }

  public function testSize()
  {
    $mockDog = new Dog('German Shepard', '174', '13');

    $this->assertSame($mockDog->getSize(), '174');
  }

  public function testLifeExpec()
  {
    $mockDog = new Dog('German Shepard', '174', '13');

    $this->assertSame($mockDog->getLifeExpec(), '13');
  }

}

?>
