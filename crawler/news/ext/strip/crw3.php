<?php
    if(preg_match('/\[記事全文\]/', $response->body))
    {
        $loop = true;
        preg_match_all('/href="(.*?)".*?\[記事全文\]<\/a>/', $response->body, $m);
        $response->url = $m[1][0];
    }
    else
    {
        $loop = false;
    }
?>