<?php
	$response->body = preg_replace("/\n/", "", $response->body);
	$response->body = preg_replace("/<div id=\"shinmaifooter\".*?>.*<\/div>/", "", $response->body);
?>