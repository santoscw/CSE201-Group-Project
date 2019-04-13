<?php
	require_once 'phpimports/header.php';
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
	/*
	function upload() {
		if ($_POST['title'] == null || !isset($_POST['gallery'])) {
			$msg = "<p>Please fill out all fields and selections.</p>";
		}
		else {
			$maxsize = 10000000; 
			if($_FILES['userfile']['error']==UPLOAD_ERR_OK) {
				if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {    
					if( $_FILES['userfile']['size'] < $maxsize) {  
						$finfo = finfo_open(FILEINFO_MIME_TYPE);
						if(strpos(finfo_file($finfo, $_FILES['userfile']['tmp_name']),"image")===0) {    
							$imgData = addslashes(file_get_contents($_FILES['userfile']['tmp_name']));
							$title = mysql_sanitize_db_input_info($_POST['title']);
							if ($_POST['gallery'] == "bracelets") {
								$gallery = "bracelets";
							}
							else if ($_POST['gallery'] == "necklaces") {
								$gallery = "necklaces";
							}
							else if ($_POST['gallery'] == "pendants") {
								$gallery = "pendants";
							}							
							$query = "INSERT INTO $gallery (name, image) VALUES ('$title', '$imgData');";
							$result = queryMysql($query);
							if (!$result) die($connection->error);
							else {
								$msg='<p>Image successfully saved in database</p>';
							}
						}
						else $msg="<p>Uploaded file is not an image.</p>";
					}
					else {
						$msg='<div>File exceeds the Maximum File limit</div>
						<div>Maximum File limit is '.$maxsize.' bytes</div>
						<div>File '.$_FILES['userfile']['name'].' is '.$_FILES['userfile']['size'].
						' bytes</div><hr />';
					}
				}
				else
				$msg="File not uploaded successfully.";
			}
			else {
				$msg= file_upload_error_message($_FILES['userfile']['error']);
			}
		}
		return $msg;
	}
	function file_upload_error_message($error_code) {
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
			return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case UPLOAD_ERR_FORM_SIZE:
			return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case UPLOAD_ERR_PARTIAL:
			return 'The uploaded file was only partially uploaded';
			case UPLOAD_ERR_NO_FILE:
			return 'No file was uploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
			return 'Missing a temporary folder';
			case UPLOAD_ERR_CANT_WRITE:
			return 'Failed to write file to disk';
			case UPLOAD_ERR_EXTENSION:
			return 'File upload stopped by extension';
			default:
			return 'Unknown upload error';
		}
	}
	*/
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Administrator Homepage</title>
		<?php echo $headerImport; ?>
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
		<header data-role="header" class="ui-header ui-bar-inherit">
			<h3 class="ui-title" role="heading">Admin Home</h3>
			<nav data-role="navbar" id="contentnav">
				<form data-form="ui-body-a" id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
					<ul id="MenuBar1" class="MenuBarHorizontal">
						<li><a href="admin_home.php" class="ui-btn-active ui-state-persist" data-ajax="false">Admin Home</a></li>
						<li><a href="comments.php" class="ui-btn-icon-left ui-icon-comment" data-ajax="false">Database Manager</a></li>
						<li><a class="submitProxy ui-btn-icon-right ui-icon-minus" data-form="ui-btn-up-a" data-ajax="false">Logout</a></li>
					</ul>
					<input type="hidden" value="logout" name="logout" />
				</form>
			</nav>
		</header>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			Login successful!
		</div>
	</body>
</html>			
