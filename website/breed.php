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
	if (!isset($_SESSION['initiated']))
	{
		session_regenerate_id();
		$_SESSION['initiated'] = 1;
	}
	if (isset($_SESSION['username']) && isset($_SESSION['level']) && $_SESSION['level'] >= 0)
	{
		$username = $_SESSION['username'];
		$level = $_SESSION['level'];
		$loggedin = TRUE;
	}	
	require_once 'phpimports/admin_nav.php';
	
	if (!isset($_POST['entry'])) {
		header("Location: doglist.php");
	}
	
	$target = $_POST['entry'];
	
	$query  = "SELECT * FROM dog WHERE id = $target";
	$result = queryData($query);
	if (!$result) die($data->error);
	$rows = $result->num_rows;
	$outputtable = null;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]); //		<td>{$row['section']}</td>
		$outputrow = <<<_STRING
		<tr>
		<td>{$row['name']}</td>
		<td><img src="{$row['image']}"></img></td>
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
				<?php if ($level > 0) echo $admin_nav; ?>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			<?php echo $deletemsg; ?>
			<table data-role="table" id="commentsTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
				<thead>
					<tr>
						<th>Name</th>
						<th data-priority="2">Image</th>
						<th data-priority="1">Look at</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $outputtable; ?>
				</tbody>
			</table>
		</div>
	</body>
</html>