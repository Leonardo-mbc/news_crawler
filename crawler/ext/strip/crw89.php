<?php
	$response->body = preg_replace("/<title>.*?<\/title>/", "", $response->body);
?>