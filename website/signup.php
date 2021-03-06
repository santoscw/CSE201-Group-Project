<?php
    require_once 'phpimports/header.php';
    
	$un_temp = $usernameErr = $passwordErr = $emailErr = $error = null;
	
	$registeractive = "ui-btn-active ui-state-persist";

	require_once 'phpimports/admin_nav.php';

    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['password'] == $_POST['repassword']) {
            $un_temp = mysql_sanitize_db_input_info($_POST['username']);
            $email_temp = mysql_sanitize_db_input_info($_POST['email']);
            $token = password_hash(mysql_sanitize_password($_POST['password']), PASSWORD_DEFAULT);
            
            $query  = "SELECT * FROM user WHERE username='$un_temp'";
            $result = queryData($query);
            if (!$result) {
                die($data->error);
            } elseif ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_NUM);
                
                if ($row[1] == $un_temp) {
                    $usernameErr = "Username already taken.";
                    $error = null;
                }
            } else {
                //			else $error = "<li data-form='ui-body-a'><pre class='ui-custom-inherit'>Username or password is incorrect</pre></li>";
                if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['username'] == null) {
                    $usernameErr = "*Required";
                    $error = null;
                }
                if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['password'] == null) {
                    $passwordErr = "*Required";
                    $error = null;
                }
                if ($_SERVER['REQUEST_METHOD'] == "POST" && $email_temp == null) {
                    $emailErr = "*Required";
                } elseif (!filter_var($email_temp, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid address";
                }
            
                if ($usernameErr == null && $passwordErr == null && $emailErr == null && $error == null) {
                    $query = "INSERT INTO user (username, email, password) VALUES('$un_temp', '$email_temp', '$token')";
                    $result = queryData($query);
                    if (!$result) {
                        die($data->error);
					}
					session_start();
					$_SESSION['username'] = $un_temp;
					$_SESSION['level'] = $row[4];
					$_SESSION['userid'] = $row[0];
					header("Location: index.php");
				}
            }
        } else {
            $passwordErr = "Passwords do not match";
            $error = null;
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>The Dog-alogue: Register</title>
		<?php echo $headerImport; ?>
		<script type="text/javascript">
			$(function() {
				$("a#submitProxy").on("click", function(e) {
					e.preventDefault();
					$(this).closest("form").submit();
				});
				$("#registerForm").keydown(function(e) {
					if(e.keyCode !=13) return;
					$(this).submit();
				});
			});
			
		</script>
		
	</head>
	<body>
		<div id="registerPage" data-role="page" data-theme="a" class="ui-page-header-fixed ui-page-footer-fixed" data-title="Register">
			<header data-role='header' data-theme="a" data-position="fixed" data-id="header" data-tap-toggle="false">
				<h1 class="ui-title" data-role="heading">Register</h1>
				<?php echo $nav; ?>
			</header>
			<div id="mainArea" class="ui-content" data-form="ui-page-theme-a">
			<div class="container">
				<form data-form="ui-body-a" id="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
					<ul data-role="listview" data-inset="true" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" id="loginBox">
						<li data-role="list-divider" data-form="ui-bar-a" role="heading" class="ui-li-divider ui-bar-inherit ui-first-child">
							<span>Register</span>
						</li>
						<li data-form="ui-body-a" class="ui-li-static ui-field-contain">
							<label for="username">Username: </label>
							<input type="text" name="username" data-mini="true" autofocus />
							<span class="ui-custom-inherit"><?php echo $usernameErr;?></span>
						</li>
						<li data-form="ui-body-a" class="ui-li-static ui-field-contain">
							<label for="password">Password: </label>
							<input type="password" name="password" data-mini="true" />
						</li>
						<li data-form="ui-body-a" class="ui-li-static ui-field-contain">
							<label for="repassword">Retype password: </label>
							<input type="password" name="repassword" data-mini="true" />
							<span class="ui-custom-inherit"><?php echo $passwordErr;?></span>
						</li>
						<li data-form="ui-body-a" class="ui-li-static ui-field-contain">
							<label for="email">Email: </label>
							<input type="email" name="email" data-mini="true" />
							<span class="ui-custom-inherit"><?php echo $emailErr;?></span>
						</li>

						<?php echo $error;?>
						<li class="ui-last-child">
							<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-lock" data-form="ui-btn-up-a" id="submitProxy">Register</a>
						</li>
					</ul>
				</form>
			</div>
			</div>
		</div>
	</body>
</html>