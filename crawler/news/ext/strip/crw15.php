<?php
    if(preg_match('/<a.*?続きを読む<\/a>/', $response->body))
    {
        $loop = true;
        preg_match_all('/href="(.*?)".*?続きを読む<\/a>/', $response->body, $m);
        echo $m[1][0];
        $response->url = $m[1][0];
    }
    else
    {
        $loop = false;
    }
?>