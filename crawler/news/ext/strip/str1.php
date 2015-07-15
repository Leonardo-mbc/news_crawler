<?php
    $response->body = preg_replace("/References|<sup>.*<\/sup>/", "", $response->body);
    
    $dom = new simple_html_dom();
    $dom->load($response->body);
    
    foreach($dom->find('text') as $obj)
    {
        $response->stripped_body .= preg_replace("/　|\s/", "", $obj);
    }
    
    $dom->clear();
?>