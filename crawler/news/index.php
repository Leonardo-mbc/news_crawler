<?php
    $need_topic = true;
    include "header.php";
    include $loc."mysql/connect.php";
    include $loc."lib/simple_html_dom.php";
    include $loc."lib/unescape.php";
    require_once $loc."lib/rss_fetch.inc";

    $url = "https://news.google.com/news?hl=ja&ie=UTF-8&oe=UTF-8&output=rss&num=100&topic=$tpc";
    $curl_result = curl_get($url);

    $result = $db->prepare("INSERT INTO crawling_log (response_code) VALUES(?)");
    $result->bind_param('i', $curl_result['http_code']);
    $result->execute();
    $result->close();

    $rss = mb_convert_encoding($curl_result["body"], "UTF-8");
    $rss = new MagpieRSS($rss);
    $items = $rss->items;

    foreach($items as $item)
    {
        preg_match_all('/href="(.*?)">/', $item["description"], $match);
        $more = array_pop($match[1]);
        preg_match('/ncl=(.*?)&/', $more, $match);
        $topic_id = $match[1];

        #echo "===== topic =====".$endl;
        #echo $item["title"].$endl;    #トピック代表タイトル
        #echo $more.$endl;            #トピック集約ページへのリンク
        #echo $topic_id.$endl;        #トピックID
        #echo "===== topic =====".$endl;
        #echo $endl;

        $result = $db->prepare("INSERT IGNORE INTO topics (id, name, url) VALUES(?, ?, ?)");
        $result->bind_param('sss', $topic_id, $item["title"], $more);
        $result->execute();
        #echo $result->error.$endl;
        $result->close();

        $start = 0;

        do
        {
            $curl_result = curl_get("http://news.google.com/news/story?cf=all&hl=ja$ned&topic=$tpc&ncl=$topic_id&start=".($start*30));
            $page_html = $curl_result['body'];
            $start += 1;
            $dom = new simple_html_dom();
            $dom->load($page_html);

            foreach($dom->find('div[class^=story from-gxp]') as $story)
            {
                $title  = $story->find('span[class=titletext]');
                $anchor = $story->find('a');
                $source = $story->find('span[class^=source]');
                $url = parse_url($anchor[0]->href);

                $ins_title = $title[0]->plaintext;
                $ins_source = html_to_utf8($source[0]->plaintext);
                $ins_url = $anchor[0]->href;
                $ins_host = $url['host'];

                #echo $title[0]->plaintext.$endl;    #記事タイトル
                #echo $anchor[0]->href.$endl;        #記事リンク
                #echo html_to_utf8($source[0]->plaintext).$endl;    #ニュースサイト名
                #echo $url['host'].$endl;            #ニュースサイトホスト名
                #echo $endl;

                $result = $db->prepare("INSERT INTO news (topic_id, name, source, url, host) SELECT ?, ?, ?, ?, ? FROM dual WHERE NOT EXISTS (SELECT id FROM news WHERE name = ? AND url = ?)");
                $result->bind_param('sssssss', $topic_id, $ins_title, $ins_source, $ins_url, $ins_host, $ins_title, $ins_url);
                $result->execute();
                #echo $result->error.$endl;
                $result->close();

                $result = $db->prepare("INSERT INTO crawlers (host) SELECT ? FROM dual WHERE NOT EXISTS (SELECT id FROM crawlers WHERE host = ?)");
                $result->bind_param('ss', $url['host'], $url['host']);
                $result->execute();
                #echo $result->error.$endl;
                $result->close();
            }

            #echo "===== Page $start END =====".$endl;
            #echo $endl;
            $dom->clear();
        }while(next_check($page_html));

        #die("途中でとめてる。");
    }
?>
