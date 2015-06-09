<?php
	$response->body = preg_replace("/<p class=\"top_link\">.*<\/a>/", "", $response->body);
?>