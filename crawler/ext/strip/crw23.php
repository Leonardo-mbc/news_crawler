<?php
	$response->body = preg_replace("/<rp>.*?<\/rp>|<rt>.*?<\/rt>/", "", $response->body);
?>