<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	include "../mysql/connect.php";
		
	$result = $db->prepare("SELECT response_code, crawling_at FROM crawling_log ORDER BY crawling_at DESC LIMIT 0, 30");
	$result->execute();
	$result->bind_result($code, $time);
	
	while($result->fetch())
	{
		echo $code.", at ".$time."<br />";
	}
	
	$result->close();
?>