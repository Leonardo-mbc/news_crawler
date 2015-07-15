<?php
    $loc = "/Users/Leonardo/Dropbox/prog/website/news/";
    include $loc."crawler/function.php";

    if($need_topic) {
        if(in_array($_GET["tpc"], array("h", "ir", "y", "w", "b", "p", "e", "s", "t", "po"))) {
            $tpc = $_GET['tpc'];
        } else {
            if(in_array($_SERVER["argv"][1], array("h", "ir", "y", "w", "b", "p", "e", "s", "t", "po"))) $tpc = $_SERVER["argv"][1];
                else die("tpc = ? (h, ir, y, w, b, p, e, s, t, po)");
        }

        $ned = "&ned=us";
        if($tpc != "po") $tpc .= $ned;
    }
    
    function next_check($page_html) {
        return preg_match('/<td class="next">/', $page_html, $match);
    }

    function rm_stsc($html) {
        $html = preg_replace('!<style.*?>.*?</style.*?>!is', '', $html);
        $html = preg_replace('!<script.*?>.*?</script.*?>!is', '', $html);
        return $html;
    }

    function rm_endl($html) {
        return str_replace(array("\r", "\n"), '', $html);
    }

    function border($text = "", $length = 10, $line = "-") {
        for($i=0;$i<$length;$i++) $line .= $line[0];

        if($text == "") {
            return $line.$endl;
        } else {
            return $line."<br/>\n".$text."<br/>\n".$line."<br/>\n";
        }
    }

    function logger($text) {
        if($GLOBALS['logger'] == "enable") echo '<p style="color:gray">'.$text.'</p>';
    }
?>
