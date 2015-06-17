<?php
$link = mysql_connect($server, $user, $password);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';
//echo '<br />';

$selectDb = mysql_select_db($database);
if (!$selectDb) {
	die('database selection problem: ' . mysql_error());
}
?>