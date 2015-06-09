<?php
	$db = new mysqli('XXX', 'XXX', 'XXX', 'XXX')
		or die(mysql_error());
	$state = $db->prepare("SET NAMES utf8");
	$state->execute();
?>
