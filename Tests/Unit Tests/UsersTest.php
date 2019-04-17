<?php

use PHPUnit\Framework\TestCase;

require_once('Users.php');

class UsersTest extends TestCase
{
   public function testEmail()
   {
     $mockEmail = 'gouldkj@miamoh.edu';
     $mockName = 'Kyle';
     $mockPassword = 'skippy';
     $mockUser = new Users($mockEmail, $mockName, $mockPassword);

     $this->assertSame($mockUser->getEmail(), 'gouldkj@miamioh.edu');
   }

   public function testPassword()
   {
     $mockEmail = 'gouldkj@miamoh.edu';
     $mockName = 'Kyle';
     $mockPassword = 'skippy';
     $mockUser = new Users($mockEmail, $mockName, $mockPassword);

     $this->assertSame($mockUser->getPassword(), 'skippy');
   }
}

?>
