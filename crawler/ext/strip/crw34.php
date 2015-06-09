<?php
	$response->body = preg_replace("/Next：.*?<\/a>/", "", $response->body);
?>