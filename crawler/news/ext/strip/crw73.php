<?php
    $response->body = preg_replace("/<a.*?>.*?<\/a>|<h[45]>.*?<\/h[45]>|<dt>.*?<\/dt>|<p class=\"note\">.*?<\/p>/", "", $response->body);
    #$response->body = preg_replace("/【関連記事】.*?<\/dl>/m", "", $response->body);
?>