<?php
	if(preg_match('/【続きを読む】/', $response->body))
	{
		$loop = true;
		preg_match_all('/href="(.*?)".*?【続きを読む】<\/a>/', $response->body, $m);
		preg_match_all('/(http:\/\/.*?\/)/', $response->url, $u);
		$m[1][0] = preg_replace('/\.\.\//', "", $m[1][0]);
		
		$response->url = $u[1][0].$m[1][0];
		$response->body = preg_replace('/【続きを読む】|<p class="caption">.*<\/p>|<d[dt].*>.*<\/d[dt]>|<p.*>.*<!-- date -->.*<!-- date end -->.*<\/p>/', "", $response->body);
		$tmp_body .= $response->body;
	}
	else
	{
		$response->body = preg_replace('/【拡大】/', "", $response->body);
		$response->body = $tmp_body.$response->body;
		$loop = false;
	}
?>