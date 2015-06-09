# news_crawler
Google Newsのスクレイピングと管理

## クローリングの開始
`crawler/main.php`でクローラが芋づる式に動作。  
`crawler/main.php`はGETで引数を取り、`crawler/main.php?tpc=h`のように使う。  
引数の値はGoogle Newsのトピック引数と同じ。  
以下のサイトを参考に。  
[Googleニュースのトピックと地域版の一覧](http://so-zou.jp/web-app/tech/web-api/google/search/news/parameter.htm#topic)

また、crontabのようにスケージュールを組んで実行したい場合は`crawler/crawling.sh`を叩けば動作する。

## 記事の参照
`debug/topics.php`にてトピックごとに分類された記事一覧が取得できる（html形式）。  
`debug/topics.php?order=d`で最新の数件を取得できる。
