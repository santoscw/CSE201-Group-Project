<?php
	require_once 'phpimports/header.php';
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
	if (isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];
	}
	else header("Location: login.php");
	if (isset($_POST['delete'])) 
	{
		$delete_target = $_POST['delete'];
		$query = "DELETE FROM comments WHERE `uid`='$delete_target'";
		$result = queryMysql($query);
		if (!$result) die($connection->error);
		else $deletemsg = "Successfully deleted.";
	}
	$query  = "SELECT * FROM comments ORDER BY `uid` ASC";
	$result = queryMysql($query);
	if (!$result) die($connection->error);
	$rows = $result->num_rows;
	$outputtable = $popuplist = null;
	for ($j = 0; $j < $rows; ++$j)
	{
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
		$outputrow = <<<_STRING
		<tr>
		<td>{$row['name']}</td>
		<td>{$row['email']}</td>
		<td>{$row['comment']}</td>
		<td>{$row['uid']}</td>
		<td>
		<a href="#confirm{$row['uid']}" data-rel="popup" data-role="button" data-transition="pop" class="ui-btn ui-btn-icon-right ui-icon-delete">DELETE</a>
		</td>
		</tr>
_STRING;
		$popupentry = <<<_POPUP
		<div data-role="popup" id="confirm{$row['uid']}" data-theme="a" class="ui-corner-all ui-popup ui-body-a">
		<div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
		<h1 class="ui-title">Are you sure?</h1>
		</div>
		<div class="ui-corner-bottome ui-content">
		<form data-form="ui-body-a" method="post" action="$documentfunc" data-ajax="false">
		<h3 class="ui-title">Are you sure you want to delete this comment?</h3>
		<p>This CANNOT be undone.</p>
		<a href="#" data-role="button" data-rel="back" data-theme="a" class="ui-btn ui-btn-icon-right ui-icon-back ui-corner-all">Cancel</a>
		<a href="#" data-role="button" class="ui-btn ui-btn-icon-right ui-icon-delete ui-corner-all submitProxy"><b>DELETE</b></a>
		<input type="hidden" value="{$row['uid']}" name="delete" />
		</form>
		</div>
		</div>
_POPUP;
		$outputtable = $outputtable . $outputrow;
		$popuplist = $popuplist . $popupentry;
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
			<nav data-role="navbar" id="contentnav">
				<form data-form="ui-body-a" id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
					<ul id="MenuBar1" class="MenuBarHorizontal">
						<li><a href="admin_home.php" data-ajax="false">Admin Home</a></li>
						
						<li><a href="comments.php" class="ui-btn-icon-left ui-icon-comment ui-btn-active ui-state-persist"  data-ajax="false">Database Manager</a></li>
						<li><a class="submitProxy ui-btn-icon-right ui-icon-minus" data-form="ui-btn-up-a" data-ajax="false">Logout</a></li>
					</ul>
					<input type="hidden" value="logout" name="logout" />
				</form>
			</nav>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			<?php echo $deletemsg; ?>
			<table data-role="table" id="commentsTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
				<thead>
					<tr>
						<th>Name</th>
						<th data-priority="2">Email</th>
						<th data-priority="1">Comment</th>
						<th data-priority="3">ID</th>
						<th data-priority="6">DELETE</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $outputtable; ?>
				</tbody>
			</table>
			<?php echo $popuplist; ?>
		</div>
	</body>
</html>
