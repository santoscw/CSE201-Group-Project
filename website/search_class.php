<?php

require_once('functions.php');

class Search {
    private $table;
    private $column;
    private $term;
    private $offset;

    public function __construct($table, $column, $term) {
        
        $this->table = $table;
        $this->column = $column;
        $this->term = $term;
        $this->offset = 0;
        $this->valid();
    }


    private function valid() {
        if ($this->table == "dog") {
            if (!($this->column == "name" || $this->column == "breed" || $this->column == "shelter")) {
                throw new InvalidArgumentException(
                    sprintf(
                        '"%s" is not a valid search column.',
                        $this->column
                    )
                );
            }
        } else if ($this->table == "breed") {
            if (!($this->column == "name" || $this->column == "type")) {
                throw new InvalidArgumentException(
                    sprintf(
                        '"%s" is not a valid search column.',
                        $this->column
                    )
                );
            }
        } else {
            if (!($this->column == "name" || $this->column == "city")) {
                throw new InvalidArgumentException(
                    sprintf(
                        '"%s" is not a valid search column.',
                        $this->column
                    )
                );
            }
        }
    }

    private function buildQuery() {
        $query = null;
        if ($this->table == "dog") {
            switch ($this->column) {
                case 'name':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter 
                    FROM 'dog' AS t1 
                    LEFT JOIN 'breed' AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN 'shelter' AS t3 ON t1.shelter_id = t3.id
                    WHERE t1.name LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
                case 'breed':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter 
                    FROM 'dog' AS t1 
                    LEFT JOIN 'breed' AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN 'shelter' AS t3 ON t1.shelter_id = t3.id
                    WHERE t2.name LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_STRING;

                    break;
                case 'shelter':
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter 
                    FROM 'dog' AS t1 
                    LEFT JOIN 'breed' AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN 'shelter' AS t3 ON t1.shelter_id = t3.id
                    WHERE t3.name LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
                default:
                    $query  = <<<_STRING
                    SELECT 
                        t1.name AS name,
                        t1.age AS age,
                        t2.name AS breed,
                        t3.name AS shelter 
                    FROM 'dog' AS t1 
                    LEFT JOIN 'breed' AS t2 ON t1.breed_id = t2.id 
                    LEFT JOIN 'shelter' AS t3 ON t1.shelter_id = t3.id
                    ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
            }
        } else if ($this->table == "breed") {
            switch ($this->column) {
                case 'name':
                    $query  = <<<_STRING
                    SELECT *
                    FROM 'breed' AS t1 
                    WHERE t1.name LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
                case 'type':
                    $query  = <<<_STRING
                    SELECT * 
                    FROM 'breed' AS t1 
                    WHERE t1.type LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
                default:
                    $query  = <<<_STRING
                    SELECT * 
                    FROM 'breed' AS t1 
                    ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
            }
        } else if ($this->table == "shelter") {
            switch ($this->column) {
                case 'name':
                    $query  = <<<_MYSQL
                    SELECT * 
                    FROM 'shelter' AS t1 
                    LEFT JOIN 'city' AS t2 ON t1.city_id = t2.id 
                    WHERE t1.name LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_MYSQL;
                    break;
                case 'city':
                    $query  = <<<_STRING
                    SELECT * 
                    FROM 'shelter' AS t1 
                    LEFT JOIN 'city' AS t2 ON t1.city_id = t2.id 
                    WHERE t2.name LIKE '%$this->term%' ORDER BY t1.name ASC LIMIT 10
_STRING;

                    break;
                default:
                    $query  = <<<_STRING
                    SELECT * 
                    FROM 'shelter' AS t1 
                    LEFT JOIN 'city' AS t2 ON t1.city_id = t2.id 
                    ORDER BY t1.name ASC LIMIT 10
_STRING;
                    break;
            }
        }
        if ($this->offset != 0) {
            $query = $query . ' OFFSET $offset';
        }
        return $query;
    }

    public function Query() {
        $query = $this->buildQuery();
        $result = queryData("SELECT * FROM dog WHERE name LIKE '%$this->term%' LIMIT 10");
        $rows = $result->num_rows;
        if ($this->table == "dog") {
            $outputtable = <<<_STRING
            <table data-role="table" id="searchTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
            <thead>
                <tr>
                    <th>Name</th>
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
                <td>{$row['t1.name']}</td>
                <td>{$row['t2.name']}</td>
                <td>{$row['t3.name']}</td>
                <td>
                <form data-form="ui-body-a" method="post" action="dog.php" data-ajax="false">
                <input type="hidden" value="{$row['t1.id']}" name="entry" />
                <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Look At</a>
                </form>
                </td>
                </tr>
_STRING;
                $outputtable = $outputtable . $outputrow;
            }
            $outputtable = $outputtable . "</tbody></table>";
        } else if ($this->table == "breed") {
            $outputtable = <<<_STRING
			<table data-role="table" id="breedTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
				<thead>
					<tr>
						<th>Name</th>
						<th data-priority="2">Image</th>
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
        } else if ($this->table == "shelter") {
            $outputtable = <<<_STRING
            <table data-role="table" id="searchTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
            <thead>
                <tr>
                    <th>Name</th>
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
                $documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
                $outputrow = <<<_STRING
                <tr>
                <td>{$row['t1.name']}</td>
                <td>{$row['t2.name']}</td>
                <td>{$row['t1.phone']}</td>
                <td>
                <form data-form="ui-body-a" method="post" action="shelter.php" data-ajax="false">
                <input type="hidden" value="{$row['t1.id']}" name="entry" />
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

    public function addTen() {
        $this->offset = $this->offset + 10;
    }

    public function subTen() {
        $this->offset = $this->offset - 10;
    }

    public function getOffset() {
        return $this->offset;
    }

}

?>