<html>
    <html>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>extract.php</title>
    </html>
    <body>
        <?php
            include "header.php";
            include $loc."crawler/object.php";
            include $loc."lib/simple_html_dom.php";
            require_once $loc."lib/readability.php";
            $db->select_db("matome_datasets");

            $result = $db->prepare("SELECT threads.id, threads.url, sources.id, sources.method, sources.strip FROM threads LEFT JOIN sources ON threads.source_id = sources.id WHERE ? <= threads.created_at AND threads.body_crawled = FALSE LIMIT 50");
            $result->bind_param('s', date("Y-m-d"));
            $result->execute();
            $result->bind_result($id, $url, $crw_id, $method, $strip);

            while($result->fetch()) $responses[] = new Response($id, $url, $crw_id, $method, $strip);
            $result->close();

            if($responses) {
                foreach($responses as $index => $response) {

                    echo $response->url.$endl;
                    $tmp_body = "";

                    do {
                        $loop = false;

                        $page_html = curl_get($response->url);
                        if($page_html["body"])
                        {##### PAGE DOWNLOAD COMPLETE #####
                            $page_html["body"] = mb_convert_encoding($page_html["body"], "UTF-8", "EUC-JP, SJIS, auto");

                            if(!$method)
                            {##### METHOD(0)_USE_READABILITY #####
                                include $loc."crawler/readability.php";

                                if($response->code)
                                {##### READABILITY HAS COMPLETED #####
                                    #include "ext/strip/crw".$crw_id.".php";
                                    #echo border($response->body,50,"-");

                                    if(!$loop)
                                    {##### FINALISE #####
                                        include "ext/strip/str".$response->crw_strip.".php";
                                        #include "ext/morph.php";

                                        $body_length = mb_strlen($response->stripped_body, "UTF-8");
                                        if(100 < $body_length)
                                        {
                                            $result = $db->prepare("UPDATE threads SET body = ?, body_length = ? WHERE id = ?");
                                            $result->bind_param('sii', $response->stripped_body, $body_length, $response->id);
                                            $result->execute();
                                            echo $result->error.$endl;
                                            $result->close();
                                        }
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
                    } while($loop);

                    $result = $db->prepare("UPDATE threads SET body_crawled = TRUE WHERE id = ?");
                    $result->bind_param('i', $response->id);
                    $result->execute();
                    echo $result->error.$endl;
                    $result->close();
                }
            }
        ?>
    </body>
</html>
