<?php
	require_once 'phpimports/header.php';
	$ahomeactive = null;
	$commentsactive = null;
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
	
	if (isset($_POST['name'])) {
		$name = mysql_sanitize_db_input_info($_POST['name']);
		$query  = "SELECT * FROM dog WHERE name LIKE '%$name%' ORDER BY `name` ASC";
	} else {
		$query  = "SELECT * FROM dog ORDER BY `name` ASC";
	}
	$result = queryData($query);
	if (!$result) die($data->error);
	$rows = $result->num_rows;
	$outputtable = null;
	for ($j = 0; $j < $rows; ++$j)
	{
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]); //		<td>{$row['section']}</td>
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
		<header class="main_nav">
		<div class="container">
			<div class="twelve columns">
			<div id="sb-search" class="sb-search">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" data-ajax="false">
					<input type="search" class="sb-search-input" name="name" placeholder="Enter your search term..." id="search" required="">
					<a class="ui-btn ui-input-btn ui-corner-all submitProxy">Search </a>
				</form>
			</div>
			</div>
			</div>
			</header>
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