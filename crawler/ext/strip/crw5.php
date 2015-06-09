<?php
	$response->body = preg_replace("/<div\ class=\"sns\-caption\-news.*?<\/div>/m", "", $response->body);
?>