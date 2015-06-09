<html>
	<html>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>extract.php</title>
	</html>
	<body>
		<?php
			include "function.php";
			include "object.php";
			include $loc."mysql/connect.php";
			include $loc."lib/simple_html_dom.php";
			require_once $loc."lib/readability.php";
			
			$crawler_in = $_GET["id"];
			echo "Crawler ID: ".$crawler_in.$endl;
			$crawler_in--;
			
			$result = $db->prepare("SELECT news.id, news.url, crawlers.id, crawlers.method, crawlers.strip FROM news, crawlers WHERE news.host = crawlers.host AND news.host = (SELECT crawlers.host FROM crawlers LIMIT $crawler_in, 1) ORDER BY news.id DESC LIMIT 5");
			
			$result->execute();
			$result->bind_result($id, $url, $crw_id, $method, $strip);
			
			while($result->fetch()) $responses[] = new Response($id, $url, $crw_id, $method, $strip);
			$result->close();
			
			foreach($responses as $index => $response)
			{
				
				echo $response->url.$endl;
				$tmp_body = "";
				
				do
				{
					$loop = false;
					
					$page_html = curl_get($response->url);
					if($page_html)
					{##### PAGE DOWNLOAD COMPLETE #####
						$page_html = rm_stsc(mb_convert_encoding($page_html, "UTF-8", "EUC-JP, SJIS, auto"));
						
						if(!$method)
						{##### METHOD(0)_USE_READABILITY #####
							include "ext/readability.php";
							
							if($response->code)
							{##### READABILITY HAS COMPLETED #####
								include "ext/strip/crw".$crw_id.".php";
								echo border($response->body,50,"-");
								
								if(!$loop)
								{##### FINALISE #####
									include "ext/strip/str".$strip.".php";
									#include "ext/morph.php";
									echo border($response->stripped_body,50,"#");
								}
							}
							else
							{##### READABILITY ERROR OCCURRED #####
								echo $ERROR[0].$endl;
							}
						}
						else
						{##### METHOD(X)_DO_NOT_USE_READABILITY #####
							echo "METHOD(X)_DO_NOT_USE_READABILITY".$endl;
							#include "ext/strip/crw".$crw_id.".php";
						}
					}
					else
					{##### PAGE DOWNLOAD ERROR #####
						echo $ERROR[1].$endl;
					}
				}while($loop);
			}
		?>
	</body>
</html>