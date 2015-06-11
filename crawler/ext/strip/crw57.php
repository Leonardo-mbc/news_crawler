<?php
	$response->body = preg_replace("/<a.*?<\/a>/", "", $response->body);
?>