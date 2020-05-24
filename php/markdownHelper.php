<?php
// Pierre Contri
// transform md5 to html part

class MarkdownAdapter {
	
	protected static $patterns = [
			// tables
			'/((?:([^\r\n|]*)\|)+(?:([^\r\n|]*)))\r?\n(?:( ?:?-+:? ?)\|)+(?:( ?:?-+:? ?))\r?\n(((?:([^\r\n|]*)\|)+(?:([^\r\n|]*))\r?\n)+)/m',
			'/(\|(?:([^\r\n|]*)\|)+)\r?\n\|(?:( ?:?-+:? ?)\|)+\r?\n((\|(?:([^\r\n|]*)\|)+\r?\n)+)/m',
			// manage titles
			'/^[#]{6}(.+)$/m',
			'/^[#]{5}(.+)$/m',
			'/^[#]{4}(.+)$/m',
			'/^[#]{3}(.+)$/m',
			'/^[#]{2}(.+)$/m',
			'/^[#]{1}(.+)$/m',
			// specific titles
			'/^(.*)\n[=]+.*$/m',
			'/^(.*)\n[-][-]+.*\n$/m',
			'/[\']{3}((.|\n)+)[\']{3}/m',  // code bloc
			// lines break
			'/\n([- * _]{3,})\n/',
			// manage caracters
			'/\s\*{3}(.+)\*{3}\s/',  // bolt && italic
			'/\s\*{2}(.+)\*{2}\s/',  // bolt
			'/\s\*{1}(.+)\*{1}\s/',  // italic
			'/\s_(.+)_\s/',          // underline
			// manage lists
			'/^[ \t]*(\w|\d)\.[ \t]+(.+)$\n\n/m',         // end of number
			'/\n\n^[ \t]*([\w\d])\.[ \t]+(.+)$/m',        // start of number
			'/^[ \t]*[-\*][ \t]+(.+)\n$/m',               // end of list
			'/\n\n^[ \t]*[-\*][ \t]+(.+)$/m',             // start of list
			'/^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$/m',  // content of list

			//'/\n\n\n/',  // new line
			// paragraph
			//'/<.*>((.|\n)*)<\/.*>/',
			// links
			'/\s\[(.*)\]\((.*)\)\s/m',
			// images
			'/\s!\[(.*)\]\((.*)\)\s/m',
			// videos
		];

	protected static $replacements = [
			// tables
			"\n<table>\n  <tr><th>{{{{TH}}}}\${1}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${6}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
			"\n<table>\n  <tr><th>{{{{TH}}}}\${1}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${4}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
			// manage titles
			"<h6>\${1}</h6>\n",
			"<h5>\${1}</h5>\n",
			"<h4>\${1}</h4>\n",
			"<h3>\${1}</h3>\n",
			"<h2>\${1}</h2>\n",
			"<h1>\${1}</h1>\n",
			// specific titles
			"<h1>\${1}</h1>\n",
			"<h2>\${1}</h2>\n",
			'<code>${1}</code>',  // code bloc
			// lines break
			'<hr />',
			// manage caracters
			'<b><i>${1}</i></b>',  // bolt && italic
			'<b>${1}</b>',         // bolt
			'<i>${1}</i>',         // italic
			'<u>${1}</u>',         // underline
			// manage lists
			"<li class=\"endListNb\">\${2}</li></ol>\n\n",                                    // end of number
			"\n\n<ol type=\"\${1}\" start=\"\${1}\"><li class=\"startListNb\">\${2}</li>\n",  // start of number
			"<li class=\"endListLi\">\${1}</li></ul>\n\n",                                    // end of list
			"\n\n<ul><li class=\"startListLi\">\${1}</li>\n",                                 // start of list
			"<li class=\"contentList\">\${4}</li>\n",                                         // content of list

			//"<br />\n",  // new line
			// paragraph
			//'<p>${1}</p>',
			// links
			"<a href=\"\${2}\" target=\"_blank\">\${1}</a>",
			// images
			"<img src=\"\${2}\" alt=\"\${1}\" />",
			// videos
		];



	public static function transformMdToHtml($contentString) {

		$contentString = preg_replace(self::$patterns, self::$replacements, $contentString);

		// treatment on TH part Table
		$contentString = preg_replace_callback(
								'/{{{{TH}}}}\|?(.*)\|?{{{{TH}}}}/m',
								function ($thpatterns) { return str_replace("|", "</th><th>", $thpatterns[1]); },
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

    public function readMdFile($filename) {
		$contentFile = file_get_contents($filename);
		return self::transformMdToHtml($contentFile);
	}
	
	public function test_md() {
		return self::readMdFile("tests.md");

	}

}