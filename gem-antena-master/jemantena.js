// コンテンツを管理するクラス
class RssContent{
    // コンストラクタ
    constructor(siteName,url,title,date){
        this.siteName = siteName;
        this.url = url;
        this.title = title;
        this.date = date;
    }
}

// コンテンツを読み込む
$(function(){
    for(let i = 0; i < siteList.length; i++){
        (function(i){
            $.ajax({
                url: siteList[i],
                cache: false,
                dataType:'xml',
                success: function(xml){
                    let siteName;
                    let url;
                    let title;
                    let date;
                    $(xml).find('item').each(function(){
                        // コンテンツ情報を持ってくる
                        siteName = $(this).find('description').text();
                        url = $(this).find('comments').text().substring(0, $(this).find('comments').text().indexOf('#'))
                        title = $(this).find('title').text();
                        date = $(this).find('pubDate').text();
                        // コンテンツの情報を登録する
                        rssContentList.push(new RssContent(siteName,url,title,date));
                    });
                }
            });
        })(i);
    }
});

// コンテンツの読み込みが終わったらコンテンツを表示する
$(document).ajaxStop(function(){
    sortContentByTime(rssContentList);
    rssContentList.forEach(content => {
        // コンテンツを表示
        $('<div class="boxdef"><li></li></div>').html('<a href="'+content.url+'">'+content.title+'</a><br />'+content.siteName).appendTo('ul#feedList');
    });
});

// コンテンツを日付順にソートする
function sortContentByTime(contentList){
    contentList.sort(function(a,b) {
        return (a.date > b.date ? 1 : -1);
    });
}

// じぇむあんてなのRSS
const siteList = [
    'http://34.97.37.222/gem-antena/jem-feed-creator/jem.xml'
];

// RSSから取得したコンテンツ保存用
const rssContentList = new Array();
