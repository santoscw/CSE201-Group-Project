<?php
    require_once 'phpimports/header.php';
    $breedlistactive = "ui-btn-active ui-state-persist";

    session_start();
    $username = $deletemsg = null;
    if (isset($_POST['logout'])) {
        session_unset();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000);
        }
        session_destroy();
        header('Location: index.php');
        exit();
    }
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = 1;
    }
    if (isset($_SESSION['username']) && isset($_SESSION['level']) && $_SESSION['level'] >= 0) {
        $username = $_SESSION['username'];
        $level = $_SESSION['level'];
        $loggedin = true;
    }
    require_once 'phpimports/admin_nav.php';
    
    if (!isset($_POST['entry'])) {
        header("Location: results.php");
    }
    
    $target = $_POST['entry'];
    
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
    WHERE t1.id = $target
_STRING;
    $result = queryData($query);
    if (!$result) {
        die($data->error);
    }
    $rows = $result->num_rows;
    $outputtable = null;
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
    $outputrow = <<<_STRING
    <tr>
    <td>{$row['name']}</td>
    <td>{$row['age']}</td>
    <td>{$row['breed']}</td>
    <td>{$row['shelter']}</td>
    </tr>
_STRING;
        $outputtable = $outputtable . $outputrow;
    $result->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Database manager</title>
		<?php echo $headerImport; ?>
		<link rel="icon" type="image/ico" href="images/favicon.ico">
		<script type="text/javascript">
			$(function() {
				$("a.submitProxy").on("click", function(e) {
					e.preventDefault();
					$(this).closest("form").submit();
				});
			});
		</script>
	</head>
	<body>
		<div data-role="header" class="ui-header ui-bar-inherit">
			<h3 class="ui-title" role="heading">Database Manager</h3>
				<?php echo $nav; ?>
				<?php if ($level > 0) {
    echo $admin_nav;
} ?>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			<?php echo $deletemsg; ?>
            <table data-role="table" id="search_table" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th data-priority="4">Age</th>
                        <th data-priority="2">Breed</th>
                        <th data-priority="3">Shelter</th>
                    </tr>
                </thead>
                <tbody>
					<?php echo $outputtable; ?>
				</tbody>
			</table>
		</div>
	</body>
</html>