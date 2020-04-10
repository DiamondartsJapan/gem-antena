<?php

    // ライブラリの読み込み
    require_once "./Item.php";
    require_once "./Feed.php";
    require_once "./RSS2.php";

    // エイリアスの作成
    use \FeedWriter\RSS2;

    // インスタンスの作成
    $feed = new RSS2;

    //RSS取得URLリスト
    $xmlList = array(
        'https://sekaiju.org/index.php/feed',
        'https://honyakusitade.com/feed'
    );

    //記事リスト
    $articleList = array();

    foreach ($xmlList as $url) {
        $getRSS = simplexml_load_file($url);

        foreach ($getRSS as $rss) {
            $feed->setTitle("じぇむあんてな");
            $feed->setLink("https://jem-antena.com");
        }

        // 各サイト名を持ってくる
        $siteName = $getRSS->channel->title;
        foreach ($getRSS->channel->item as $items){
            $title = $items->title;
            $link = $items->link;
            //MEMO: Unixタイムスタンプに変換しないと入らない
            $date = strtotime($items->pubDate);

            //$article = new Article($title,$link,$date);
            // 記事情報が入った配列を作る
            $articleList[] = array("title"=>$title,"link"=>$link,"date"=>$date,"description"=>$siteName);

            /* 動作確認したら消すこと

            $item = $feed->createNewItem() ;
            $item->setTitle($title);
            $item->setLink($link);
            $item->setId($link,true);
            //説明文にサイト名を入れる
            $item->setDescription($blogTitle) ;
            $item->setDate($date) ;    // 更新日時
            $item->setAuthor( "" , "hoge@diamondarts.page" ) ;    // 著者の連絡先(E-mail)
            $feed->addItem($item);
            */
        }
    }

    //print_r($articleList);
    foreach ($articleList as $key => $value) {
        $sort[$key] = $value['date'];
    }

    array_multisort($sort, SORT_DESC, $articleList);

    // 記事をセットする
    foreach ($articleList as $a) {
        $item = $feed->createNewItem();
        $item->setTitle($a["title"]);
        $item->setLink($a["link"]);
        $item->setId($a["link"],true);
        $item->setDate($a["date"]);
        $item->setDescription($a["description"]);
        $feed->addItem($item);
    }

    $xml = $feed->generateFeed() ;

    // ファイルの保存場所を設定
    $file = "./jem.xml" ;

    // ファイルの保存を実行
    @file_put_contents( $file , $xml ) ;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="robots" content="noindex,nofollow">

        <!-- ビューポートの設定 -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>PHP Creator</title>
    </head>
    <body>
        rss2 file generated.
        <br />
        本番環境では消すこと。
    </body>
</html>
