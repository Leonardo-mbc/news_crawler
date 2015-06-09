<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	include "../mysql/connect.php";
	
	if($_GET['order'] == 'd') $ORDER = "DESC";
		else $ORDER = 'ASC';
		
	if($_GET['limit']) $LIMIT = $_GET['limit'];
		else $LIMIT = 30;
		
	if($_GET['page']) $PAGE = $_GET['page'];
		else $PAGE = 1;
	
	$START = ($PAGE-1)*$LIMIT;
	$result = $db->prepare("SELECT topics.id, topics.name, count(news.topic_id) FROM topics, news WHERE topics.id = news.topic_id GROUP BY topics.id ORDER BY topics.updated_at $ORDER LIMIT ?, ?");
	$result->bind_param('ii', $START, $LIMIT);
	$result->execute();
	$result->bind_result($id, $name, $num);
	
	while($result->fetch())
	{
		echo "<a href='news.php?id=$id'>$name</a> $num<br/>";
	}
	
	$result->close();
?>