<?php
    $response->body = preg_replace("/■関連記事■.*<\/a>|<li.*?>.*?<\/li>/", '', $response->body);
?>