<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
    include "../mysql/connect.php";

    if($_GET['order'] == 'd') $ORDER = "DESC";
        else $ORDER = 'ASC';

    if($_GET['date']) $DATE = htmlspecialchars($_GET['date'], ENT_QUOTES, "utf-8");
        else $DATE = date('Y-m');

    if($_GET['limit']) $LIMIT = $_GET['limit'];
        else $LIMIT = 30;

    if($_GET['page']) $PAGE = $_GET['page'];
        else $PAGE = 1;

    $START = ($PAGE-1)*$LIMIT;
    $result = $db->prepare("SELECT topics.id, topics.name, count(news.topic_id) FROM topics, news WHERE topics.id = news.topic_id AND substring(news.updated_at, 1, 7) = ? GROUP BY topics.id ORDER BY topics.updated_at $ORDER LIMIT ?, ?");
    $result->bind_param('sii', $DATE, $START, $LIMIT);
    $result->execute();
    $result->bind_result($id, $name, $num);

    while($result->fetch()) {
        echo "<a href='news.php?id=$id'>$name</a> $num<br/>";
    }

    $result->close();
?>
