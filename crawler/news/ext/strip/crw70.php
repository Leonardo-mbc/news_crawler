<?php
    $response->body = preg_replace("/<p class=\"news_img_link\">.*?<\/p>/", "", $response->body);
?>