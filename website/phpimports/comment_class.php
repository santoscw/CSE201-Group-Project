<?php

require_once('functions.php');

/**
 * TODO
 * 
 * @author      Benjamin Arehart <benjamin@arehart.com>
 * @version     v1.1
 * @since       Class available since v1.1 of Comment Page
 */
class CommentTable {

    /**
     * TODO
     * 
     * @var int
     * @access private
     */
    private $_user_id;

    /**
     * The id of the dog whose comments we're looking at.
     * 
     * This integer value is used to get the comments associated with the dog we're looking at.
     * 
     * @var int
     * @access private
     */
    private $_dog_id;

    /**
     * TODO
     * 
     * @var int
     * @access private
     */
    private $_user_level;

    /**
     * Creates the CommentTable object
     * 
     * @return CommentTable  the CommentTable object.
     */
    public function __construct($user_id, $dog_id) {
        $this->_user_id = $user_id;
        $this->_dog_id = $dog_id;

        if ($user_id == 0) {
            $this->_user_level = 0;
        } else {
            $query = <<<_STRING
            SELECT level FROM `user` WHERE uid = $user_id
_STRING;
            $result = queryData($query);
            if (!$result) {
                $this->_user_level = 0;
            } else {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $this->_user_level = (int)$row['level'];
            }
            $result->close();
        }
    }

    /**
     * Creates the MySQL query based on the dog id.
     * 
     * @return: a string query
     * @access private
     */
    private function buildQuery() {
        $dogid = $this->_dog_id;
        $query = <<<_STRING
        SELECT 
        t1.comment AS comment,
        t2.username AS username, 
        t1.time AS time,
        t1.id AS uid
        FROM `commentDog` AS t1
        LEFT JOIN `user` AS t2 ON t1.user_id = t2.uid
        WHERE t1.dog_id = $dogid
        ORDER BY time DESC
_STRING;
        return $query;
    }

    /**
     * Builds the comment table
     * 
     * If the user level is high enough, this will also have a 'Remove' column that works to remove comments
     * 
     * @access public
     */
    public function query() {
        $query = $this->buildQuery();
        $result = queryData($query);
        if (!$result) {
            return "MySQL Query error. Will be rectified shortly. \n" . $data->error;
        } else {
            $rows = $result->num_rows;
            $mod_header = $modPopup = null;
            if ($this->_user_level >= 1) {
                $mod_header = "<th>REMOVE COMMENT</th>";
            }
            $pastComments = <<<_STRING
            <table data-role="table" id="comments_table" class="ui-responsive ui-table ui-corner-all">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Time</th>
                        <th>Comment</th>
                        $mod_header
                    </tr>
                </thead>
                <tbody>
_STRING;
            for ($j = 0; $j < $rows; ++$j) {
                $result->data_seek($j);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $mod_body = null;
                $documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
                if ($this->_user_level >= 1) {
                    $mod_body = <<<_STRING
                <td>
                    <a href="#confirm{$row['uid']}" data-rel="popup" data-role="button" data-transition="pop" class="ui-btn ui-btn-icon-right ui-icon-delete">REMOVE</a>
                </td>
_STRING;
                }

                $commentRow = <<<_STRING
                    <tr>
                    <td>{$row['username']}</td>
                    <td>{$row['time']}</td>
                    <td>{$row['comment']}</td>
                    $mod_body
                    </tr>
_STRING;
                if ($this->_user_level >= 1) {
                    $modPopup = $modPopup . <<<_STRING
                    <div data-role="popup" id="confirm{$row['uid']}" data-theme="a" class="ui-corner-all ui-popup ui-body-a">
                        <div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
                            <h1 class="ui-title">Are you sure?</h1>
                        </div>
                        <div class="ui-corner-bottom ui-content">
                            <form data-form="ui-body-a" method="post" action="$documentfunc" data-ajax="false">
                                <h3 class="ui-title">Are you sure you want to delete this comment?</h3>
                                <p>This CANNOT be undone.</p>
                                <a href="#" data-role="button" data-rel="back" data-theme="a" class="ui-btn ui-btn-icon-right ui-icon-back ui-corner-all">Cancel</a>
                                <a href="#" data-role="button" class="ui-btn ui-btn-icon-right ui-icon-delete ui-corner-all submitProxy"><b>DELETE</b></a>
                                <input type="hidden" value="{$row['uid']}" name="delete" />
                            </form>
                        </div>
                    </div>
_STRING;
                }
                $pastComments = $pastComments . $commentRow;
            }
            $pastComments = $pastComments . "</tbody></table>" . $modPopup;
            $result->close();
            return $pastComments;
        }
    }



}

?>
