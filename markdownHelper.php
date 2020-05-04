<?php
// Pierre Contri
// transform md5 to html part

class MarkdownAdapter {
	
	public static $tablePatern = '/^([|]{0,1}((.+)[|]{0,1})+)\n((([-]+)|([ :|-]{0,1}))+)\n([|]{0,1}(.+)[|]{0,1}\n)+$/m';
	
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

			'/^[ \t]*(\w|\d)\.[ \t]+(.+)$\n\n/m',           // end of number
			'/\n\n^[ \t]*([\w\d])\.[ \t]+(.+)$/m', // start of number

			'/^[ \t]*[-|\*][ \t]+(.+)\n$/m',           // end of list
			'/\n\n^[ \t]*([-\*])[ \t]+(.+)$/m',        // start of list

			'/^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$/m',         // content of list

			//'/\n\n/',  // new line
			// paragraph
			//'/<.*>((.|\n)*)<\/.*>/',
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

			"<li class=\"contentList\">\${4}</li>\n",                                          // content of list

			//"<br />\n",  // new line
			// paragraph
			//'<p>${1}</p>',
		];

	public static function transformMdToHtml($contentFileString) {

        return preg_replace(self::$patterns, self::$replacements, $contentFileString);
	}

    public function readMdFile($filename) {
		$contentFile = file_get_contents($filename);
		return self::transformMdToHtml($contentFile);
	}

	public function hello() {
		return "Yo man !";
	}
	
	public function test_md() {
		$simpleTest = <<<ENDTest
# Title1

test: ceci est cool

## Title2

test2: ceci est vraiment cool

### Title3

test3: ceci est carement cool

___
*****

BigTitle
========

second title 1


MiddleTitle
-----------

second title 2


'''

this is a code bloc specific
if(\$test == 4) {
	println("Calcul: \$test");
}

'''


list:

- l1
- l2
- l3

* ll1
* ll2
* ll3

1. test1
2. test2
3. test3

	a. testA
	b. testB
	c. testC

		2. suitable test 2
		2. suitable test 3
		2. suitable test 4

		3. suitable test 31
		4. suitable test 32
		5. suitable test 33
		6. suitable test 34


ENDTest;

		return self::transformMdToHtml($simpleTest);

	}

}