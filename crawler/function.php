<?php
	$endl = "<br/>\n";

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

		return array(
		    'http_code' => (int)$header['http_code'],
		    'body' => $result
		);
	}
?>
