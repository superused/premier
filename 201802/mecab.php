<?php
// テキストデータ取得
$txt = file_get_contents('./data.txt');
// 形態素解析を行う
$parse = (new MeCab\Tagger())->parse($txt);
// 行毎に配列に入れる
$parseArray = explode("\n", $parse);

$words = [];
foreach ($parseArray as $p) {
    $value = explode("\t", $p);
    if (!isset($value[0]) || !isset($value[1])) continue;
    $word = $value[0]; // 文字
    $desc = explode(',', $value[1]); // 説明
    // 名詞のみを配列にいれる
    if (isset($desc[0]) && $desc[0] == '名詞') {
        if (!array_key_exists($word, $words)) $words[$word] = 0;
        $words[$word]++;
    }
}
// 数の多い順にソート
arsort($words);
$view = '';
foreach ($words as $key => $val) {
    $view .= $key . "\t\t\t" . $val . "\n";
}
// 表示
echo $view;
