<?php
// ライブラリファイルの読み込み （パス指定し直す）
require_once 'phpexcel/PHPExcel/IOFactory.php';

// 読み込むファイル
$readFile = 'data.xlsx';

if (!file_exists($readFile)) exit('error');

$obj = PHPExcel_IOFactory::load($readFile);
$datas = $obj->getActiveSheet()->toArray(null, true, false, false);

// 上の空白行を削除
$datas = array_slice($datas, 2);

$makeDatas = [];
// 名前の列を合わせる為に、最大文字数を取得する
$maxLength = 0;
foreach ($datas as $key => $data) {
    if ($key == 0) {
        $data[2] = '名前';
    } else {
        $data[2] = $data[1] . ' ' . $data[2];
    }
    // 名前を合わせると、最初の値は不要なので削除
    $data = array_slice($data, 2);
    $makeDatas[$key] = $data;

    // 文字数を取得し、最大文字数と比較
    $length = strlen($data[0]);
    if ($maxLength < $length) $maxLength = $length;
}

foreach ($makeDatas as $key => $data) {
    // 名前の欄は最大文字数に合わせて足りない分は空白を埋める
    $data[0] = str_pad($data[0], $maxLength);

    // 合計点を求める
    if ($key == 0) {
        $data[] = '合計点';
    } else {
        $points = array_slice($data, 2);
        $sum = 0;
        foreach ($points as $point) {
            if (ctype_digit((string)$point) && $point >= 0 && $point <= 100) {
                $sum += $point;
            } else {
                // 0~100の数値でない値があれば、エラーを表示
                $sum = 'Error';
                break;
            }
        }
        $data[] = $sum;
    }

    echo implode("\t", $data) . "\n";
}
