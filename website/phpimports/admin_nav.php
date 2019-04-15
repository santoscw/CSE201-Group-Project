<?php

$action = htmlspecialchars($_SERVER["PHP_SELF"]);

//$homeactive = null;
if (!($loggedin)) {
	$login = '<li><a href="login.php" class="ui-btn-icon-left ui-icon-lock" title="Login" data-ajax="false">Login</a></li>';
}

if ($loggedin) {
	$forma = "<form data-form='ui-body-a' id='loginForm' method='post' action='$action' data-ajax='false'><input type='hidden' value='logout' name='logout' />";
	$logout = '<li><a class="submitProxy ui-btn-icon-right ui-icon-minus" data-form="ui-btn-up-a" data-ajax="false">Logout</a></li>';
	$formb = '</form>';
}

$nav = <<<_END
<nav data-role="navbar">
	$forma
	<ul class="MenuBarHorizontal">
		<li><a href="index.php" class="$homeactive" title="Home" data-ajax="false">Home</a></li>
		<li><a href="doglist.php" class="$breedlistactive" data-ajax="false">Breed List</a></li>
		<li><a href="Shelter.html" title="Shelters" class="ui-btn">Shelter</a></li>
		<li><a href="contact.html" title="Contact Us" class="ui-btn">Contact Us</a></li>	
		$login
		$logout
	</ul>
	$formb
</nav>

_END;

$admin_nav = <<<_END
<nav data-role="navbar">
	<ul id="MenuBar1" class="MenuBarHorizontal">
		<li><a href="admin_home.php" class="$ahomeactive" data-ajax="false">Admin Home</a></li>
		<li><a href="comments.php" class="ui-btn-icon-left ui-icon-comment $commentsactive" data-ajax="false">Database Manager</a></li>
	</ul>
</nav>
_END;


?>