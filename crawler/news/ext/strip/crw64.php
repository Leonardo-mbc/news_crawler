<?php
    if(preg_match('/⇒続きを見る/', $response->body))
    {
        $loop = true;
        preg_match_all('/href="(.*?)".*?⇒続きを見る<\/a>/', $response->body, $m);
        preg_match_all('/(http:.*\/)/', $response->url, $u);
        $response->url = $u[1][0].$m[1][0];
        $response->body = preg_replace('/⇒続きを見る/', "", $response->body);
        $tmp_body .= $response->body;
    }
    else
    {
        $response->body = $tmp_body.$response->body;
        $loop = false;
    }
?>