<?php
	$response->body = preg_replace("/<p\ class=\"print\">.*?<\/p>/", "", $response->body);
?>