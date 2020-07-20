<?php
// Pierre Contri
// transform md5 to html part

class MarkdownAdapter {
	
	protected static $patterns = [
			// tables
			'/((?:([^\r\n|]*)\|)+(?:([^\r\n|]*)))\r?\n(?:( ?:?-+:? ?)\|)+(?:( ?:?-+:? ?))\r?\n(((?:([^\r\n|]*)\|)+(?:([^\r\n|]*))\r?\n)+)/m',
			'/(\|(?:([^\r\n|]*)\|)+)\r?\n\|(?:( ?:?-+:? ?)\|)+\r?\n((\|(?:([^\r\n|]*)\|)+\r?\n)+)/m',
			// code
			'/[\']{3}((.|\n)+?)[\']{3}/m',  // code bloc
			// manage lists
			'/^[ \t]*(\w|\d)\.[ \t]+(.+)$\n/m',         // end of number
			'/^\n[ \t]*([\w\d])\.[ \t]+(.+)$/m',        // start of number
			'/^[ \t]*[-\*][ \t]+(.+)\n$/m',               // end of list
			'/^\n[ \t]*[-\*][ \t]+(.+)$/m',             // start of list
			'/^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$/m',  // content of list
			// manage titles
			'/^\r?\n?[#]{6}\s*(.+)\r?\n?$/m',
			'/^\r?\n?[#]{5}\s*(.+)\r?\n?$/m',
			'/^\r?\n?[#]{4}\s*(.+)\r?\n?$/m',
			'/^\r?\n?[#]{3}\s*(.+)\r?\n?$/m',
			'/^\r?\n?[#]{2}\s*(.+)\r?\n?$/m',
			'/^\r?\n?[#]{1}\s*(.+)\r?\n?$/m',
			// specific titles
			'/^\r?\n?(.*)\r?\n[=]+.*\r?\n?$/m',
			'/^\r?\n?(.*)\r?\n[-][-]+.*\r?\n?$/m',
			// lines break
			'/\n([-*_]{3,})\n/',
			// manage caracters
			'/([\W\s_])\*{3}(.*?)\*{3}([\W\s_])/',  // bolt && italic
			'/([\W\s_])\*{2}(.*?)\*{2}([\W\s_])/',  // bolt
			'/([\W\s_])\*{1}(.*?)\*{1}([\W\s_])/',  // italic
			'/([\W\s])_(.*?)_([\W\s])/',            // underline
			'/(.*?\r?\n)(\r?\n)/m',  // new line
			// paragraph
			//'/<.*>((.|\n)*)<\/.*>/',
			// links
			'/(\s)\[(.*)\]\((.*)\)\s?/m',
			// images
			'/\s!\[(.*)\]\((.*)\)\s/m',
			// videos
			//'/ /', //spaces
		];

	protected static $replacements = [
			// tables
			"\n<table>\n  <tr><th>{{{{TH}}}}\${1}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${6}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
			"\n<table>\n  <tr><th>{{{{TH}}}}\${1}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${4}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
			// code
			'<pre><code>${1}</code></pre>',  // code bloc
			// manage lists
			"<li class=\"endListNb\">\${2}</li></ol>\n",                                      // end of number
			"<ol type=\"\${1}\" start=\"\${1}\"><li class=\"startListNb\">\${2}</li>",  // start of number
			"<li class=\"endListLi\">\${1}</li></ul>\n",                                      // end of list
			"<ul><li class=\"startListLi\">\${1}</li>",                                 // start of list
			"<li class=\"contentList\">\${4}</li>",                                         // content of list
			// manage titles
			"<h6>\${1}</h6>",
			"<h5>\${1}</h5>",
			"<h4>\${1}</h4>",
			"<h3>\${1}</h3>",
			"<h2>\${1}</h2>",
			"<h1>\${1}</h1>",
			// specific titles
			"<h1>\${1}</h1>",
			"<h2>\${1}</h2>",
			// lines break
			'<hr />',
			// manage caracters
			'${1}<b><i>${2}</i></b>${3}',  // bolt && italic
			'${1}<b>${2}</b>${3}',         // bolt
			'${1}<i>${2}</i>${3}',         // italic
			'${1}<u>${2}</u>${3}',         // underline
			"\${1}<br />\n",  // new line
			// paragraph
			//'<p>${1}</p>',
			// links
			"\${1}<a href=\"\${3}\" target=\"_blank\">\${2}</a>",
			// images
			"<img src=\"\${2}\" alt=\"\${1}\" />",
			// videos
			//"&nbsp;", // spaces

		];

	public static function transformMdToHtml($contentString) {

		$contentString = preg_replace(self::$patterns, self::$replacements, $contentString);

		// treatment on TH part Table
		$contentString = preg_replace_callback(
								'/{{{{TH}}}}\|?.*\|?{{{{TH}}}}/m',
								function ($thpatterns) { 
								$thelems = $thpatterns[0];
									$thelems = preg_replace('/\|?{{{{TH}}}}\|?/', "", $thelems);
									return str_replace("|", "</th><th>", $thelems);
								},
								$contentString);

		// treatment on TD part Table
		$contentString = preg_replace_callback(
								'/{{{{TD}}}}((.|\n)*?)\n?{{{{TD}}}}/m',
								function ($tdpatterns) {
									$tmplines = preg_replace('/^\s?\|?(.*?)\|?\s?$/m', '${1}', array_filter(explode("\n", $tdpatterns[1]), function ($tmpLine) { return $tmpLine != ""; }));
									$fullline  = implode("</td></tr>\n  <tr><td>", $tmplines);
									$fullline  = str_replace("|", "</td><td>", $fullline);
									return $fullline;
								},
								$contentString);

		return $contentString;
	}
	
	public static function test_md() {

		$sampleText = <<<TestTxt
		
This is a simple test for MardownHelper

***

# Title1

under the title 1

## Title 2

under thte title 2


List:

- l1
- l2
- l3
- l4


TestTxt;

		return self::transformMdToHtml($sampleText);

	}

}