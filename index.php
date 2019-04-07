<?php
	require_once 'phpimports/header.php';
	
	$nameErr = $emailErr = $commentsErr = $submitmsg = $error = null;
	session_start();
	if (isset($_SESSION['initiated'])) {
		session_unset();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000);
		}
	    session_destroy();
		exit();
	}

	$response = null;
	if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['comments'])) 
	{
		$name_temp = mysql_sanitize_db_input_info($_POST['name']);
		$email_temp = mysql_sanitize_db_input_info($_POST['email']);
		$comment_temp = mysql_sanitize_db_input_info($_POST['comments']);
		
		if ($_SERVER['REQUEST_METHOD'] == "POST" && $name_temp == null)
		{
			$nameErr = "*Required";
		}
		if ($_SERVER['REQUEST_METHOD'] == "POST" && $email_temp == null)
		{
			$emailErr = "*Required";
		}
		elseif (!filter_var($email_temp, FILTER_VALIDATE_EMAIL)) 
		{
			$emailErr = "Invalid address";
		}
		if ($_SERVER['REQUEST_METHOD'] == "POST" && $comment_temp == null)
		{
			$commentsErr = "*Required";
		}
		if ($nameErr == null && $emailErr == null && $commentsErr == null ) {
			
			$query = "INSERT INTO comments (name, email, comment) VALUES ('$name_temp', '$email_temp', '$comment_temp')";
			$result = queryMysql($query);
			
			if (!$result) die($connection->error);
			else
			{
				$submitmsg = "<p class='submit'>Thanks for the comment!</p>";
			}
		}
	}
	closeConnection();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TEST</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php echo $headerImport; ?>
		<script type="text/javascript">
			$(function() {
				$("[data-role='navbar']").navbar();
				$("[data-role='header']").toolbar();
				$("[data-role='popup']").enhanceWithin().popup();
				$("a#submitProxy").on("click", function(e) {
					e.preventDefault();
					$(this).closest("form").submit();
				});
			});
		</script>
			</head>
	<body>
		<header class="main_nav gray">
			<div class="container">
				<div class="twelve columns">
					<div class="logo">
						<p class="logomain">Project<sub class="sub">.com</sub></p>
					</div>
					<nav data-role="navbar">
						<ul>
							<li><a href="#" class="ui-btn-active ui-state-persist" title="Home" data-ajax="false">Home</a></li>
							<li><a href="/login.php" class="ui-btn-icon-left ui-icon-lock" title="Login" data-ajax="false">Login</a></li>						
						</ul>
					</nav>
				</div>
			</div>
		</header>
		<div class="container">
			<div data-form="ui-body-a" id="contactSection" data-theme="a" class="ui-body ui-body-a ui-corner-all">
				<form data-form="ui-body-a" id="contactForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
					<div class="row">
						<div class="container">
							<div class="twelve columns">
								<h2 class="title">Database Test</h2>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="container">
							<div class="twelve columns">
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<label for="name">Name: </label>
									<input type="text" name="name" data-mini="true" autofocus />
									<span class="ui-custom-inherit"><?php echo $nameErr;?></span>
								</div>
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<label for="email">Email: </label>
									<input type="email" name="email" data-mini="true" />
									<span class="ui-custom-inherit"><?php echo $emailErr;?></span>
								</div>
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<label for="comments">Comment: <span class="ui-custom-inherit"><br><?php echo $commentsErr;?></span></label>
									<textarea name="comments" rows="6" data-autogrow="false"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="container">
							<div class="twelve columns">
								<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all" data-form="ui-btn-up-a" id="submitProxy">Submit</a>
								<?php
									if ($response != null && $response->success) {
										echo $submitmsg;
									}
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<footer data-role="footer">
			<div class="row">
				<div class="container">
					<div class="row twelve columns">
					<p class="copyright">&copy; 2017 Benjamin Arehart. All rights reserved.</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
