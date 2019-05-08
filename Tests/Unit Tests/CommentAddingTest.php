<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\website\phpimports\comment_class.php');

class CommentAddingTest extends TestCase
{

  /**
  * Tests ability to generate new comments by creating a new comment table
  * then adding comments to it and verifying that these comments have been
  * successfully added.
  */
  public function testCommentAdding()
  {
    // Start by creating a CommentTable object for the comments.
    $mockTable1 = new CommentTable(0, 10);

    // Should return true if the function successfully adds the comment and
    // returns the affirmative string.
    $this->assertEquals('Thanks for the comment!', addComment(10, 1, 'test comment'));
    $this->assertEquals('Thanks for the comment!', addComment(10, 1, 'added new comment'));
    $this->assertEquals('Thanks for the comment!', addComment(10, 1, 'nkdsnvlknwevnwn'));

    // Creates a new comment table for a different dog entry and verifies
    // comments can be added to it.
    $mockTable1 = new CommentTable(0, 1);

    // Should return true if the function successfully adds the comment and
    // returns the affirmative string.
    $this->assertEquals('Thanks for the comment!', addComment(1, 1, 'test comment 2'));
    $this->assertEquals('Thanks for the comment!', addComment(1, 1, 'added another comment'));
    $this->assertEquals('Thanks for the comment!', addComment(1, 1, 'nkdscnk dsllsdk sk'));
  }// End testCommentAdding
}// End CommentAddingTest

?>
