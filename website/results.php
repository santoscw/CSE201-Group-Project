<?php 

require_once 'phpimports/header.php';
require_once 'phpimports/search_class.php';

$breedlistactive = "ui-btn-active ui-state-persist";

session_start();
$username = $deletemsg = null;
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
if (isset($_SESSION['username']) && isset($_SESSION['level']) && $_SESSION['level'] >= 0) {
    $username = $_SESSION['username'];
    $level = $_SESSION['level'];
    $loggedin = true;
}
require_once 'phpimports/admin_nav.php';

if (isset($_POST['name'])) {
    $name = mysql_sanitize_db_input_info($_POST['name']);
    $target = mysql_sanitize_db_input_info($_POST['db']);
    $column = mysql_sanitize_db_input_info($_POST['column']);

    $search = new Search($target, $column, $name);
    $_SESSION['search'] = $search;

    $outputtable = $search->query();

    if ($search->getOffset() == 0) {
        $prevButton = <<<_STRING
        <a data-role="button" class="ui-btn ui-corner-all ui-state-disabled" disabled="true">Prev</a>
_STRING;
    } else {
        $prevButton = <<<_STRING
        <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Prev</a>
_STRING;
    }

    if ($search->getOffset() + 10 >= $search->getLength()) {
        $nextButton = <<<_STRING
        <a data-role="button" class="ui-btn ui-corner-all ui-state-disabled">Next</a>
_STRING;
    } else {
        $nextButton = <<<_STRING
        <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Next</a>
_STRING;
    }

    
} else if (isset($_SESSION['search'])) {
    $search = $_SESSION['search'];
    if (isset($_POST['next']))
        $search->setOffset($_POST['next']);
    else if (isset($_POST['prev']))
        $search->setOffset($_POST['prev']);

    $outputtable = $search->query();

    if ($search->getOffset() == 0) {
        $prevButton = <<<_STRING
        <a data-role="button" class="ui-btn ui-corner-all ui-state-disabled" disabled="true">Prev</a>
_STRING;
    } else {
        $prevButton = <<<_STRING
        <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Prev</a>
_STRING;
    }
    if ($search->getOffset() + 10 >= $search->getLength()) {
        $nextButton = <<<_STRING
        <a data-role="button" class="ui-btn ui-corner-all ui-state-disabled">Next</a>
_STRING;
    } else {
        $nextButton = <<<_STRING
        <a href="#" data-role="button" class="ui-btn ui-corner-all submitProxy">Next</a>
_STRING;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>The Dog-alogue: Search</title>
    <?php echo $headerImport; ?>
    <link rel="icon" type="image/ico" href="images/favicon.ico">
    <script type="text/javascript">
        $(function() {
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
    <div data-role="header" class="ui-header ui-bar-inherit">
        <h3 class="ui-title" role="heading">Search</h3>
        <?php echo $nav; ?>
        <?php if ($level > 1) echo $admin_nav; ?>
    </div>
    <header class="main_nav">
    <div class="container">
		<div data-role="collapsible" data-collapsed-icon="search" data-collapsed="false" data-expanded-icon="search">
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
    </header>
    <div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
        <?php echo $outputtable; ?>
        <div class="container">
            <div class="four columns"><p> </p></div>
            <div class="two columns">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" data-ajax="false">
                    <input type="hidden" name="prev" value="<?php echo $_SESSION['search']->getOffset() - 10; ?>" />
                    <?php echo $prevButton; ?>
                </form>
            </div>
            <div class="two columns">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" data-ajax="false">
                    <input type="hidden" name="next" value="<?php echo $_SESSION['search']->getOffset() + 10; ?>" />
                    <?php echo $nextButton; ?>
                </form>
            </div>
            <div class="four columns"></div>
        </div>
    </div>
</body>

</html>