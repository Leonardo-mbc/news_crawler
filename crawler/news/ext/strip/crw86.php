<?php
    $response->body = preg_replace("/\n/", "", $response->body);
    $response->body = preg_replace("/<p>更新時間.*?<\/p>|<h[345]>.*?<\/h[345]>|<div class=\"pageTop\">.*<\/div>|<a href=\"#top\".*?<\/a>|<p class=\"jasrac\">.*<\/p>/", "", $response->body);
?>