<?php
    $response->body = preg_replace("/Topic\ Board\ トピックボード|BrandContent\ ブランドコンテンツ/", "", $response->body);
?>