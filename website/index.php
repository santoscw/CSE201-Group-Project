<?php
    require_once 'phpimports/header.php';
    
    $nameErr = $emailErr = $commentsErr = $submitmsg = $error = $level = $username = $loggedin = null;

    // session code
    session_start();
    if (isset($_POST['logout'])) {
        session_unset();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000);
        }
        session_devalueoy();
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


    // if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['comments'])) {
    //     $name_temp = mysql_sanitize_db_input_info($_POST['name']);
    //     $email_temp = mysql_sanitize_db_input_info($_POST['email']);
    //     $comment_temp = mysql_sanitize_db_input_info($_POST['comments']);
        
    //     if ($_SERVER['REQUEST_METHOD'] == "POST" && $name_temp == null) {
    //         $nameErr = "*Required";
    //     }
    //     if ($_SERVER['REQUEST_METHOD'] == "POST" && $email_temp == null) {
    //         $emailErr = "*Required";
    //     } elseif (!filter_var($email_temp, FILTER_VALIDATE_EMAIL)) {
    //         $emailErr = "Invalid address";
    //     }
    //     if ($_SERVER['REQUEST_METHOD'] == "POST" && $comment_temp == null) {
    //         $commentsErr = "*Required";
    //     }
    //     if ($nameErr == null && $emailErr == null && $commentsErr == null) {
    //         $query = "INSERT INTO comments (name, email, comment) VALUES ('$name_temp', '$email_temp', '$comment_temp')";
    //         $result = queryUser($query);
            
    //         if (!$result) {
    //             die($connection->error);
    //         } else {
    //             $submitmsg = "<p class='submit'>Thanks for the comment!</p>";
    //         }
    //     }
    // }
    // closeConnection();
    

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
					<?php if ($level > 0) { echo $admin_nav;} ?>
				</div>
			</div>
		</header>
		<form action="results.php" method="post" data-ajax="false">

			<div class="container">
					<div class="six columns">
						<label for="db" class="select"></label>
						<select name="db" id="db" data-native-menu="false" required="required">
							<option value="" data-placeholder="true">Choose...</option>
							<option value="dog">Dog</option>
							<option value="breed">Breed</option>
							<option value="shelter">Shelter</option>
						</select>
					</div>
					<div class="six columns" >
						<label for="column"></label>
						<select name='column' id="column" data-native-menu="false" required>
							<option value='' data-placeholder='true'>Choose...</option>
						</select>
					</div>
				</div>
				<div class="container">
					<div class="ten columns">
						<div id="sb-search" class="sb-search">
								<input type="search" class="sb-search-input" name="name" placeholder="Enter your search term..." id="search" required="">
						</div>
					</div>
					<div class="two columns">
						<input type="submit" class="ui-btn ui-input-btn ui-corner-all submitProxy" value="Search" />
					</div>
			</div>
			</form>
<!--
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
								<a class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all submitProxy" data-form="ui-btn-up-a">Submit</a>
								
							</div>
							<?php
                                    if ($submitmsg != null) {
                                        ?>
										<p class='submit'>Thanks for the comment!</p>
							<?php
                                    }
                                ?>
						</div>
					</div>
				</form>
			</div>
		</div>
		-->

		<style>
body, html {
    height: 100%;
}
.hero-image {
    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("husky-2.jpg");
  height: 50%;


  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}
.hero-text {
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
}
</style>


	<!-- header -->
	<!-- //header -->
	<!-- banner -->
	<div class="banner">
		<div class="container">
			<div data-form="ui-body-a" id="contactSection" data-theme="a" class="ui-body ui-body-a ui-corner-all">
				<div  id="top" class="callbacks_container">
					<ul class="rslides" id="slider3">       
						<li>
							<div class="banner-textagileinfo">
								<h6>Find your dog by</h6>	 
								<h3>Breeds</h3>	 
								<div class="more">
									<a href="#" data-toggle="modal" data-target="#myModal"> Click here</a>
								</div>	
							</div>	
						</li>
						<li>
							<div class="banner-textagileinfo"> 
								<h6>Want to adopt a dog?</h6>	 
								<h3>Find the shelter around you </h3>	
								<div class="more">
									<a href="#" data-toggle="modal" data-target="#myModal"> Click here</a>
								</div>	
							</div>	
						</li>
						<li>
							<div class="banner-textagileinfo">
								<h6>Want to know more about dogs?</h6>
								<h3>Dog breed wiki</h3>		 	
								<div class="more">
									<a href="https://en.wikipedia.org/wiki/Category:Dog_breeds"> Click here</a>
								</div>		
							</div>		
						</li> 
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- modal-sign -->
	
		<footer data-role="footer">
			<div class="row">
				<div class="container">
					<div class="row twelve columns">
					<div class="modal bnr-modal fade" id="myModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
					<div class="modal-body modal-spa">
					<img class="img-responsive" src="images/g1.jpg" alt="">
					<img src="giphy.gif" alt="Smiley face" height="100" width="100">
					<h3> Our Mission </h3>
					<p>We are surrounded by many kinds of dogs every day, but do you really understand them? Do you know their breed? Their history, 
					habits, bloodlines, etc., each is their best description. Our website is dedicated to collecting the types and information of dogs, 
					allowing more people to understand the dog and adopting a dog that suits their environment in a certain way. </p>
				</div> 
			</div>
		</div>
	</div>
	<!-- //modal-sign -->
		<!-- //banner -->
		<!-- welcome -->
			<div class="footer-w3copy w3-agileits">
		<p>Copyright &copy; 2019.</a></p>
	</div>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
