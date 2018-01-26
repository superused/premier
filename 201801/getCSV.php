<?php
// //POSTデータ
// $data = array(
//     // "_method"  => "POST",
//     // "_csrfToken"  => "b9eb4a5472b4b6426f7e3aa9cac5c22b01d922d424dc7782e4d60e253903ade2cde05d5520e4c14d35ef85c7c284176c26f63a283a82528691cba13e8b898699",
//     // "harumafuji" => "v0addlp99b8a7bvnat6233bm9q",
//     // "email" => "micky.mouse@no1s.biz",
//     // "password" => "micky",
//     "_method" => "POST",
//     "_csrfToken" => "b9eb4a5472b4b6426f7e3aa9cac5c22b01d922d424dc7782e4d60e253903ade2cde05d5520e4c14d35ef85c7c284176c26f63a283a82528691cba13e8b898699",
//     "email" => "micky.mouse@no1s.biz",
//     "password" => "micky",
// );
// $data = http_build_query($data, "", "&");
//
// //header
// $header = array(
//     // "Content-Type: application/x-www-form-urlencoded",
//     // "Content-Length: ".strlen($data)
//     "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
//     "Accept-Encoding:gzip, deflate, br",
//     "Accept-Language:ja,en-US;q=0.9,en;q=0.8",
//     "Cache-Control:max-age=0",
//     "Connection:keep-alive",
//     "Content-Length:196",
//     "Content-Type:application/x-www-form-urlencoded",
//     "Cookie:csrfToken=b9eb4a5472b4b6426f7e3aa9cac5c22b01d922d424dc7782e4d60e253903ade2cde05d5520e4c14d35ef85c7c284176c26f63a283a82528691cba13e8b898699; harumafuji=v0addlp99b8a7bvnat6233bm9q",
//     "Host:premier.no1s.biz",
//     "Origin:https://premier.no1s.biz",
//     "Referer:https://premier.no1s.biz/"
// );
//
// $context = array(
//     "http" => array(
//         "method"  => "POST",
//         "header"  => implode("\r\n", $header),
//         "content" => $data
//     )
// );
//
// $url = 'https://premier.no1s.biz/?redirect=%2admin';
// var_dump(file_get_contents($url, false, stream_context_create($context)));


$count = 0;
$csv = '';
while(++$count) {
    $url = 'https://premier.no1s.biz/admin?page=' . $count;

    //header
    $header = array(
        // "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
        // "Accept-Encoding:gzip, deflate, br",
        // "Accept-Language:ja,en-US;q=0.9,en;q=0.8",
        // "Cache-Control:max-age=0",
        // "Connection:keep-alive",
        "Cookie:csrfToken=b9eb4a5472b4b6426f7e3aa9cac5c22b01d922d424dc7782e4d60e253903ade2cde05d5520e4c14d35ef85c7c284176c26f63a283a82528691cba13e8b898699; harumafuji=lh4psb464d7c1ivi8mibcas545",
        // "Host:premier.no1s.biz",
        // "Referer:https://premier.no1s.biz/",
        // "Upgrade-Insecure-Requests:1",
    );
    $context = array(
        "http" => array(
            "method"  => "GET",
            "header"  => implode("\r\n", $header),
        )
    );
    $html = @file_get_contents($url, false, stream_context_create($context));
    if (!$html) break;

    $dom = new DOMDocument();
    // html取得（エラー回避）
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'utf8'));

    $xpath = new DOMXPath($dom);

    foreach ($xpath->query('//table/tr') as $key => $node) {
        if ($key == 0) continue;
        $val = array_map(function($i) use ($xpath, $node) {
            return '"' . $xpath->evaluate('string(td[position()=' . $i . '])', $node) . '"';
        }, range(1, 3));
        $csv .= implode(',', $val) . "\n";
    }
}
echo $csv;
