<?php
    $response->body = preg_replace("/<p>■関連記事.*?<\/p>/", "", $response->body);
?>