<?php
    $response->body = preg_replace("/<h3\ class=\"byline\">.*?<\/h3>|<p>原文.*?<\/p>|<p>関連記事：.*?<\/p>|<a.*?<\/a>/", "", $response->body);
?>