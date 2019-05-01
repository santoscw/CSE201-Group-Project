<?php
    require_once 'phpimports/header.php';
    $commentsactive = "ui-btn-active ui-state-persist";
    
    session_start();
    $username = null;
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
    if (isset($_SESSION['username']) && isset($_SESSION['level']) && $_SESSION['level'] > 1) {
        $username = $_SESSION['username'];
        $level = $_SESSION['level'];
        $loggedin = true;
    } else {
        header("Location: index.php");
    }
    
    require_once 'phpimports/admin_nav.php';
    
    if (isset($_POST['admin'])) {
        $admin_target = $_POST['admin'];
        $query = "UPDATE `user` SET `level` = '2' WHERE `user`.`uid` = $admin_target";
        $result = queryData($query);
        if (!$result) {
            die($connection->error);
        } else {
            $adminmsg = "Successfully updated.";
        }
    } else if (isset($_POST['mod'])) {
        $mod_target = $_POST['mod'];
        $query = "UPDATE `user` SET `level` = '1' WHERE `user`.`uid` = $mod_target";
        $result = queryData($query);
        if (!$result) {
            die($connection->error);
        } else {
            $adminmsg = "Successfully updated.";
        }
    }
    $query  = "SELECT `uid`, `username`, `email`, `level` FROM user ORDER BY `username` ASC";
    $result = queryData($query);
    if (!$result) {
        die($connection->error);
    }
    $rows = $result->num_rows;
    $outputtable = $popuplist = null;
    for ($j = 0; $j < $rows; ++$j) {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
		$documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
		if ($row['level'] < 1) {
			$addmod = "<td><a href='#mod{$row['uid']}' data-rel='popup' data-role='button' data-transition='pop' class='ui-btn ui-btn-icon-right ui-icon-power'>MAKE MOD</a></td>";
		} else {
			$addmod = "<td>This user already has mod privileges!</td>";
		}
		if ($row['level'] < 2)
			$addadmin = "<td><a href='#admin{$row['uid']}' data-rel='popup' data-role='button' data-transition='pop' class='ui-btn ui-btn-icon-right ui-icon-power'>MAKE ADMIN</a></td>";
		else
			$addadmin = "<td>This user already has admin privileges!</td>";
        $outputrow = <<<_STRING
		<tr>
		<td>{$row['username']}</td>
		<td>{$row['email']}</td>
		<td>{$row['level']}</td>
		$addmod
		$addadmin
		</tr>
_STRING;
		if ($row['level'] < 1) {
			$popupentry = <<<_POPUP
		<div data-role="popup" id="mod{$row['uid']}" data-theme="a" class="ui-corner-all ui-popup ui-body-a">
		<div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
		<h1 class="ui-title">Are you sure?</h1>
		</div>
		<div class="ui-corner-bottome ui-content">
		<form data-form="ui-body-a" method="post" action="$documentfunc" data-ajax="false">
		<h3 class="ui-title">Are you sure you want to alter these permissions?</h3>
		<p>This will allow this user to have more control on this site.</p>
		<p>This can only be undone on PHPMyAdmin, or in the raw MySQL!</p>
		<p>DO NOT TOUCH IF YOU DON'T KNOW WHAT YOU'RE DOING</p>
		<a href="#" data-role="button" data-rel="back" data-theme="a" class="ui-btn ui-btn-icon-right ui-icon-back ui-corner-all">Cancel</a>
		<a href="#" data-role="button" class="ui-btn ui-btn-icon-right ui-icon-power ui-corner-all submitProxy"><b>Update</b></a>
		<input type="hidden" value="{$row['uid']}" name="mod" />
		</form>
		</div>
		</div>
_POPUP;
		}
        if ($row['level'] < 2) {
            $popupentry = $popupentry . <<<_POPUP
		<div data-role="popup" id="admin{$row['uid']}" data-theme="a" class="ui-corner-all ui-popup ui-body-a">
		<div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
		<h1 class="ui-title">Are you sure?</h1>
		</div>
		<div class="ui-corner-bottome ui-content">
		<form data-form="ui-body-a" method="post" action="$documentfunc" data-ajax="false">
		<h3 class="ui-title">Are you sure you want to alter these permissions?</h3>
		<p>This will allow this user to have more control on this site.</p>
		<p>This can only be undone on PHPMyAdmin, or in the raw MySQL!</p>
		<p>DO NOT TOUCH IF YOU DON'T KNOW WHAT YOU'RE DOING</p>
		<a href="#" data-role="button" data-rel="back" data-theme="a" class="ui-btn ui-btn-icon-right ui-icon-back ui-corner-all">Cancel</a>
		<a href="#" data-role="button" class="ui-btn ui-btn-icon-right ui-icon-power ui-corner-all submitProxy"><b>Update</b></a>
		<input type="hidden" value="{$row['uid']}" name="admin" />
		</form>
		</div>
		</div>
_POPUP;
        }
        $outputtable = $outputtable . $outputrow;
        $popuplist = $popuplist . $popupentry;
    }
    $result->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>User manager</title>
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
			<h3 class="ui-title" role="heading">User Manager</h3>
				<?php echo $nav; ?>
				<?php echo $admin_nav; ?>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			<?php echo $deletemsg; ?>
			<table data-role="table" id="commentsTable" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
				<thead>
					<tr>
						<th>Username</th>
						<th data-priority="2">Email</th>
						<th data-priority="1">level</th>
						<th data-priority="5">MAKE MODERATOR</th>
						<th data-priority="6">MAKE ADMIN</th>
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