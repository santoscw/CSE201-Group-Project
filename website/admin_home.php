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

if (isset($_POST['breedadd'])) {
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
	if ($name2Err == null && $sectionErr == null && $countryErr == null && $imageErr == null) {
		$query = "INSERT INTO breed (name, type, country, image) VALUES ('$name_temp', '$section_temp', '$country_temp', '$image_temp')";
		$result = queryData($query);
		
		if (!$result) {
			die($data->error);
		} else {
			$submitmsg = "<p class='submit'>Breed added.</p>";
		}
	}
}

if (isset($_POST['dogremove'])) {
	$remtarget = $_POST['dogremove'];
	$query = "DELETE FROM `dog_req` WHERE id = $remtarget";
	$result = queryData($query);
	if (!$result) {
		die($data->error);
	}
}

if (isset($_POST['dogadd']) ) {
	if ($_POST['dogadd'] != "yes") {
		$reqtarget = $_POST['dogadd'];
		$query = "DELETE FROM `dog_req` WHERE id = $reqtarget";
		$result = queryData($query);
		if (!$result) {
			die($data->error);
		}
	}
	$name_temp = mysql_sanitize_db_input_info($_POST['name3']);
	$breed_temp = (int)mysql_sanitize_db_input_info($_POST['breed']);
	$shelter_temp = (int)mysql_sanitize_db_input_info($_POST['shelter']);
	$age_temp = (int)mysql_sanitize_db_input_info($_POST['age']);
	$image_temp = mysql_sanitize_db_input_info($_POST['image2']);
	
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
		$query = "INSERT INTO dog (name, breed_id, shelter_id, age, img, active) VALUES ('$name_temp', '$breed_temp', '$shelter_temp', '$age_temp', '$image_temp', 1)";
		$result = queryData($query);
		
		if (!$result) {
			die($data->error);
		} else {
			$submitmsg = "<p class='submit'>Dog added.</p>";
		}
	}
}

$query = <<<_STRING
	SELECT 
		t1.id AS id, 
		t2.username AS username,
		t3.name AS sheltername,
		t3.id AS shelterid,
		t4.name AS breedname,
		t4.id AS breedid,
		t1.name AS name,
		t1.age AS age,
		t1.img AS img
	FROM `dog_req` AS t1
	LEFT JOIN `user` AS t2 ON t1.user_id = t2.uid
	LEFT JOIN `shelter` AS t3 ON t1.shelter_id = t3.id
	LEFT JOIN `breed` AS t4 ON t1.breed_id = t4.id
	ORDER BY id ASC
_STRING;
$result = queryData($query);
if (!result) {
	echo "MySQL Query error. Will be rectified shortly. \n" . $data->error;
} else {
	$rows = $result->num_rows;
	$documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
	for ($j = 0; $j < $rows; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$dogrow = <<<_STRING
		<div data-role="collapsible">
			<h4>{$row['name']} requested by {$row['username']}</h4>
			<div class="row">
				<div class="four columns">
					<p>Name: {$row['name']}</p>
				</div>
				<div class="four columns">
					<p>Age: {$row['age']}</p>
				</div>
				<div class="four columns">
					<img src="{$row['img']}" />
				</div>
			</div>
			<div class="row">
				<div class="four columns">
					<p>Breed: {$row['breedname']}</p>
				</div>
				<div class="four columns">
					<p>Shelter: {$row['sheltername']}</p>
				</div>
				<div class="four columns">
					<p>Req'd by: {$row['username']}</p>
				</div>
			</div>
			<div class="row">
			<div class="six columns">
			<form action="$documentfunc" method="post" data-ajax="false">
				<input type="hidden" name="dogadd" value="{$row['id']}" />
				<input type="hidden" name="name3" value="{$row['name']}" />
				<input type="hidden" name="breed" value="{$row['breedid']}" />
				<input type="hidden" name="shelter" value="{$row['shelterid']}" />
				<input type="hidden" name="age" value="{$row['age']}" />
				<input type="hidden" name="image2" value="{$row['img']}" />
				<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-check ui-corner-all submitProxy" data-form="ui-btn-up-a">Approve</a>
			</form>
			</div>
			<div class="six columns">
			<form action="$documentfunc" method="post" data-ajax="false">
			<input type="hidden" name="dogremove" value="{$row['id']}" />
			<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-delete ui-corner-all submitProxy" data-form="ui-btn-up-a">Deny</a>
			</form>
			</div>
			</div>
		</div>
_STRING;
		$dogtable = $dogtable . $dogrow;
	}
}
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Entries</title>
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
		<h3 class="ui-title" role="heading">Add Entries</h3>
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
								<h2 class="title">Add Breed</h2>
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
								<input type="hidden" name="breedadd" value="yes" />
								<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all submitProxy" data-form="ui-btn-up-a">Submit</a>
								<?php echo $submitmsg; ?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="container">
			<div data-form="ui-body-a" id="dogDiv" data-theme="a" class="ui-body ui-body-a ui-corner-all">
				<form data-form="ui-body-a" id="dogForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-ajax="false">
					<div class="row">
						<div class="container">
							<div class="twelve columns">
								<h2 class="title">Add New Dog</h2>
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