<?php
    include "header.php";

    if($_GET["bbs_id"]) {
        $bbs_id = (int)$_GET['bbs_id'];
    } else {
        if($_SERVER["argv"][1]) $bbs_id = (int)$_SERVER["argv"][1];
            else die("bbs_id = ?");
    }

    if($_GET["thread_id"]) {
        $thread_id = (int)$_GET['thread_id'];
    } else {
        if($_SERVER["argv"][2]) $thread_id = (int)$_SERVER["argv"][2];
            else die("thread_id = ?");
    }

    echo "bbs_id: ".$bbs_id.PHP_EOL;
    echo "thread_id: ".$thread_id.PHP_EOL;

    $result = $db->prepare("SELECT url FROM bbs WHERE id = ? LIMIT 1");
    $result->bind_param('i', $bbs_id);
    $result->execute();
    $result->bind_result($bbs_url);
    $result->fetch();
    $result->close();

    $url = parse_url($bbs_url);
    $saba = $url["host"];
    $ita = $url["path"];
    $sure = $thread_id.'.dat';
    $logfile = $loc.'crawler/2ch/dat/'.$thread_id.'.dat';
    $data = '';
    $ch = curl_init();

    if(preg_match("/machi\.to/", $bbs_url)) {
        $bbs_type = "machi.to";
        $saba = "www.machi.to";
        $header[] = 'GET /bbs/offlaw.cgi/2'.$ita.$thread_id.' HTTP/1.1';
        curl_setopt($ch, CURLOPT_URL, $saba.'/bbs/offlaw.cgi/2'.$ita.$thread_id);
    } else if(preg_match("/2ch\.sc/", $bbs_url)) {
        $bbs_type = "2ch.sc";
        $header[] = 'GET '.$ita.'dat/'.$sure.' HTTP/1.1';
        curl_setopt($ch, CURLOPT_URL, $bbs_url.'dat/'.$sure);
    } else {
        die("Unknown domain");
    }
    $header[] = 'Host: '.$saba;
    $header[] = 'User-Agent: Monazilla/1.00';

    if(file_exists($logfile)) {
        $time = filemtime($logfile);
        $mod = date("r", $time - 3600 * 9);
        $byte = filesize($logfile);
        $header[] = 'If-Modified-Since: '.$mod;
        $header[] = 'Range: bytes='.$byte.'-';
    } else {
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    }
    $header[] = 'Connection: close';

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    var_dump($header);

    $res_count = 0;

    switch($code) {
        case 206:
            $result = $db->prepare("SELECT count(*) FROM responses WHERE thread_id = ?");
            $result->bind_param('i', $thread_id);
            $result->execute();
            $result->bind_result($rc);
            $result->fetch();
            $result->close();

            $res_count += $rc;

        case 200:
            file_put_contents($loc.'crawler/2ch/dat/'.$thread_id.'.dat', $data, FILE_APPEND | LOCK_EX);
            $data = mb_convert_encoding($data, 'utf8', 'sjis-win');
            $lines = explode(PHP_EOL, $data);
            foreach($lines as $line) {
                if(!empty($line)) {
                    $res_count += 1;
                    $res = explode('<>', $line);

                    $sep_ofs = 0;
                    if($bbs_type == "machi.to") {
                        $sep_ofs = 1;
                        $res_count = $res[0];
                    }

                    $name = $res[$sep_ofs + 0];
                    $mail = $res[$sep_ofs + 1];
                    $details = $res[$sep_ofs + 2];
                    $body = $res[$sep_ofs + 3];

                    $result = $db->prepare("INSERT INTO responses (id, thread_id, name, mail, details, body) VALUES(?, ?, ?, ?, ?, ?)");
                    $result->bind_param('iissss', $res_count, $thread_id, $name, $mail, $details, $body);
                    $result->execute();
                    $result->close();
                }
            }
        break;

        case 304:
            echo "Not Modified\n";
        break;
    }
?>
