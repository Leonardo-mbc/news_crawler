<?php
    $response->body = preg_replace("/\n/", "%%%%", $response->body);
    $response->body = preg_replace("/<!--\ trackback\ -->.*?<!--\ trackback\ -->|<p>｜トラックバック.*?｜<\/p>|<span\ class=\"ninja_onebutton_hidden\">.*?<\/span>|チャイナプレスを登録|<div\ id=\"banner_siteinfo\".*?<\/div>/", "", $response->body);
    $response->body = preg_replace("/%%%%/", "\n", $response->body);
?>