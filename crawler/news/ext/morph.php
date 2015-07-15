<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<?php
    ini_set('mecab.default_userdic', "/home/leonardo/local/lib/mecab/dic/hatena.dic");
    
    $mecab = new MeCab();
    $text = mb_convert_kana($response->stripped_body, "n", "UTF-8");
    
    $nodes = $mecab->parse(mb_convert_encoding($text, "EUC-JP", "UTF-8"));
    $nodes =  array_diff(explode("\n", preg_replace("/EOS/", "", mb_convert_encoding($nodes, "UTF-8", "EUC-JP"))), array(""));
    
    foreach($nodes as $key => $value)
    {
        $value = explode("\t", $value);
        $word = $value[0];
        $surface = $value[1];
        
        if(preg_match("/^名詞/", $surface))
        {
            echo "<li>".$word." : ".$surface."</li>";
        }
    }
?>