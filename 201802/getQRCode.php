<?php
require_once('./Image_QRCode-0.1.3/Image/QRCode.php');
$url = [
    'http://www.giants.jp/top.html',
    'https://www.amazon.co.jp/dp/B01BHPEC9G',
    'http://www.cosme.net/product/product_id/10023860/top',
];
$cnt = 1;
foreach ($url as $u) {
    $qr = new Image_QRCode();
    $img = $qr->makeCode($u, ['output_type' => 'return']);
    imagepng($img, './img_' . $cnt++ . '.png');
    imagedestroy($img);
}
