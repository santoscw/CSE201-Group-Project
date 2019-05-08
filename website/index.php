<?php
    require_once 'phpimports/header.php';
    
    $level = $username = $loggedin = null;

    // session code
    session_start();
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
        $loggedin = true;
    }
    // -- session code

		$homeactive = "ui-btn-active ui-state-persist";
    require_once 'phpimports/admin_nav.php';

   

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>The Dog-alogue: Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php echo $headerImport; ?>
		<script type="text/javascript">
			$(function() {
				$("[data-role='navbar']").navbar();
				$("[data-role='header']").toolbar();
				$("[data-role='popup']").enhanceWithin().popup();
				$("a.submitProxy").on("click", function(e) {
					e.preventDefault();
					$(this).closest("form").submit();
				});
				$("#column").selectmenu("disable");
				$("#db").change(function() {
					var value = $(this).val();
					if(value == "") {
						$("#column").html("<option value='' data-placeholder='true'>Choose...</option>");
					} else {
						if (value == "dog") {
							$("#column").html("<option value='' data-placeholder='true'>Choose...</option><option value='name'>Name</option><option value='breed'>Breed</option><option value='shelter'>Shelter</option>");
						} else if (value == "breed") {
							$("#column").html("<option value='' data-placeholder='true'>Choose...</option><option value='name'>Name</option><option value='type'>Type</option>");
						} else if (value == "shelter") {
							$("#column").html("<option value='' data-placeholder='true'>Choose...</option><option value='name'>Name</option><option value='city'>City</option>");
						}
						$("#column").selectmenu("enable");
					}
					$("#column").selectmenu("refresh");
				});
			});
			
		</script>

	</head>
	<body>
		<header class="main_nav gray">
			<div class="container">
				<div class="twelve columns">
					<div class="logo">
						<p class="logomain">The Dog-alogue</p>
					</div>
					<?php echo $nav; ?>
					<?php if ($level > 1) { echo $admin_nav;} ?>
				</div>
			</div>
		</header>
		<div class="container">
		<div data-role="collapsible" data-collapsed-icon="search" data-expanded-icon="search">
			<h4>Search</h4>
			<form action="results.php" method="post" data-ajax="false">
				<div class="container">
					<div class="six columns">
						<div class="ui-field-contain">
						<label for="db" class="select">Search for:</label>
						<select name="db" id="db" data-native-menu="false" required="required">
							<option value="" data-placeholder="true">Choose...</option>
							<option value="dog">Dog</option>
							<option value="breed">Breed</option>
							<option value="shelter">Shelter</option>
						</select>
						</div>
					</div>
					<div class="six columns" >
						<div class="ui-field-contain">
						<label for="column">Search by:</label>
						<select name='column' id="column" data-native-menu="false" required>
							<option value='' data-placeholder='true'>Choose...</option>
						</select>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="nine columns">
						<div class="ui-field-contain">
						<label for="name">Search Term:</label>
						<input type="search" class="sb-search-input" name="name" placeholder="Enter your search term..." id="search" >
					</div>
					</div>
					<div class="three columns">
						<input type="submit" data-icon="search" class="ui-btn ui-input-btn ui-corner-all submitProxy" value="Search" />
					</div>
				</div>
			</form>
		</div>
		</div>
		<div class="banner">
			<div class="container">
				<div data-form="ui-body-a" data-theme="a" class="ui-body ui-body-a ui-corner-all">
					<div class="four columns">
						<form action="results.php" method="post" data-ajax="false">
							<input type="hidden" name="db" value="breed" />
							<input type="hidden" name="column" value="name" />
							<input type="hidden" name="name" value="" />
							<a class="ui-btn ui-input-btn ui-corner-all submitProxy" data-form="ui-btn-up-a">Look at Breeds!</a>
						</form>
					</div>
					<div class="four columns">
						<form action="results.php" method="post" data-ajax="false">
							<input type="hidden" name="db" value="dog" />
							<input type="hidden" name="column" value="name" />
							<input type="hidden" name="name" value="" />
							<a class="ui-btn ui-input-btn ui-corner-all submitProxy" data-form="ui-btn-up-a">Look at Dogs!</a>
						</form>
					</div>
					<div class="four columns">
						<form action="results.php" method="post" data-ajax="false">
							<input type="hidden" name="db" value="shelter" />
							<input type="hidden" name="column" value="name" />
							<input type="hidden" name="name" value="" />
							<a class="ui-btn ui-input-btn ui-corner-all submitProxy" data-form="ui-btn-up-a">Look at Shelters!</a>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div data-form="ui-body-a" data-theme="a" class="ui-body ui-body-a ui-corner-all">
					<div class="row twelve columns">
						<h3> Our Mission </h3>
						<p style="margin-bottom: 0">We are surrounded by many kinds of dogs every day, but do you really understand them? Do you know their breed? Their history, 
						habits, bloodlines, etc., each is their best description. Our website is dedicated to collecting the types and information of dogs, 
						allowing more people to understand the dog and adopting a dog that suits their environment in a certain way. </p>
					</div>
			</div>
		</div>
		<footer data-role="footer" data-position="fixed">
			<div class="container">
				<div class="row twelve columns">
					<p>Who Let the Engineers Out &copy; 2019</p>
				</div>
			</div>
		</footer>
	</body>
</html>
