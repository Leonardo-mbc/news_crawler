<?php
	include "function.php";
	
	if(in_array($_GET["tpc"], array("h", "ir", "y", "w", "b", "p", "e", "s", "t", "po")))
	{
		$tpc = $_GET['tpc'];
	}
	else
	{
		if(in_array($_SERVER["argv"][1], array("h", "ir", "y", "w", "b", "p", "e", "s", "t", "po"))) $tpc = $_SERVER["argv"][1];
			else die("tpc = ? (h, ir, y, w, b, p, e, s, t, po)");
	}
	
	$ned = "&ned=us";
	if($tpc != "po") $tpc .= $ned;
?>