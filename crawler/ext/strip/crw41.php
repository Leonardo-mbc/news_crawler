<?php
	$response->body = preg_replace("/\n/", "%%%%", $response->body);
	$response->body = preg_replace("/<div\ id=\"no_tb_mask\".*?<div\ class=\"privacy\".*?<\/div>/", "", $response->body);
	$response->body = preg_replace("/%%%%/", "\n", $response->body);
?>