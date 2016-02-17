<?php
    if(function_exists('tidy_parse_string'))
    {
        $tidy = tidy_parse_string($page_html["body"], array(), 'UTF8');
        $tidy->cleanRepair();
        $page_html["body"] = $tidy->value;
    }

    $readability = new Readability($page_html["body"]);
    $readability->debug = false;
    $readability->convertLinksToFootnotes = true;

    $result = $readability->init();
    // does it look like we found what we wanted?
    if($result)
    {
        $content = $readability->getContent()->innerHTML;
        if(function_exists('tidy_parse_string'))
        {
            $tidy = tidy_parse_string($content, array('indent'=>true, 'show-body-only' => true), 'UTF8');
            $tidy->cleanRepair();
            $content = $tidy->value;
        }
        $response->body = $content;
        $response->code = true;
    }
    else
    {
        $response->body = NULL;
        $response->code = false;
    }
?>
