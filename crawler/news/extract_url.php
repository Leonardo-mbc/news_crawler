<html>
    <html>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>extract_url.php</title>
    </html>
    <body>
        <?php
            require_once $loc."lib/simple_html_dom.php";
            require_once $loc."lib/readability.php";
            
            $GLOBALS['logger'] = "enable";
            
            $parsed_url = parse_url($article->url);
            
            $result = $db->prepare("SELECT crawlers.id, crawlers.method, crawlers.strip FROM crawlers WHERE crawlers.host = ? LIMIT 1");
            $result->bind_param("s", $parsed_url['host']);
            
            $result->execute();
            $result->bind_result($crw_id, $crw_method, $crw_strip);
            
            while($result->fetch()) $responses[] = new Response(null, $url, $crw_id, $crw_method, $crw_strip);
            $result->close();
            
            foreach($responses as $index => $response)
            {
                #echo $response->url.$endl;
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
                                include "ext/strip/crw".$response->crw_id.".php";
                                #echo border($response->body,50,"-");
                                
                                if(!$loop)
                                {##### FINALISE #####
                                    include "ext/strip/str".$response->crw_strip.".php";
                                    #include "ext/morph.php";
                                    #echo border($response->stripped_body,50,"#");
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