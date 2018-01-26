<?php
$url = 'https://www.google.co.jp/search?q=%E6%B2%96%E7%B8%84%E3%80%80%E9%AB%98%E7%B4%9A%E3%83%9B%E3%83%86%E3%83%AB';
$html = file_get_contents($url);

$dom = new DOMDocument();

// html取得（エラー回避）
@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'sjis'));

$xpath = new DOMXPath($dom);

// タイトルとURLを抜き出して表示処理
$view = "";
foreach ($xpath->query('//div[@class="g"]/h3[@class="r"]') as $node) {
    // タイトル取得
    $title = $xpath->evaluate('string()', $node);

    $href = $xpath->evaluate('string(a/@href)', $node);
    $param = parse_url(urldecode($href), PHP_URL_QUERY);
    parse_str($param, $output);
    // URL取得
    $url = $output['q'];

    // 表示文字列作成
    $view .= "<<< " . $title . " >>>\n" . $url . "\n---------------\n";
}
echo $view;
