<?php
    require_once 'phpimports/header.php';
    require_once 'phpimports/comment_class.php';
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

    $commentsErr = $submitmsg = $error = $query = null;

    if (isset($_POST['delete'])) {
        $delTar = $_POST['delete'];
        $submitmsg = delComment($delTar);
    }

	if (isset($_POST['comments'])) 
	{
        $comment_temp = mysql_sanitize_db_input_info($_POST['comments']);
        $user_id = (int)mysql_sanitize_db_input_info($_POST['userid']);
        $dog_id = (int)mysql_sanitize_db_input_info($_POST['dogid']);
		
		if ($_SERVER['REQUEST_METHOD'] == "POST" && $comment_temp == null)
		{
			$commentsErr = "*Required";
		}
		if ($commentsErr == null) {
            $submitmsg = addComment($dog_id, $user_id, $comment_temp);
        }
        $_SESSION['entry'] = $dog_id;
	}

    
    if (!isset($_POST['entry']) && !isset($_SESSION['entry'])) {
        header("Location: results.php");
    }
    
    $target = NULL;
    if (isset($_POST['dogid'])) {
        $target = $_POST['dogid'];
        $_SESSION['entry'] = $target;
    } else if (isset($_POST['entry'])) {
        $target = $_POST['entry'];
        $_SESSION['entry'] = $target;
    } else {
        $target = $_SESSION['entry'];
    }

    $query  = <<<_STRING
    SELECT
    t1.name AS name,
    t1.age AS age,
    t2.name AS breed,
    t3.name AS shelter,
    t1.img AS img
    FROM `dog` AS t1 
    LEFT JOIN `breed` AS t2 ON t1.breed_id = t2.id 
    LEFT JOIN `shelter` AS t3 ON t1.shelter_id = t3.id
    WHERE t1.id = $target
_STRING;
    $result = queryData($query);
    if (!$result) {
        die($data->error);
    }
    $rows = $result->num_rows;
    $outputtable = null;
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $documentfunc = htmlspecialchars($_SERVER["PHP_SELF"]);
    $outputrow = <<<_STRING
    <div class="row six columns">
    <div class="row"><h2>{$row['name']}</h2></div>
    <h4>Age: {$row['age']}</h4>
    <h4>Breed: {$row['breed']}</h4>
    <h4>Shelter: {$row['shelter']}</h4>

    </div>
    <div class="row six columns">
    <img src="{$row['img']}" />

    </div>
    
_STRING;
    $outputtable = $outputtable . $outputrow;
    $dog_name = $row['name'];
    $result->close();

    $user_id = $_SESSION['userid'];
    if (isset($_SESSION['userid'])) {
        $addComment = <<<_STRING
        <div class="container">
        <div data-form="ui-body-a" id="contactSection" data-theme="a" class="ui-body ui-body-a ui-corner-all">
            <form data-form="ui-body-a" id="contactForm" method="post" action="$documentfunc" data-ajax="false">
                <div class="row">
                    <div class="container">
                        <div class="twelve columns">
                            <h2 class="title">Comment</h2>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="userid" value="$user_id" />
                <input type="hidden" name="dogid" value="$target" />
                <div class="row">
                    <div class="container">
                        <div class="twelve columns">
                            <div data-form="ui-body-a" class="ui-li-static ui-field-contain">
                                <label for="comments">Comment: <span class="ui-custom-inherit"><br>$commentsErr</span></label>
                                <textarea name="comments" rows="6" data-autogrow="false"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="twelve columns">
                            <input type="submit" value="Submit" class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all" data-iconpos="right" data-icon="comment" data-form="ui-btn-up-a" />
                            $submitmsg
                        </div>
                    </div>
                </div>
            </form>
        </div>
    
_STRING;
    
    } 


    $commentEngine = new CommentTable($user_id, $target);

    $pastComments = $commentEngine->query();

?>
<!DOCTYPE html>
<html>
	<head>
		<title>The Dog-alogue: <?php echo $dog_name; ?></title>
		<?php echo $headerImport; ?>
		<link rel="icon" type="image/ico" href="images/favicon.ico">
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
			<h3 class="ui-title" role="heading"><?php echo $dog_name; ?></h3>
				<?php echo $nav; ?>
				<?php if ($level > 1) {
                    echo $admin_nav;
                } ?>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
            <div class="container">
                <?php echo $outputtable; ?>
            </div>
            <div class="container">
                <?php echo $deletemsg; ?>
            </div>
            <div class="container">
                <?php echo $pastComments; ?>
            </div>
            <?php echo $addComment; ?>
            <?php echo $catastrophic; ?>
		</div>
	</body>
</html>