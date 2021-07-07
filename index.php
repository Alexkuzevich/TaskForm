<?php

require_once('db_access.php');
$mysql->query("SET NAMES 'utf8'");

function printResult($result) {
    echo "<h2>All tasks: </h2>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<b>From</b>: ".$row['from-user'].". ";
            echo "<b>To</b>: ".$row['to-user'].". ";
            echo "<b>Task</b>: ".$row['text']."<br><br>";
        }
    }
    echo "<hr>";
}

if (isset($_POST["send"])) {
    $from = htmlspecialchars($_POST["from"]);
    $to = htmlspecialchars($_POST["to"]);
    $message = htmlspecialchars($_POST["message"]);
    $error_from = "";
    $error_to = "";
    $error_message = "";
    $error = false;
    if($from == "") {
        $error_from = "Enter the correct user name";
        $error = true;
    }
    if($to == "") {
        $error_to = "Enter the correct user name";
        $error = true;
    }
    if(strlen($message) == 0) {
        $error_message = "Enter the message text";
        $error = true;
    }

    if(!$error) {
        $mysql->query("INSERT INTO `users` (`from-user`, `to-user`, `text`) VALUES ('$from', '$to', '$message')");
        //printResult($result);
    }
}

if (isset($_POST["show"])) {
    $result = $mysql->query("SELECT DISTINCT `from-user`, `to-user`, `text` FROM `users` WHERE `users`.`text` IS NOT NULL");
    printResult($result);
}

if (isset($_POST["delete"])) {
    $mysql->query("DELETE FROM `users` WHERE `text` = ''");
}

$mysql->close();
?>

<!doctype html>
<html>
<head>
    <title>Task page</title>
</head>
<body>
<h2>New task</h2>
<form name ="feedback" action="" method="post">
    <label>From: </label> <br/>
    <input type="text" name="from"/>
    <span style="color: red"><?=$error_from?></span>
    <br/>
    <label>To: </label> <br/>
    <input type="text" name="to"/>
    <span style="color: red"><?=$error_to?></span>
    <br/>
    <label>Message: </label> <br/>
    <textarea name="message" cols="30" rows="10"></textarea>
    <span style="color: red"><?=$error_message?></span>
    <br/>
    <input type="submit" name="send" value="Send"> <br/><br/>
    <input type="submit" name="show" value="Show tasks"> <br/><br/>
    <input type="submit" name="delete" value="Delete empty tasks">
</form>
</body>
</html>