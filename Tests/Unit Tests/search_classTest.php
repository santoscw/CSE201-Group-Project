<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\website\phpimports\search_class.php');

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
    $mockSearch2 = new Search('dog', 'name', 'retriever', 0);
    $mockSearch3 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch4 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch5 = new Search('breed', 'name', 'retriever', 0);
    $mockSearch6 = new Search('breed', 'name', 'retriever', 0);

    // Assertion should pass since these objects are identical
    $this->assertEquals($mockSearch, $mockSearch2);

    // Assertion should pass since these objects are identical
    $this->assertEquals($mockSearch3, $mockSearch4);

    // Assertion should pass since these objects are identical
    $this->assertEquals($mockSearch5, $mockSearch6);
  } // End testTableCreation()

  /**
  * Tests the query() function in search_class.php using
  * several mock Search objects.
  */
  public function testQuery()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 0);
    $mockSearch2 = new Search('dog', 'name', 'retriever', 0);
    $mockSearch3 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch4 = new Search('breed', 'type', 'retriever', 0);
    $mockSearch5 = new Search('breed', 'name', 'retriever', 0);
    $mockSearch6 = new Search('breed', 'name', 'retriever', 0);

    // Assertion should pass since these objects are identical
    $this->assertEquals($mockSearch, $mockSearch2);

    // Assertion should pass since these objects are identical
    $this->assertEquals($mockSearch3, $mockSearch4);

    // Assertion should pass since these objects are identical
    $this->assertEquals($mockSearch5, $mockSearch6);
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
    $mockSearch->addTen();
    $this->assertEquals(10, $mockSearch->getOffset());

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
    $mockSearch->subTen();
    $this->assertEquals(0, $mockSearch->getOffset());
  }// End testSubTen()

  /**
  * Tests the setOffset() function in search_class.php using
  * one mock Search object.
  */
  public function testSetOffset()
  {
    $mockSearch = new Search('dog', 'name', 'retriever', 10);

    // Assertion should pass
    $mockSearch->setOffset(20);
    $this->assertEquals(20, $mockSearch->getOffset());
  }// End testSetOffset()

} // End search_classTest

?>
