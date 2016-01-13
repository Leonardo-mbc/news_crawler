<?php
    date_default_timezone_set('Asia/Tokyo');    # 応急処置
    include "../../mysql/connect.php";
    $db->select_db("news_datasets");

    $DATE = date("Y-m-d", strtotime("-1 month"));

    if($_GET['limit']) $LIMIT = $_GET['limit'];
        else $LIMIT = 30;

    if($_GET['page']) $PAGE = $_GET['page'];
        else $PAGE = 1;

    $START = ($PAGE - 1) * $LIMIT;

    $result = $db->prepare("SELECT id, topic_id, name, source, url, host, body, updated_at, created_at FROM news WHERE ? < created_at AND 20 < body_length ORDER BY created_at DESC LIMIT ?, ?");
    $result->bind_param('sii', $DATE, $START, $LIMIT);
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

    $result->close();

    $json = json_encode($topics, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    echo $json;
?>
