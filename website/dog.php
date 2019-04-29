<?php
    require_once 'phpimports/header.php';
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

    $commentsErr = $submitmsg = $error = null;
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
			$query = "INSERT INTO commentDog (dog_id, user_id, comment) VALUES ('$dog_id', '$user_id', '$comment_temp')";
			$result = queryData($query);
			
            if (!$result) 
                $catastrophic = $connection->error;
			else
			{
				$submitmsg = "<p class='submit'>Thanks for the comment!</p>";
			}
		}
	}

    
    if (!isset($_POST['entry']) && !isset($_SESSION['entry'])) {
        header("Location: results.php");
    }
    
    $target = NULL;
    if (isset($_SESSION['entry'])) {
        $target = $_SESSION['entry'];
    } else {
        $target = $_POST['entry'];
        $_SESSION['entry'] = $target;
    }

    $query  = <<<_STRING
    SELECT
    t1.name AS name,
    t1.age AS age,
    t2.name AS breed,
    t3.name AS shelter,
    t1.id AS id 
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
    <tr>
    <td>{$row['name']}</td>
    <td>{$row['age']}</td>
    <td>{$row['breed']}</td>
    <td>{$row['shelter']}</td>
    </tr>
_STRING;
        $outputtable = $outputtable . $outputrow;
        $dog_name = $row['name'];
        $dog_id = $row['id'];

        $result->close();
        $user_id = $_SESSION['userid'];

        $query = <<<_STRING
        SELECT 
        t1.comment AS comment,
        t2.username AS username, 
        t1.time AS time 
        FROM `commentDog` AS t1
        LEFT JOIN `user` AS t2 ON t1.user_id = t2.uid
        WHERE t1.dog_id = $dog_id
        ORDER BY time DESC
_STRING;

        $result = queryData($query);
        if (!$result) {
            die($data->error);
        }
        $rows = $result->num_rows;
        $pastComments = null;
        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);


            $pastComments = $pastComments . <<<_STRING
        <tr>
        <td>{$row['username']}</td>
        <td>{$row['time']}</td>
        <td>{$row['comment']}</td>
        </tr>


_STRING;
        }

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
                <input type="hidden" name="dogid" value="$dog_id" />
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
                            <input type="submit" value="Submit" class="ui-btn ui-input-btn ui-btn-icon-right ui-icon-comment ui-corner-all" data-form="ui-btn-up-a" id="submitProxy" />
                            $submitmsg
                        </div>
                    </div>
                </div>
            </form>
        </div>

_STRING;
    $result->close();
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
			<h3 class="ui-title" role="heading">Database Manager</h3>
				<?php echo $nav; ?>
				<?php if ($level > 0) {
    echo $admin_nav;
} ?>
		</div>
		<div id="mainArea" data-form="ui-page-theme-a" class="ui-content">
			<?php echo $deletemsg; ?>
            <table data-role="table" id="search_table" data-mode="columntoggle" class="ui-responsive ui-table ui-corner-all">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th data-priority="4">Age</th>
                        <th data-priority="2">Breed</th>
                        <th data-priority="3">Shelter</th>
                    </tr>
                </thead>
                <tbody>
					<?php echo $outputtable; ?>
				</tbody>
			</table>
            <div class="container">
                <table data-role="table" id="comments_table" class="ui-responsive ui-table ui-corner-all">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Time</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $pastComments; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $user_id; ?>
            <?php echo $catastrophic; ?>
            <?php echo $addComment; ?>
		</div>
	</body>
</html>