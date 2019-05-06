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
        t1.id AS id,
        t1.name AS name,
        t1.address AS address,
        t1.phone AS phone,
        t1.email AS email,
        t2.city AS city
    FROM `shelter` AS t1
    LEFT JOIN `city` AS t2 ON t1.city_id = t2.id
    WHERE t1.id = '$target'
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
            <td>{$row['address']}</td>
            <td>{$row['city']}</td>
			<td>{$row['phone']}</td>
			<td>{$row['email']}</td>
		</tr>
_STRING;
		$outputtable = $outputtable . $outputrow;
		$shelter_name = $row['name'];
    $result->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>The Dog-alogue: <?php echo $shelter_name; ?></title>
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
			<h3 class="ui-title" role="heading">Shelter: <?php echo $shelter_name; ?></h3>
				<?php echo $nav; ?>
				<?php if ($level > 1) {
    echo $admin_nav;
} ?>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			<?php echo $deletemsg; ?>
			<table data-role="table" id="commentsTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
				<thead>
					<tr>
						<th>Name</th>
						<th>Address</th>
						<th>City</th>
						<th>Phone</th>
                        <th>Email</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $outputtable; ?>
				</tbody>
			</table>
		</div>
	</body>
</html>