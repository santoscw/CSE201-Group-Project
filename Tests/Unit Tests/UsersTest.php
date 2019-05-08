<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\Tests\Unit Tests\Users.php');

class UsersTest extends TestCase
{
  /**
  * Tests ability to create new user entry objects by creating three then
  * checking their variables to ensure they were created properly.
  */
   public function testUserCreation()
   {
     // Creates three mock user entry objects
     $mockUser1 = new Users(4, 'testusername', 'testemail@gmail.com', 'testpassword', 0);
     $mockUser2 = new Users(5, 'testusernametwo', 'testemail2@gmail.com', 'testpassword2', 1);
     $mockUser3 = new Users(6, 'testusernamethree', 'testemail3@gmail.com', 'testpassword3', 2);

     // Checks $uid for each user entry
     $this->assertEquals(4, $mockUser1->getUID());
     $this->assertEquals(5, $mockUser2->getUID());
     $this->assertEquals(6, $mockUser3->getUID());

     // Checks $username for each entry
     $this->assertEquals('testusername', $mockUser1->getUsername());
     $this->assertEquals('testusernametwo', $mockUser2->getUsername());
     $this->assertEquals('testusernamethree', $mockUser3->getUsername());

     // Checks $email for each user entry
     $this->assertEquals('testemail@gmail.com', $mockUser1->getEmail());
     $this->assertEquals('testemail2@gmail.com', $mockUser2->getEmail());
     $this->assertEquals('testemail3@gmail.com', $mockUser3->getEmail());

     // Checks $password for each user entry
     $this->assertEquals('testpassword', $mockUser1->getPassword());
     $this->assertEquals('testpassword2', $mockUser2->getPassword());
     $this->assertEquals('testpassword3', $mockUser3->getPassword());

     // Checks the $level for each user entry
     $this->assertEquals(0, $mockUser1->getLevel());
     $this->assertEquals(1, $mockUser2->getLevel());
     $this->assertEquals(2, $mockUser3->getLevel());
   }// End testUserCreation

   /**
   * Tests ability to add new user entries to the user database by creating
   * three new entries and adding them to the database while checking to verify
   * they were properly added.
   */
   public function testUserEntryAddition()
   {
     // Adds an entry for an administrator, a moderator, and a regular user
     // and checks that each has been added.
     $this->assertEquals('Entry added', addUser(7, 'testusername4', 'testemail4@gmail.com', 'testpassword4', 0));
     $this->assertEquals('Entry added', addUser(8, 'testusername5', 'testemail5@gmail.com', 'testpassword5', 1));
     $this->assertEquals('Entry added', addUser(9, 'testusername6', 'testemail6@gmail.com', 'testpassword6', 2));
   }// End testUserEntryAddition
}// End UsersTest

?>
