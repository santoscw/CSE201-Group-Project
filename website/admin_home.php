<?php
require_once 'phpimports/header.php';

$name2Err = $sectionErr = $countryErr = $imageErr = null;


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
if (isset($_SESSION['username']) && isset($_SESSION['level']) && $_SESSION['level'] > 0) {
	$username = $_SESSION['username'];
	$level = $_SESSION['level'];
	$loggedin = true;
} else {
	header("Location: index.php");
}

$ahomeactive = "ui-btn-active ui-state-persist";
require_once 'phpimports/admin_nav.php';

if (isset($_POST['breed'])) {
	$name_temp = mysql_sanitize_db_input_info($_POST['name2']);
	$section_temp = mysql_sanitize_db_input_info($_POST['section']);
	$country_temp = mysql_sanitize_db_input_info($_POST['country']);
	$image_temp = mysql_sanitize_db_input_info($_POST['image']);
	
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $name_temp == null) {
		$name2Err = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $section_temp == null) {
		$sectionErr = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $country_temp == null) {
		$countryErr = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $image_temp == null) {
		$imageErr = "*Required";
	}
	if ($nameErr == null && $sectionErr == null && $countryErr == null && $imageErr == null) {
		$query = "INSERT INTO breed (name, type, country, image) VALUES ('$name_temp', '$section_temp', '$country_temp', '$image_temp')";
		$result = queryData($query);
		
		if (!$result) {
			die($data->error);
		} else {
			$submitmsg = "<p class='submit'>Breed added.</p>";
		}
	}
}
    
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
		<?php echo $nav; ?>
		<?php echo $admin_nav; ?>
	</header>
	<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
		<div class="container">
		<div data-form="ui-body-a" id="breedDiv" data-theme="a" class="ui-body ui-body-a ui-corner-all">
			<form data-form="ui-body-a" id="breedForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
				<div class="row">
					<div class="container">
						<div class="twelve columns">
							<h2 class="title">Add entry</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="container">
						<div class="twelve columns">
							<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
								<label for="name2">Name: </label>
								<input type="text" name="name2" data-mini="true" autofocus />
								<span class="ui-custom-inherit"><?php echo $name2Err;?></span>
							</div>
							<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
								<label for="section">Section: </label>
								<input type="text" name="section" data-mini="true" />
								<span class="ui-custom-inherit"><?php echo $sectionErr;?></span>
							</div>
							<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
								<label for="country">Country: </label>
								<input type="text" name="country" data-mini="true" />
								<span class="ui-custom-inherit"><?php echo $countryErr;?></span>
							</div>
							<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
								<label for="image">Image Link: </label>
								<input type="text" name="image" data-mini="true" />
								<span class="ui-custom-inherit"><?php echo $imageErr;?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="container">
						<div class="twelve columns">
							<input type="hidden" name="breed" value="yes" />
							<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all submitProxy" data-form="ui-btn-up-a">Submit</a>
							<?php echo $submitmsg; ?>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	</div>
</body>
</html>			