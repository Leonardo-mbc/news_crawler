<?php
	$endl = "<br/>\n";
	$loc = "/home/leonardo/www/";
	
	$ERROR = array(
		"ERROR.000 (READABILITY ERROR)",
		"ERROR.001 (PAGE DOWNLOAD ERROR)"
	);
	
	function curl_get($url, $timeout = 300)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
		$result = curl_exec($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);
		
		if($header['http_code'] == "404")
		{
			echo $header['http_code']."<br/>\n";
			return false;
		}
		else
		{
			return $result;
		}
	}
	
	function next_check($page_html)
	{
		return preg_match('/<td class="next">/', $page_html, $match);
	}
	
	function rm_stsc($html)
	{
		$html = preg_replace('!<style.*?>.*?</style.*?>!is', '', $html);
		$html = preg_replace('!<script.*?>.*?</script.*?>!is', '', $html);
		return $html;
	}
	
	function rm_endl($html)
	{
		return str_replace(array("\r", "\n"), '', $html);
	}
	
	function border($text = "", $length = 10, $line = "-")
	{
		for($i=0;$i<$length;$i++) $line .= $line[0];
		
		if($text == "")
		{
			return $line.$endl;
		}
		else
		{
			return $line."<br/>\n".$text."<br/>\n".$line."<br/>\n";
		}
	}
	
	function logger($text)
	{
		if($GLOBALS['logger'] == "enable") echo '<p style="color:gray">'.$text.'</p>';
	}
?>