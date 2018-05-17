<?php
// 一つのサイトからURLを取得します
define('START_URL', 'https://no1s.biz/');
$crawledUrls = [];
crawlStart($crawledUrls);
$view = '';
foreach ($crawledUrls as $url => $title) {
	$view .= $url . '	' . $title . "\n";
}
file_put_contents('./' . date('YmdHis') . '.txt', $view);

function crawlStart(&$crawledUrls) {
	$crawlFlg = false;
	if (count($crawledUrls) == 0) {
		crawl(START_URL, $crawledUrls);
		$crawlFlg = true;
	} else {
		foreach ($crawledUrls as $crawledUrl => $title) {
			// タイトルが取得できていなければそのURLのクローリングを行う
			if ($title === false) {
				crawl($crawledUrl, $crawledUrls);
				$crawlFlg = true;
			}
		}
	}
	// 全てのURLのタイトルを取得するまでクローリングを開始し続ける
	if ($crawlFlg) {
		crawlStart($crawledUrls);
	}
}

function crawl($url, &$crawledUrls) {
	$html = file_get_contents($url);
	$dom = new DOMDocument();
	@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'sjis'));
	$xpath = new DOMXPath($dom);

	foreach ($xpath->query('//a') as $node) {
		$href = $xpath->evaluate('string(@href)', $node);
		if (strpos($href, START_URL) === 0) {
			if (substr($href, -1) != '/') $href .= '/';
			if (substr($href, -2) == '//') $href = substr($href, 0, -1);
			if (!array_key_exists($href, $crawledUrls)) {
				$crawledUrls[$href] =  false;
			}
		}
	}
	if (isset($crawledUrls[$url])) {
		// 現在のURLのタイトルを取得する
		$crawledUrls[$url] = getTitle($html);
	}
}


function getTitle($html){
	$title = '';
	$regex = '@<title>([^<]++)</title>@i';
	$order = 'ASCII,JIS,UTF-8,CP51932,SJIS-win';
	if (
		preg_match($regex, mb_convert_encoding($html, 'UTF-8', $order), $result) &&
		isset($result[1])
	) {
		// 改行を削除
		$title = str_replace(PHP_EOL, '', $result[1]);
	}
	return $title;
}
