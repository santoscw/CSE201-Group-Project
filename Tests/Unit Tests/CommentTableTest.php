<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\website\phpimports\comment_class.php');

class CommentTableTest extends TestCase
{

  // Tests the ability of the CommentTable class to create new CommentTable
  // objects.
  public function testTableCreation()
  {
    $mockTable1 = new CommentTable(0, 10);
    $mockTable2 = new CommentTable(0, 10);
    $mockTable3 = new CommentTable(0, 20);

    // Should pass
    $this->assertEquals($mockTable1, $mockTable2);

    // Calls the query method for both tables which builds the user comment
    // table.
    $mockTable1->query();
    $mockTable2->query();

    // This should return true since the two comment tables should have gone
    // through the same process in the query function.
    $this->assertEquals($mockTable1, $mockTable2);
  }// End testTableCreation

}// End testCommentTable

?>
