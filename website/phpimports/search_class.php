<?php

require_once('functions.php');

/**
 * A search utility to account for multiple different queries, and aiding in pagination
 * 
 * This class takes in the _table to search on, the _column of that _table to search on, the searching _term,
 * and any starting _offset to begin with.
 * 
 * In future versions, $_offset will likely be replaced with $page, where $_offset is 10x bigger than $page.
 * 
 * As of now, pagination is handled using $_offset.
 * 
 * @author      Benjamin Arehart <benjamin@arehart.com>
 * @version     v2.6.2
 * @since       Class available since v2.1 of Advanced Search Utility
 */
class Search {

    /**
     * The table to search
     * 
     * Potential values are "dog", "breed", and "shelter".
     * 
     * @var string
     * @access private
     */
    private $_table;

    /**
     * The column on the table to search
     * 
     * Potential values depend on the table.
     * 
     * @var string
     * @access private
     */
    private $_column;

    /**
     * The search term
     * 
     * This value is based on user input, after the string has been cleaned.
     * 
     * @var string
     * @access private
     */
    private $_term;

    /**
     * The query offset.
     * 
     * Potential values are multiples of 10.
     * 
     * @var int
     * @access private
     */
    private $_offset;

    /**
     * Creates the Search object
     * 
     * Defines the four class variables and then validates them.
     * 
     * @param string    $_table     the string to define the table.
     * @param string    $_column    the string to define the column.
     * @param string    $_term      the string to define the search term.
     * @param int       $_offset    an integer to offset the query by. Defaults to 0.
     * 
     * @return Search  the Search object.
     */
    public function __construct($_table, $_column, $_term, $_offset = 0) {
        
        $this->_table = $_table;
        $this->_column = $_column;
        $this->_term = $_term;
        $this->_offset = $_offset;
        $this->valid();
    }

    /**
     * Validates the Search object
     * 
     * @return void
     * @throws InvalidArgumentException if the search object is invalid.
     * 
     * @access private
     */
    private function valid() {
        if ($this->_table == "dog") {
            if (!($this->_column == "name" || $this->_column == "breed" || $this->_column == "shelter")) {
                throw new InvalidArgumentException(
                    sprintf(
                        '"%s" is not a valid search _column.',
                        $this->_column
                    )
                );
            }
        } else if ($this->_table == "breed") {
            if (!($this->_column == "name" || $this->_column == "type")) {
                throw new InvalidArgumentException(
                    sprintf(
                        '"%s" is not a valid search _column.',
                        $this->_column
                    )
                );
            }
        } else {
            if (!($this->_column == "name" || $this->_column == "city")) {
                throw new InvalidArgumentException(
                    sprintf(
                        '"%s" is not a valid search _column.',
                        $this->_column
                    )
                );
            }
        }
    }

    /**
     * Creates the MySQL query based on the search input.
     * @return: a string query
     * @access private
     */
    private function buildQuery() {
        $query = $set = null;
        if ($this->_table == "dog") {
            switch ($this->_column) {
                case 'name':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter,
                        t1.id AS id
                    FROM `dog` AS t1 
                    LEFT JOIN `breed` AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN `shelter` AS t3 ON t1.shelter_id = t3.id
                    WHERE t1.name LIKE '%$this->_term%' 
                    ORDER BY t1.name ASC
_STRING;
                    break;
                case 'breed':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter,
                        t1.id AS id 
                    FROM `dog` AS t1 
                    LEFT JOIN `breed` AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN `shelter` AS t3 ON t1.shelter_id = t3.id
                    WHERE t2.name LIKE '%$this->_term%' 
                    ORDER BY t2.name ASC
_STRING;

                    break;
                case 'shelter':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter,
                        t1.id AS id 
                    FROM `dog` AS t1 
                    LEFT JOIN `breed` AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN `shelter` AS t3 ON t1.shelter_id = t3.id
                    WHERE t3.name LIKE '%$this->_term%' 
                    ORDER BY t3.name ASC
_STRING;
                    break;
                default:
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter,
                        t1.id AS id 
                    FROM `dog` AS t1 
                    LEFT JOIN `breed` AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN `shelter` AS t3 ON t1.shelter_id = t3.id
                    ORDER BY t1.name ASC
_STRING;
                    break;
            }
        } else if ($this->_table == "breed") {
            switch ($this->_column) {
                case 'name':
                    $query  = <<<_STRING
                    SELECT
                        t1.name AS name,
                        t1.image AS image,
                        t1.type AS type,
                        t1.id AS id
                    FROM `breed` AS t1
                    WHERE t1.name LIKE '%$this->_term%'
                    ORDER BY t1.name ASC
_STRING;
                    break;
                case 'type':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.image AS image,
                        t1.type AS type,
                        t1.id AS id
                    FROM `breed` AS t1 
                    WHERE t1.type LIKE '%$this->_term%' 
                    ORDER BY t1.type ASC
_STRING;
                    break;
                default:
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.image AS image,
                        t1.type AS type,
                        t1.id AS id
                    FROM `breed`
                    ORDER BY t1.name ASC
_STRING;
                    break;
            }
        } else if ($this->_table == "shelter") {
            switch ($this->_column) {
                case 'name':                 
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.address AS address,
                        t2.city AS city,
                        t1.phone AS phone,
                        t1.id AS id 
                    FROM `shelter` AS t1 
                    LEFT JOIN `city` AS t2 ON t1.city_id = t2.id 
                    WHERE t1.name LIKE '%$this->_term%' 
                    ORDER BY t1.name ASC 
_STRING;
                    break;
                case 'city':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.address AS address,
                        t2.city AS city,
                        t1.phone AS phone,
                        t1.id AS id
                    FROM `shelter` AS t1 
                    LEFT JOIN `city` AS t2 ON t1.city_id = t2.id 
                    WHERE t2.city LIKE '%$this->_term%' 
                    ORDER BY t2.city ASC
_STRING;

                    break;
                default:
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.address AS address,
                        t2.city AS city,
                        t1.phone AS phone,
                        t1.id AS id
                    FROM `shelter` AS t1 
                    LEFT JOIN `city` AS t2 ON t1.city_id = t2.id 
                    ORDER BY t1.name ASC 
_STRING;
                    break;
            }
        }
        
        return $query;
    }

    /**
     * queries the database and structures the output HTML table
     * 
     * Calls the $this->buildQuery() function to get the search query for this object, and sets the offset
     * for the query (if any). 
     * 
     * @return string  typically a lengthy string of HTML tags, but if the query fails, then a short message
     * stating such a thing.
     * 
     * @access public
     */
    public function query() {
        $query = $this->buildQuery();
        if (intval($this->_offset) != 0) {
            $set = " OFFSET " . intval($this->_offset);
        }
        $query = $query . " LIMIT 10 $set";
        $result = queryData($query);
        if (!$result) {
            return "MySQL Query error. Will be rectified shortly. \n" . $data->error;
        } else {
            $rows = $result->num_rows;
            if ($this->_table == "dog") {
                $outputtable = <<<_STRING
                <table data-role="table" id="search_table" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th data-priority="4">Age</th>
                        <th data-priority="2">Breed</th>
                        <th data-priority="3">Shelter</th>
                        <th data-priority="1">Look at</th>
                    </tr>
                </thead>
                <tbody>
_STRING;
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
                    $outputrow = <<<_STRING
                    <tr>
                    <td>{$row['name']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['breed']}</td>
                    <td>{$row['shelter']}</td>
                    <td>
                    <form data-form="ui-body-a" method="post" action="dog.php" data-ajax="false">
                    <input type="hidden" value="{$row['id']}" name="entry" />
                    <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Look At</a>
                    </form>
                    </td>
                    </tr>
_STRING;
                    $outputtable = $outputtable . $outputrow;
                }
                $outputtable = $outputtable . "</tbody></table>";
            } else if ($this->_table == "breed") {
                $outputtable = <<<_STRING
                <table data-role="table" id="breed_table" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th data-priority="2">Image</th>
                            <th data-priority="3">Type</th>
                            <th data-priority="1">Look at</th>
                        </tr>
                    </thead>
                    <tbody>
_STRING;
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
                    $outputrow = <<<_STRING
                    <tr>
                    <td>{$row['name']}</td>
                    <td><img src="{$row['image']}"></img></td>
                    <td>{$row['type']}</td>
                    <td>
                    <form data-form="ui-body-a" method="post" action="breed.php" data-ajax="false">
                    <input type="hidden" value="{$row['id']}" name="entry" />
                    <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Look At</a>
                    </form>
                    </td>
                    </tr>
_STRING;
                    $outputtable = $outputtable . $outputrow;
                }
                $outputtable = $outputtable . "</tbody></table>";
            } else if ($this->_table == "shelter") {
				//                            <th data-priority="2">City</th>

                $outputtable = <<<_STRING
                <table data-role="table" id="search_table" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th data-priority="2">Address</th>
                            <th data-priority="2">City</th>
                            <th data-priority="3">Phone</th>
                            <th data-priority="1">Look at</th>
                        </tr>
                    </thead>
                    <tbody>
_STRING;
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $outputrow = <<<_STRING
                    <tr>
                        <td>{$row['name']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['city']}</td>
                        <td>{$row['phone']}</td>
                        <td>
                            <form data-form="ui-body-a" method="post" action="shelter.php" data-ajax="false">
                                <input type="hidden" value="{$row['id']}" name="entry" />
                                <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Look At</a>
                            </form>
                        </td>
                    </tr>
_STRING;
                    $outputtable = $outputtable . $outputrow;
                }
                $outputtable = $outputtable . "</tbody></table>";
            }
            $result->close();

            return $outputtable;
        }
    }

    /**
     * adds ten to the offset
     * 
     * @return void
     * 
     * @access public
     */
    public function addTen() {
        $this->_offset = $this->_offset + 10;
    }

    /**
     * subtracts ten from the offset
     * 
     * @return void
     * 
     * @access public
     */
    public function subTen() {
        $this->_offset = $this->_offset - 10;
    }

    /**
     * getter for $this->_offset
     * 
     * @return int  the offset var
     * 
     * @access public
     */
    public function getOffset() {
        return $this->_offset;
    }

    /**
     * setter for $this->_offset
     * 
     * @return void
     * 
     * @access public
     */
    public function setOffset(int $set) {
        $this->_offset = $set;
    }

    /**
     * gets the number of rows total that the search query returns
     * 
     * Runs the query and returns the number of rows. Works without using the OFFSET or LIMIT keywords that
     * the displayed query runs. Useful for accounting for the uper limit of pagination.
     * 
     * @return int  number of rows returned by the search query.
     * 
     * @access public
     */
    public function getLength() {
        $query = $this->buildQuery();
        $result = queryData($query);
        if (!$result) {
            return "MySQL Query error. Will be rectified shortly. \n" . $data->error;
        } else {
            return intval($result->num_rows);
        }
    }

}

?>
