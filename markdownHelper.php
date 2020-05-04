<?php
// Pierre Contri
// transform md5 to html part

class MarkdownAdapter {
	
	public static $patterns = [
			// manage titles
			'/[#]{6}(.+)\n/',
			'/[#]{5}(.+)\n/',
			'/[#]{4}(.+)\n/',
			'/[#]{3}(.+)\n/',
			'/[#]{2}(.+)\n/',
			'/[#]{1}(.+)\n/',
			// manage tables
			//'/([|]{0,1}\s*\w+\s*[|]{0,1})\n[|]{0,1}\s*[- |]+\n/',
			// specific titles
			'/(.*)\n(([=]+.*)\n)/',
			'/(.*)\n(([-][-]+.*)\n)/',
			// paragraph
			'/[\']{3}((.|\n)*)[\']{3}/',  // code bloc
			// lines break
			'/\n([- * _]{3,})\n/',
			// manage caracters
			'/\s\*{3}(.+)\*{3}\s/',  // bolt && italic
			'/\s\*{2}(.+)\*{2}\s/',  // bolt
			'/\s\*{1}(.+)\*{1}\s/',  // italic
			// manage lists
			'/((\w\.\s+)|(\d\.\s+))(.*)\n\n/',        // end of number
			'/((-[^-])|(\*[^*])\s+)(.*)\n\n/',                    // end of list
			'/\n\s*\n(\s*((\w)\.\s+)|((\d)\.\s+))(.*)/', // start of number
			'/\n\n(\s*(-[^-])|(\*[^*])\s+)(.*)/',                 // start of list
			'/((\d\.)|(\w\.)|(-)|(\*)\s+)(.*)/',        // content of list
			'/\n\n/',
			// paragraph
			//'/<.*>((.|\n)*)<\/.*>/',
		];

	public static $replacements = [
			// manage titles
			'<h6>${1}</h6>',
			'<h5>${1}</h5>',
			'<h4>${1}</h4>',
			'<h3>${1}</h3>',
			'<h2>${1}</h2>',
			'<h1>${1}</h1>',
			// specific titles
			'<h1>${1}</h1>',
			'<h2>${1}</h2>',
			// tables
			// paragraph
			'<code>${1}</code>',  // code bloc
			// lines break
			'<hr />',
			// manage caracters
			'<b><i>${1}</i></b>',
			'<b>${1}</b>',
			'<i>${1}</i>',
			// manage lists
			"<li>\${4}</li></ol>\n\n",                                // end of number
			"<li>\${4}</li></ul>\n\n",                                // end of list
			"\n\n<ol type=\"\${3}\" start=\"\${3}\"><li>\${6}</li>",  // start of number
			"\n\n<ul><li>\${4}</li>",                                 // start of list
			'<li>${6}</li>',                                          // content of list
			"<br />\n",
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