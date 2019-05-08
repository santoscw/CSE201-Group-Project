<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\Tests\Unit Tests\Dog.php');

class DogTest extends TestCase
{
  /**
  * Tests the ability to create new dog entry objects by creating new objects
  * and checking to see if all their variables are properly set.
  */
  public function testDogCreation()
  {
    // Create a new dog object
    $mockDog1 = new Dog(362, 'Bernese Mountain Dog', 'Sheep Dogs', 'Switzerland', 'https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/13000434/Bernese-Mountain-Dog-On-White-03.jpg');

    // Test each variable in the newly created dog object to verify that they
    // are correct.
    $this->assertEquals(362, $mockDog1->getID());
    $this->assertEquals('Bernese Mountain Dog', $mockDog1->getName());
    $this->assertEquals('Sheep Dogs', $mockDog1->getSection());
    $this->assertEquals('Switzerland', $mockDog1->getCountry());
    $this->assertEquals('https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/13000434/Bernese-Mountain-Dog-On-White-03.jpg', $mockDog1->getImage());
  }// End testDogCreation

  /**
  * Tests ability to add dog entries to the database by adding a few and
  * verifying that they have been added.
  */
  public function testDogEntryAddition()
  {
    // Test adding a new entry to the dog database.
    // Should return true if it recieves the 'Entry added' return message.
    $this->assertEquals('Entry added', addDog(363, 'Test Name', 'Test Section', 'Test Country', 'https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/13000434/Bernese-Mountain-Dog-On-White-03.jpg'));
  }// End testDogEntryAddition

}

?>
