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
if (isset($_SESSION['username']) && isset($_SESSION['level'])) {
	$username = $_SESSION['username'];
    $level = $_SESSION['level'];
    $userid = $_SESSION['userid'];
	$loggedin = true;
} else {
	header("Location: index.php");
}

$requestactive = "ui-btn-active ui-state-persist";
require_once 'phpimports/admin_nav.php';

$breedlist = $shelterlist = $dogtable = null;
$query = <<<_STRING
	SELECT DISTINCT name, id FROM `breed` ORDER BY name ASC
_STRING;
$result = queryData($query);
if (!result) {
	echo "MySQL Query error. Will be rectified shortly. \n" . $data->error;
} else {
	$rows = $result->num_rows;
	for ($j = 0; $j < $rows; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$breedopt = <<<_STRING
		<option value="{$row['id']}">{$row['name']}</option>
_STRING;
		$breedlist = $breedlist . $breedopt;
	}
}

$query = <<<_STRING
	SELECT DISTINCT name, id FROM `shelter` ORDER BY name ASC
_STRING;
$result = queryData($query);
if (!result) {
	echo "MySQL Query error. Will be rectified shortly. \n" . $data->error;
} else {
	$rows = $result->num_rows;
	for ($j = 0; $j < $rows; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$shelteropt = <<<_STRING
		<option value="{$row['id']}">{$row['name']}</option>
_STRING;
		$shelterlist = $shelterlist . $shelteropt;
	}
}

if (isset($_POST['dogadd']) ) {
	$name_temp = mysql_sanitize_db_input_info($_POST['name3']);
	$breed_temp = (int)mysql_sanitize_db_input_info($_POST['breed']);
	$shelter_temp = (int)mysql_sanitize_db_input_info($_POST['shelter']);
	$age_temp = (int)mysql_sanitize_db_input_info($_POST['age']);
    $image_temp = mysql_sanitize_db_input_info($_POST['image2']);
    $userid_temp = mysql_sanitize_db_input_info($_POST['userid']);
	
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $name_temp == null) {
		$name3Err = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $breed_temp == null) {
		$breedErr = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $shelter_temp == null) {
		$shelterErr = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $age_temp == null) {
		$ageErr = "*Required";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST" && $image_temp == null) {
		$image2Err = "*Required";
	}
	if ($name3Err == null && $breedErr == null && $shelterErr == null && $ageErr == null && $imageErr == null) {
		$query = "INSERT INTO `dog_req` (name, breed_id, shelter_id, age, img, user_id) VALUES ('$name_temp', '$breed_temp', '$shelter_temp', '$age_temp', '$image_temp', $userid_temp)";
		$result = queryData($query);
		
		if (!$result) {
			die($data->error);
		} else {
			$submitmsg = "<p class='submit'>Dog added.</p>";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Request Entries</title>
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
	<div data-role="header" class="ui-header ui-bar-inherit">
		<h3 class="ui-title" role="heading">Request Entries</h3>
        <?php echo $nav; ?>
        <?php if ($level > 1) {
            echo $admin_nav;
        } ?>
	</div>
	<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
		<div class="container">
			<div data-form="ui-body-a" id="dogReqDiv" data-theme="a" class="ui-body ui-body-a ui-corner-all">
				<form data-form="ui-body-a" id="dogForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
					<div class="row">
						<div class="container">
							<div class="twelve columns">
								<h2 class="title">Request New Dog</h2>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="container">
							<div class="twelve columns">
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<label for="name3">Name: </label>
									<input type="text" name="name3" data-mini="true" autofocus />
									<span class="ui-custom-inherit"><?php echo $name3Err;?></span>
								</div>
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<div class="ui-field-contain">
										<label for="breed">Breed: </label>
										<select name="breed" data-native-menu="false">
											<option value='' data-placeholder='true'>Choose...</option>
											<?php echo $breedlist; ?>
										</select>
									</div>
									<span class="ui-custom-inherit"><?php echo $breedErr;?></span>
								</div>
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<div class="ui-field-contain">
										<label for="shelter">Shelter: </label>
										<select name="shelter" data-native-menu="false">
											<option value='' data-placeholder='true'>Choose...</option>
											<?php echo $shelterlist; ?>
										</select>
									</div>
									<span class="ui-custom-inherit"><?php echo $shelterErr;?></span>
								</div>
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<label for="age">Age: </label>
									<input type="range" name="age" value="0" min="0" max="20" step="1" data-highlight="true" />
									<span class="ui-custom-inherit"><?php echo $ageErr;?></span>
								</div>
								<div data-form="ui-body-a" class="ui-li-static ui-field-contain">
									<label for="image2">Image Link: </label>
									<input type="text" name="image2" data-mini="true" />
									<span class="ui-custom-inherit"><?php echo $image2Err;?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="container">
							<div class="twelve columns">
                                <input type="hidden" name="dogadd" value="yes" />
                                <input type="hidden" name="userid" value="<?php echo $userid; ?>" />
								<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all submitProxy" data-form="ui-btn-up-a">Submit</a>
								<?php echo $submitmsg; ?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="container">
			<?php echo $dogtable; ?>
		</div>
	</div>
</body>
</html>
