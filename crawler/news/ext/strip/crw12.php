<?php
	if(preg_match('/\(続く\)/', $response->body))
	{
		$loop = true;
		$response->url .= "?p=all";
	}
	else
	{
		$loop = false;
	}
?>