<?php
	$response->body = preg_replace("/<h2>.*?<\/h2>|<h3 id=\"news_detail_title\">.*?<\/h3>|<h3 id=\"news_day_index_title\">.*<\/div>/", "", $response->body);
?>