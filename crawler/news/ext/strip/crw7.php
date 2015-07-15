<?php
    if(preg_match('/<a.*?続く\.\.\.<\/a>/', $response->body))
    {
        $loop = true;
        $response->url .= "?sp=true";
    }
    else
    {
        $loop = false;
    }
?>