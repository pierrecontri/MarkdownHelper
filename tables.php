<?php

		$simpleTest = <<<ENDTest

H1 | H2 | H3
-- | -- | --
c1 | c2 | c3
c4 | c5 | c6
c7 | c8 | c9


| H1 | H2 | H3 |
| -- | -- | -- |
| c1 | c2 | c3 |
| c4 | c5 | c6 |
| c7 | c8 | c9 |



| Test |
| ---- |
| TX   |


ENDTest;


function replaceTH($thpatterns) {
	return str_replace("|",  "</th><th>", $thpatterns[1]);
}

function replaceTD($tdpatterns) {
	
	$tmplines = preg_replace('/^\s?\|?(.*?)\|?\s?$/m', '${1}', explode("\n", $tdpatterns[1]));
	$fullline  = implode("</td></tr>\n  <tr><td>", $tmplines);
	$fullline  = str_replace("|", "</td><td>", $fullline);
	
	return $fullline;
}

function transformMdArrayHtml($contentString) {

	$contentString = preg_replace(
							'/\|?(([ ]*\|?[ ]*([\d\w]+)[ ]*)+?)\|?\n(\|?[ :]?[-]{2,}[ :]?\|?)+\n(([|]?[ ]*(.+)[ ]*[|]?\n?)+)/m',
							"\n<table>\n  <tr><th>{{{{TH}}}}\${1}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${5}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
							$contentString);

	$contentString = preg_replace_callback(
							'/{{{{TH}}}}(.*){{{{TH}}}}/m',
							"replaceTH",
							$contentString);

	$contentString = preg_replace_callback(
							'/{{{{TD}}}}((.|\n)*?)\n{{{{TD}}}}/m',
							"replaceTD",
							$contentString);

	return $contentString;
}

print(transformMdArrayHtml($simpleTest));

//file_put_contents("exportTable1.txt", $htmlPage);
//file_put_contents("exportTable2.txt", $htmlPage2);
//file_put_contents("exportTable3.txt", $htmlPage3);

?>