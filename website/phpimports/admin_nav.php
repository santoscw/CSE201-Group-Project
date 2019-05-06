<?php

$action = htmlspecialchars($_SERVER["PHP_SELF"]);

//$homeactive = null;
if (!($loggedin)) {
	$login = "<li><a href='login.php' class='ui-btn-icon-left ui-icon-lock $loginactive' title='Login' data-ajax='false'>Login</a></li>";
	$login = $login . "<li><a href='signup.php' class='ui-btn-icon-left ui-icon-user $registeractive' title='Register' data-ajax='false'>Register</a></li>";

}

if ($loggedin) {
	$request = "<li><a href='request.php' class='ui-btn-icon-left ui-icon-plus $requestactive' data-ajax='false'>Request Entry</a></li>";
	$forma = "<form data-form='ui-body-a' id='loginForm' method='post' action='$action' data-ajax='false'><input type='hidden' value='logout' name='logout' />";
	$logout = '<li><a class="submitProxy ui-btn-icon-left ui-icon-minus" data-form="ui-btn-up-a" data-ajax="false">Logout</a></li>';
	$formb = '</form>';
}

$nav = <<<_END
<nav data-role="navbar">
	$forma
	<ul class="MenuBarHorizontal">
		<li><a href="index.php" class="ui-btn-icon-left ui-icon-home $homeactive" title="Home" data-ajax="false">Home</a></li>
		<li><a href="results.php" class="ui-btn-icon-left ui-icon-search $breedlistactive" data-ajax="false">Search</a></li>
		$login
		$request
		$logout
	</ul>
	$formb
</nav>

_END;

$admin_nav = <<<_END
<nav data-role="navbar">
	<ul id="MenuBar1" class="MenuBarHorizontal">
		<li><a href="admin_home.php" class="$ahomeactive ui-btn-icon-left ui-icon-plus" data-ajax="false">Add Entries</a></li>
		<li><a href="users.php" class="ui-btn-icon-left ui-icon-user $commentsactive" data-ajax="false">User Management</a></li>
	</ul>
</nav>
_END;


?>
