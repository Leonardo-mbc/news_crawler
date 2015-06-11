<?php
	include "header.php";
	include $loc."mysql/connect.php";
	include $loc."lib/simple_html_dom.php";
	include $loc."lib/unescape.php";
	require_once $loc."lib/rss_fetch.inc";

	$url = "https://news.google.com/news?hl=ja&ie=UTF-8&oe=UTF-8&output=rss&num=100&topic=$tpc";
	$rss = mb_convert_encoding(curl_get($url), "UTF-8");
	$rss = new MagpieRSS($rss);
	$items = array_slice($rss->items, 0, $num_items);

	foreach($items as $item)
	{
		preg_match_all('/href="(.*?)">/', $item["description"], $match);
		$more = array_pop($match[1]);
		preg_match('/ncl=(.*?)&/', $more, $match);
		$topic_id = $match[1];

		#echo "===== topic =====".$endl;
		#echo $item["title"].$endl;	#トピック代表タイトル
		#echo $more.$endl;			#トピック集約ページへのリンク
		#echo $topic_id.$endl;		#トピックID
		#echo "===== topic =====".$endl;
		#echo $endl;

		$result = $db->prepare("INSERT IGNORE INTO topics (id, name, url) VALUES(?, ?, ?)");
		$result->bind_param('sss', $topic_id, $item["title"], $more);
		$result->execute();
		$result->close();

		$start = 0;

		do
		{
			$page_html = curl_get("http://news.google.com/news/story?cf=all&hl=ja$ned&topic=$tpc&ncl=$topic_id&start=".($start*30));
			$start += 1;
			$dom = new simple_html_dom();
			$dom->load($page_html);

			foreach($dom->find('div[class^=story from-gxp]') as $story)
			{
				$title  = $story->find('span[class=titletext]');
				$anchor = $story->find('a');
				$source = $story->find('span[class^=source]');
				$url = parse_url($anchor[0]->href);
				#echo $title[0]->plaintext.$endl;	#記事タイトル
				#echo $anchor[0]->href.$endl;		#記事リンク
				#echo html_to_utf8($source[0]->plaintext).$endl;	#ニュースサイト名
				#echo $url['host'].$endl;			#ニュースサイトホスト名
				#echo $endl;

				$result = $db->prepare("INSERT INTO news (topic_id, name, source, url, host) SELECT ?, ?, ?, ?, ? FROM dual WHERE NOT EXISTS (SELECT id FROM news WHERE name = ? AND url = ?)");
				$result->bind_param('sssssss', $topic_id, $title[0]->plaintext, html_to_utf8($source[0]->plaintext), $anchor[0]->href, $url['host'], $title[0]->plaintext, $anchor[0]->href);
				$result->execute();
				$result->close();

				$result = $db->prepare("INSERT INTO crawlers (host) SELECT ? FROM dual WHERE NOT EXISTS (SELECT id FROM crawlers WHERE host = ?)");
				$result->bind_param('ss', $url['host'], $url['host']);
				$result->execute();
				$result->close();
			}

			#echo "===== Page $start END =====".$endl;
			#echo $endl;
			$dom->clear();
		}while(next_check($page_html));

		#die("途中でとめてる。");
	}
?>
