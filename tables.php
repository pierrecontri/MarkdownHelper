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

$i = 0;


function transformMdTableToHtml($contentString) {

	// extract TH and TD part
	$contentString = preg_replace(
							'/\|?(([ ]*\|?[ ]*([\d\w]+)[ ]*)+?)\|?\n(\|?[ :]?[-]{2,}[ :]?\|?)+\n(([|]?[ ]*(.+)[ ]*[|]?\n?)+)/m',
							"\n<table>\n  <tr><th>{{{{TH}}}}\${1}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${5}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
							$contentString);
	// treatment on TH part
	$contentString = preg_replace_callback(
							'/{{{{TH}}}}(.*){{{{TH}}}}/m',
							function ($thpatterns) { return str_replace("|", "</th><th>", $thpatterns[1]); },
							$contentString);
	// treatment on TD part
	$contentString = preg_replace_callback(
							'/{{{{TD}}}}((.|\n)*?){{{{TD}}}}/m',
							function ($tdpatterns) {
								$tmplines = preg_replace('/^\s?\|?(.*?)\|?\s?$/m', '${1}', explode("\n", $tdpatterns[1]));
								$fullline  = implode("</td></tr>\n  <tr><td>", $tmplines);
								$fullline  = str_replace("|", "</td><td>", $fullline);
								return $fullline;
							},
							$contentString);

	return $contentString;
}

$simpleTest = file_get_contents("tests.md");

	$smallFiles = explode("\n\n", $simpleTest);
	$smallFiles = array_map("transformMdTableToHtml", $smallFiles);
	$simpleTest = implode("\n\n", $smallFiles);

print($simpleTest);

//file_put_contents("exportTable1.txt", $htmlPage);
//file_put_contents("exportTable2.txt", $htmlPage2);
//file_put_contents("exportTable3.txt", $htmlPage3);

?>