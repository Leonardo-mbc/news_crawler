<?php
    $need_topic = true;
    include "header.php";
    include $loc."mysql/connect.php";
    $db->select_db("2ch_datasets");

    #include "load_bbs.php";
    #include "load_threads.php";
    include "load_responses.php";
?>
