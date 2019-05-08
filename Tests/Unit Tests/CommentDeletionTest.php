<?php

use PHPUnit\Framework\TestCase;

require_once('C:\wamp64\www\app\website\phpimports\comment_class.php');

class CommentDeletionTest extends TestCase
{

  /**
  * Tests ability to delete comments from a comment table.
  * Does this by creating a comment table then adding several comments
  * to it and deleting these comments, then checking to see if these comments
  * have been deleted.
  */
  public function testCommentDeletion()
  {

    // Start by creating a CommentTable object for the comments.
    $mockTable1 = new CommentTable(0, 5);

    // Create a new comment and add it to the table.
    addComment(5, 1, 'test comment');

    // Should return true as the delComment function will not throw any errors
    // and will return a verifying string if the comment is successfully
    // deleted.
    $this->assertEquals('Comment successfully deleted', delComment(1));

    // Add new comments to the same table but at a different entries.
    addComment(5, 1, 'test comment 2');
    addComment(5, 1, 'skjvnkslnlevmlvmelkanv');
    addComment(5, 1, 'smvlnalkvrjelnvelnvln');
    addComment(5, 1, 'dogs are fun');

    // Should return true as the delComment function will not throw any errors
    // and will return a verifying string if the comment is successfully
    // deleted.
    $this->assertEquals('Comment successfully deleted', delComment(1));
    $this->assertEquals('Comment successfully deleted', delComment(2));
    $this->assertEquals('Comment successfully deleted', delComment(3));
    $this->assertEquals('Comment successfully deleted', delComment(4));

  }// End testCommentDeletion

}// End CommentDeletionTest

?>
