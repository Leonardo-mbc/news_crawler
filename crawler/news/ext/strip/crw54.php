<?php
    $response->body = preg_replace("/【関連記事・情報】|<a.*?<\/a>/", "", $response->body);
?>