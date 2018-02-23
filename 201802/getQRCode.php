<?php
$url = [
    'http://www.giants.jp/top.html',
    'https://www.amazon.co.jp/dp/B01BHPEC9G',
    'http://www.cosme.net/product/product_id/10023860/top',
];
$cnt = 1;
foreach ($url as $u) {
    $qrCodeUrl = 'https://chart.apis.google.com/chart?cht=qr&chs=150&chl=' . urlencode($u);
    $img = file_get_contents($qrCodeUrl);
    file_put_contents('./img_' . $cnt . '.png', $img);
    $cnt++;
}
