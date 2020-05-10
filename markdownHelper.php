<?php
// Pierre Contri
// transform md5 to html part

class MarkdownAdapter {
	
	
	public static $patterns = [
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
			// manage lists
			'/^[ \t]*(\w|\d)\.[ \t]+(.+)$\n\n/m',         // end of number
			'/\n\n^[ \t]*([\w\d])\.[ \t]+(.+)$/m',        // start of number
			'/^[ \t]*[-\*][ \t]+(.+)\n$/m',               // end of list
			'/\n\n^[ \t]*([-\*])[ \t]+(.+)$/m',           // start of list
			'/^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$/m',  // content of list

			//'/\n\n\n/',  // new line
			// paragraph
			//'/<.*>((.|\n)*)<\/.*>/',
			// images
			// videos
		];

	public static $replacements = [
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
			// manage lists
			"<li class=\"endListNb\">\${2}</li></ol>\n\n",                                    // end of number
			"\n\n<ol type=\"\${1}\" start=\"\${1}\"><li class=\"startListNb\">\${2}</li>\n",  // start of number
			"<li class=\"endListLi\">\${1}</li></ul>\n\n",                                    // end of list
			"\n\n<ul><li class=\"startListLi\">\${2}</li>\n",                                 // start of list
			"<li class=\"contentList\">\${4}</li>\n",                                         // content of list

			//"<br />\n",  // new line
			// paragraph
			//'<p>${1}</p>',
			// images
			// videos
		];


	public static function transformMdTableToHtml($contentString) {
		// extract TH and TD part
		$contentString = preg_replace(
								'/(\|?(([ ]*\|?[ ]*([\d\w]+)[ ]*)+?)\|?\n(\|?[ :]?[-]{2,}[ :]?\|?)+\n(([|]?[ ]*(.+)[ ]*[|]?\n?)+))/m',
								"\n<table>\n  <tr><th>{{{{TH}}}}\${2}{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\${6}{{{{TD}}}}</td></tr>\n</table>\n<br/>\n",
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


	public static function transformMdToHtml($contentString) {

		$contentString = preg_replace(self::$patterns, self::$replacements, $contentString);

        // tables part
		$smallFiles = explode("\n\n", $contentString);
		//$smallFiles = array_map("self::transformMdTableToHtml", $smallFiles);
		$contentString2 = implode("\n\n", $smallFiles);

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