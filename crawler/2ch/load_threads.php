<?php
    include "header.php";

    if($_GET["bbs_id"]) {
        $bbs_id = (int)$_GET['bbs_id'];
    } else {
        if($_SERVER["argv"][1]) $bbs_id = (int)$_SERVER["argv"][1];
            else die("bbs_id = ?");
    }

    echo "bbs_id: ".$bbs_id.PHP_EOL;

    $result = $db->prepare("SELECT url FROM bbs WHERE id = ? LIMIT 1");
    $result->bind_param('i', $bbs_id);
    $result->execute();
    $result->bind_result($url);
    $result->fetch();
    $result->close();

    $subject = $url.'subject.txt';
    $thread_list = @fopen($subject,'r');

    if($thread_list) {
        while(!feof($thread_list)) {
            $line = fgets($thread_list);
            $line = mb_convert_encoding($line, 'utf8', 'sjis-win');

            if(preg_match("/\.dat<>/", $line)) $sep_ext = '.dat<>';
                else if(preg_match("/\.cgi,/", $line)) $sep_ext = '.cgi,';

            $thread_id_num = mb_strpos($line, $sep_ext);

            $thread_id = (int)mb_substr($line, 0, $thread_id_num);

            preg_match("/\(([0-9]+)\)$/", $line, $match);
            $num = $match[1];

            preg_match('/\\'.$sep_ext.'(.*?)\s*\([0-9]+\)$/', $line, $match);
            $thread_name = $match[1];

            $result = $db->prepare("SELECT count(*), res FROM threads WHERE id = ?");
            $result->bind_param('i', $thread_id);
            $result->execute();
            $result->bind_result($count, $res);
            $result->fetch();
            $result->close();

            if(0 < $count && (int)$res < $num) {
                $result = $db->prepare("UPDATE threads SET res = ? WHERE id = ?");
                $result->bind_param('ii', $num, $thread_id);
                $result->execute();
                $result->close();
            } else {
                $result = $db->prepare("INSERT IGNORE INTO threads (id, bbs_id, name, res) VALUES(?, ?, ?, ?)");
                $result->bind_param('iisi', $thread_id, $bbs_id, $thread_name, $num);
                $result->execute();
                $result->close();
            }
        }
    }

    fclose($thread_list);
?>
