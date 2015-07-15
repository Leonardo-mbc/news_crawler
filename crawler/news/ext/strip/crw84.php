<?php
    $response->body = preg_replace("/<p>.*?date_start.*?<\/p>/", "", $response->body);
?>