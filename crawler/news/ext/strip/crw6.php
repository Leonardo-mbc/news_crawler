<?php
    $response->body = preg_replace("/((記事に関する記者への問い合わせ先：|記事についての記者への問い合わせ先：|記事についてのエディターへの問い合わせ先：).*?<\/p>|<span\ class=\"update\">.*?<\/span>)/", "", $response->body);
?>