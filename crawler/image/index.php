<?php
    include "header.php";
    include $loc."lib/simple_html_dom.php";
    include $loc."lib/unescape.php";
    require_once $loc."lib/rss_fetch.inc";

    $query = "apple";
    $url = "https://www.google.co.jp/search?site=imghp&tbm=isch&sa=1&q=".$query;
    $curl_result = curl_get($url);

    var_dump($curl_result);
    die();
?>
