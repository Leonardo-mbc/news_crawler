<?php
    include "header.php";
    include $loc."lib/simple_html_dom.php";
    include $loc."lib/unescape.php";

    $url = "http://2chmm.com/arrival.html";
    $curl_result = curl_get($url);

    $result = $db->prepare("INSERT INTO crawling_log (response_code) VALUES(?)");
    $result->bind_param('i', $curl_result['http_code']);
    $result->execute();
    $result->close();

    $page_html = mb_convert_encoding($curl_result["body"], "UTF-8");
    $dom = new simple_html_dom();
    $dom->load($page_html);

    $title_ary = [];
    foreach($dom->find('li.entry') as $entry) {
        $anchor = $entry->find('a', 0);

        $title_ary[] = array(
            'title' => $entry->plaintext,
            'link' => $anchor->href
        );
    }

    $source_ary = [];
    foreach($dom->find('li.date') as $entry) {
        $datetime = preg_replace("/<span>.+?<\/span>/", "", $entry->innertext);

        $source = $entry->find('span', 0);
        $date = new DateTime($datetime);
        $source_ary[] = array(
            "source" => $source->plaintext,
            "datetime" => $date
        );
    }

    $threads = fArray_merge($title_ary, $source_ary);

    foreach($threads as $thread) {
        $url = parse_url($thread["link"]);

        $result = $db->prepare("INSERT INTO sources (host, domain) SELECT ?, ? FROM dual WHERE NOT EXISTS (SELECT id FROM sources WHERE host = ? AND domain = ?)");
        $result->bind_param('ssss', $thread["source"], $url["host"], $thread["source"], $url["host"]);
        $result->execute();
        $result->close();

        $result = $db->prepare("SELECT id FROM sources WHERE host = ? AND domain = ?");
        $result->bind_param('ss', $thread["source"], $url["host"]);
        $result->execute();
        $result->bind_result($src_id);
        while($result->fetch()) $source_id = $src_id;
        $result->close();

        $published_at = $thread["datetime"]->format('Y-m-d H:i:sP');
        $result = $db->prepare("INSERT IGNORE INTO threads (name, source_id, url, published_at) VALUES(?, ?, ?, ?)");
        $result->bind_param('siss', $thread["title"], $source_id, $thread["link"], $published_at);
        $result->execute();
        $result->close();
    }
?>
