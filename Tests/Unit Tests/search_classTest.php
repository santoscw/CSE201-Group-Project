<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\search_class.php');

class search_classTest extends TestCase
{

/**
* Tests the ability of search_class.php to create different Search objects
* given a $_table, $_column, $_term, and $_offset.
*
* Valid inputs for $_column:
* If $_table = 'dog' then $_column = 'name' or 'breed' or 'shelter'
* If $_table = 'breed' then $_column = 'name' or 'type'
* If $_table = 'shelter' then $_column = 'name' or 'city'
*/
  public function testSearchCreation()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 0);
    $mockSearch2 = new Search('dog', 'testing', 'retriever', 0);
    $mockSearch3 = new Search('breed', 'name', 'retriever', 0);
    $mockSearch4 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch5 = new Search('breed', 'testing', 'retriever', 0);

    // Assertion should pass
    $this->assertEquals($mockSearch3, $mockSearch3);

    // Assertion should not pass
    $this->assertEquals($mockSearch, $mockSearch2);

  } // End testTableCreation()

  /**
  * Tests the valid() function in search_class.php using several
  * mock Search objects.
  *
  * Valid inputs for $_column:
  * If $_table = 'dog' then $_column = 'name' or 'breed' or 'shelter'
  * If $_table = 'breed' then $_column = 'name' or 'type'
  * If $_table = 'shelter' then $_column = 'name' or 'city'
  */
  public function testValid()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 0);
    $mockSearch2 = new Search('dog', 'testing', 'retriever', 0);
    $mockSearch3 = new Search('breed', 'name', 'retriever', 0);
    $mockSearch4 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch5 = new Search('breed', 'testing', 'retriever', 0);

    // Assertion should not pass
    $this->assertEquals($mockSearch, $mockSearch2);

    // Should be a valid object
    $this->assertTrue($mockSearch.valid());

    // Should result in an InvalidArgumentException for the search column
    $this->assertTrue($mockSearch2.valid());

    // Should be a valid object
    $this->assertTrue($mockSearch3.valid());

    // Should be a valid object
    $this->assertTrue($mockSearch4.valid());

    // Should result in an InvalidArgumentException for the search column
    $this->assertTrue($mockSearch5.valid());

  }// End testValid()

  /**
  * Tests the buildQuery() function in search_class.php using
  * several mock Search objects.
  */
  public function testBuildQuery()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 0);
    $mockSearch2 = new Search('dog', 'testing', 'retriever', 0);
    $mockSearch3 = new Search('breed', 'name', 'retriever', 0);
    $mockSearch4 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch5 = new Search('breed', 'testing', 'retriever', 0);
  }// End testBuildQuery()

  /**
  * Tests the query() function in search_class.php using
  * several mock Search objects.
  */
  public function testQuery()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 0);
    $mockSearch2 = new Search('dog', 'testing', 'retriever', 0);
    $mockSearch3 = new Search('breed', 'name', 'retriever', 0);
    $mockSearch4 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch5 = new Search('breed', 'testing', 'retriever', 0);
  }// End testQuery

  /**
  * Tests the addTen() function in search_class.php using
  * one mock Search object.
  */
  public function testAddTen()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 0);

    // Adds ten to the $_offset in $mockSearch then checks
    // to verify the $_offset has been raised by ten.
    $mockSearch.addTen();
    $this->assertEquals(10, $mockSearch.getOffset());

  }// End testAddTen()

  /**
  * Tests the subTen() function in search_class.php using
  * one mock Search object.
  */
  public function testSubTen()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 10);

    // Subtracts ten from the $_offset in $mockSearch then
    // checks to verify the $_offset has been lowered by ten.
    $mockSearch.subTen();
    $this->assertEquals(0, $mockSearch.getOffset());
  }// End testSubTen()

  /**
  * Tests the setOffset() function in search_class.php using
  * one mock Search object.
  */
  public function testSetOffset()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 10);

    // Assertion should pass
    $mockSearch.setOffset(20);
    $this->assertEquals(20, $mockSearch.getOffset());

    // Assertion should fail
    $mockSearch.setOffset(10);
    $this->assertEquals(20, $mockSearch.getOffset());

  }// End testSetOffset()

} // End search_classTest

?>
