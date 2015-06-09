<?php
	$page_html = rm_stsc(rm_endl($page_html));
	preg_match_all('/<!-- google_ad_section.*?-->(.*?)<!-- google_ad_section.*?-->/', $page_html, $match);
	
	$body = $match[0][0];
?>