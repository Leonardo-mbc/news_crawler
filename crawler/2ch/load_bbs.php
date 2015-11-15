<?php
    $result = $db->prepare("SELECT name, url FROM bbs");
    $result->execute();
    $result->bind_result($name, $url);

    $bbs = array();
    while($result->fetch()) $bbs[$name] = $url;
    $result->close();

    $html = file_get_contents('http://kita.jikkyo.org/cbm/cbm.cgi/20.p0.m0.sc/-all/bbsmenu.html');
    $html = mb_convert_encoding($html, 'utf8', 'sjis-win');
    preg_match_all('/<A HREF=.*>.*<\/A>/', $html, $links);
    $links = preg_replace('/ TARGET=_blank/', '', $links[0]);

    $i = 0;
    foreach($links as $link) {
        if(preg_match('{<A HREF=http:\/\/(.*).(2ch.net|bbspink.com|machi.to|2ch.sc)\/(.*)\/>}', $link)) {
            if(preg_match_all('/<A HREF=(\S*)>(.*)<\/A>/', $link, $match, PREG_SET_ORDER)) {
                $res[$i][0] = $match[0][1];
                $res[$i][1] = $match[0][2];
                $i++;
            }
        }
    }

    foreach($res as $link) {
        $name = $link[1];
        $url = $link[0];
        preg_match('{(2ch.net|bbspink.com|machi.to|2ch.sc)/(.*)/$}', $url, $ch);
        if($bbs[$name] != $url) {
            #echo $name." ".$url."<br/>";
            #$result = $db->prepare("INSERT INTO bbs (url, name) VALUES (?, ?)");

            $result = $db->prepare("UPDATE bbs SET url = '?' WHERE name = '?' LIMIT 1");
            $result->bind_param('ss', $url, $name);
            $result->execute();
            $result->close();
        }
    }
?>
