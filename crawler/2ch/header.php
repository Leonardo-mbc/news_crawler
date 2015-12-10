<?php
    header("User-Agent:Monazilla/1.00(hoge.com/0.4)");

    $loc = "/home/leonardo/services/news/";
    include $loc."crawler/function.php";
    include $loc."mysql/connect.php";
    $db->select_db("2ch_datasets");
?>
