<?php

use PHPUnit\Framework\TestCase;

/**
* Commented out require_once() because, since the Comments class does not
* exist this line will cause errors and prevent the class from running properly.
*
* require_once('Comments.php');
*/

/**
* This is a test class for the Comments class, meant to test funtionality
* for bugs and errors.
*
* This class creates mock Comments objects then uses these mock objects
* to test each function in the Comments class.
*
* @author     Kyle Gould <gouldkj@miamioh.edu>
*/

class CommentsTest extends TestCase
{

  /**
  * Tests the ability of Comments.php to create new comment objects
  * given a string $_comment, and a user id $_userID.
  *
  * The test uses the assertInstanceOf() function to verify that the created
  * object is an instance of the Comments class.
  *
  * The test also uses getter methods in the Comments class to verify
  * that each mock object has the correct $_comment and $_userID.
  */
  public function testCommentCreation()
  {
    $mockComment1 = new Comments('This dog is very friendly', '10');
    $mockComment2 = new Comments('This dog sheds a lot', '20');

    $this->assertInstanceOf(Comments::class, $mockComment1);
    $this->assertInstanceOf(Comments::class, $mockComment2);

    $this->assertEquals(10, $mockComment1->getUserID());
    $this->assertEquals('This dog is very friendly', $mockComment1->getComment());

    $this->assertEquals(20, $mockComment2->getUserID());
    $this->assertEquals('This dog sheds a lot', $mockComment2->getComment());

  }// End testCommentCreation

  /**
  * Tests the isValid function in Comments.php by creating several mock
  * Comments objects and calling the isValid function to assert whether
  * the objects are valid.
  */
  public function testIsValid()
  {
    $mockComment1 = new Comments('This dog is very friendly', '10');
    $mockComment2 = new Comments('This dog sheds a lot', '-10');

    $this->assertTrue($mockComment1->valid());

    // This mock object has a negative id number and so should be valid.
    $this->assertFalse($mockComment2->valid());
  }// End testIsValid

}// End CommentsTest

?>
