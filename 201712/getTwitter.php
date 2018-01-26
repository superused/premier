<?php
$apiKey = "UhuBsj54DbRnnHGQYaPywHmAi";                                // apiキー
$apiSecret = "e1zSaGoQorVKzyW5W7U8LaAmkUsf3nnjdIHCxh45Y03bnnBj86";    // apiシークレット
$accessToken = "42566916-5CI2BDtmiUQdWFKtTY6ajgxmLlYjURI4ftW2K88h3";  // アクセストークン
$accessTokenSecret = "YHfdFB1RJxIcHQ6XOriX0GZhmZmFpNmI5wegaBIRDflIU"; // アクセストークンシークレット

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$method = "GET";

// user_timelineのパラメータ
$param = [
    "screen_name" => "@realDonaldTrump",
    "tweet_mode" => "extended",
    "count" => 10,
];

// OAuthパラメータ
$paramBase = [
    "oauth_token" => $accessToken,
    "oauth_consumer_key" => $apiKey,
    "oauth_signature_method" => "HMAC-SHA1",
    "oauth_timestamp" => time(),
    "oauth_nonce" => microtime(),
    "oauth_version" => "1.0",
];

// リクエストパラメータ作成
$resultParam = array_merge($param, $paramBase);
ksort($resultParam);
$requestParams = http_build_query($resultParam, "", "&");
$requestParams = str_replace(["+", "%7E"], ["%20", "~"], $requestParams);

// OAuthの署名を作成してパラメータに追加
$resultParam["oauth_signature"] = base64_encode(hash_hmac(
    "sha1",
    urlencode($method) . "&" . urlencode($url) . "&" . urlencode($requestParams),
    urlencode($apiSecret) . "&" . urlencode($accessTokenSecret),
    true
));

// URLにuser_timelineのGETパラメータを追加
if ($param) $url .= "?" . http_build_query($param);

$context = [
    "http" => [
        "method" => $method,
        "header" => [
            "Authorization: OAuth " . http_build_query($resultParam, "", ","),
        ],
    ],
];

// データを取得
$json = file_get_contents($url, false, stream_context_create($context));
if (!$json) exit('error'); // 取得失敗した場合errorを表示

$datas = json_decode($json, true);

// 表示文字列作成
$view = '';
foreach ($datas as $data) {
    $view .= date("<<< Y年m月d日 H時i分s秒 >>>", strtotime($data["created_at"])) . "\n";
    $view .= $data["full_text"] . "\n";
    $view .= "----------------------------\n";
}
echo $view;
