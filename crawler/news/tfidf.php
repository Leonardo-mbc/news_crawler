<html>
	<html>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>extract_url.php</title>
	</html>
	<body>
		<?php
			include "function.php";
			include "object.php";
			include $loc."mysql/connect.php";
			
			$GLOBALS['logger'] = "enable";
			
			$tpc = "dj7C0p7QkS6_EHM";
			
			$result = $db->prepare("SELECT news.name, news.url FROM news WHERE news.topic_id = ?");
			$result->bind_param('s', $tpc);
			$result->execute();
			$result->bind_result($title, $url);
			
			while($result->fetch()) $articles[] = new News($title, $url);
			$result->close();
			
			foreach($articles as $key => $article)
			{
				include "extract_url.php";
				include "ext/morph.php";
				
				die("!!! STOP !!!");
			}
			
		?>
	</body>
</html>