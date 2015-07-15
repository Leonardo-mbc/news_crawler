<?php
    $page_html = rm_stsc(rm_endl($page_html));
    $dom = new simple_html_dom();
    $dom->load($page_html);
    
    foreach($dom->find('text') as $obj)
    {
        $body[] = $obj.$endl;
    }
    
    $dom->clear();
?>