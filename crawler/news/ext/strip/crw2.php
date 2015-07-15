<?php
    $response->body = preg_replace("/関連キーワード|\n/", "", $response->body);
    $response->body = preg_replace("/<p class=\"cmnc-words\">.*?<\/p>/", "", $response->body);
?>