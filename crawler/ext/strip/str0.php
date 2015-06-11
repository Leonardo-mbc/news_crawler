<?php
	
	$response->body = preg_replace("/<div.*?References.*?<\/div>|<sup>.*?<\/sup>/", "", $response->body);
	
	$dom = new simple_html_dom();
	$dom->load($response->body.$endl);
	
	foreach($dom->find('text') as $obj)
	{
		$response->stripped_body .= preg_replace("/　|\s/", "", $obj);
	}
	
	$dom->clear();
?>