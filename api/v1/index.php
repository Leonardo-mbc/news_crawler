<?php
    date_default_timezone_set('Asia/Tokyo');    # 応急処置
    include "../../mysql/connect.php";
    $db->select_db("news_datasets");

    if($_GET['order'] == 'a') $ORDER = "ASC";
        else $ORDER = 'DESC';

    if($_GET['date']) $DATE = htmlspecialchars($_GET['date'], ENT_QUOTES, "utf-8");
        else $DATE = date('Y-m');

    if($_GET['limit']) $LIMIT = $_GET['limit'];
        else $LIMIT = 30;

    if($_GET['page']) $PAGE = $_GET['page'];
        else $PAGE = 1;

    $START = ($PAGE - 1) * $LIMIT;

    if($_GET['topic_id']) {
        $topic = htmlspecialchars($_GET['topic_id'], ENT_QUOTES, "utf-8");

        $result = $db->prepare("SELECT id, topic_id, name, source, url, host, body, updated_at, created_at FROM news WHERE topic_id = ? AND substring(created_at, 1, 7) = ? ORDER BY created_at $ORDER LIMIT ?, ?");
        $result->bind_param('ssii', $topic, $DATE, $START, $LIMIT);
        $result->execute();

        # -> output news
        $result->bind_result($id, $topic_id, $name, $source, $url, $host, $body, $updated_at, $created_at);

        $topics = array('news' => array());
        while($result->fetch()) {
            $topics["news"][] = array(
                'id' => $id,
                'topic_id' => $topic_id,
                'title' => $name,
                'source' => $source,
                'url' => $url,
                'host' => $host,
                'body' => $body,
                'updated_at' => $updated_at,
                'created_at' => $created_at
            );
        }
    } else if($_GET['news_id']) {
        $news = htmlspecialchars($_GET['news_id'], ENT_QUOTES, "utf-8");

        $result = $db->prepare("SELECT id, topic_id, name, source, url, host, body, updated_at, created_at FROM news WHERE id = ? LIMIT 1");
        $result->bind_param('i', $news);
        $result->execute();

        # -> output news
        $result->bind_result($id, $topic_id, $name, $source, $url, $host, $body, $updated_at, $created_at);

        $topics = array('news' => array());
        while($result->fetch()) {
            $topics["news"][] = array(
                'id' => $id,
                'topic_id' => $topic_id,
                'title' => $name,
                'source' => $source,
                'url' => $url,
                'host' => $host,
                'body' => $body,
                'updated_at' => $updated_at,
                'created_at' => $created_at
            );
        }
    } else {
        $result = $db->prepare("SELECT topics.id, topics.name, topics.updated_at, topics.created_at, count(news.topic_id) FROM topics, news WHERE topics.id = news.topic_id AND substring(news.created_at, 1, 7) = ? GROUP BY topics.id ORDER BY topics.created_at $ORDER LIMIT ?, ?");
        $result->bind_param('sii', $DATE, $START, $LIMIT);
        $result->execute();
        echo $result->error.$endl;

        # -> output topics
        $result->bind_result($id, $name, $updated_at, $created_at, $num);

        $topics = array('topics' => array());
        while($result->fetch()) {
            $topics["topics"][] = array(
                'id' => $id,
                'title' => $name,
                'updated_at' => $updated_at,
                'created_at' => $created_at,
                'quant' => $num
            );
        }
    }

    $result->close();

    $json = json_encode($topics, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    echo $json;
?>
