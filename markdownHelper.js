/* *************** */
/* Markdown Helper */
/* Pierre   Contri */
/* *************** */

'use strict';

var rulesRegex = [
	// tables
	{ pattern : /((?:([^\r\n|]*)\|)+(?:([^\r\n|]*)))\r?\n(?:( ?:?-+:? ?)\|)+(?:( ?:?-+:? ?))\r?\n(((?:([^\r\n|]*)\|)+(?:([^\r\n|]*))\r?\n)+)/gm, replacement : "\n<table>\n  <tr><th>{{{{TH}}}}$1{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}$6{{{{TD}}}}</td></tr>\n</table>\n<br/>\n" },
	{ pattern : /(\|(?:([^\r\n|]*)\|)+)\r?\n\|(?:( ?:?-+:? ?)\|)+\r?\n((\|(?:([^\r\n|]*)\|)+\r?\n)+)/gm, replacement : "\n<table>\n  <tr><th>{{{{TH}}}}$1{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}$4{{{{TD}}}}</td></tr>\n</table>\n<br/>\n" },
	// manage titles
	{ pattern : /^[#]{6}(.+)$/gm, replacement : "<h6>$1</h6>\n" },
	{ pattern : /^[#]{5}(.+)$/gm, replacement : "<h5>$1</h5>\n" },
	{ pattern : /^[#]{4}(.+)$/gm, replacement : "<h4>$1</h4>\n" },
	{ pattern : /^[#]{3}(.+)$/gm, replacement : "<h3>$1</h3>\n" },
	{ pattern : /^[#]{2}(.+)$/gm, replacement : "<h2>$1</h2>\n" },
	{ pattern : /^[#]{1}(.+)$/gm, replacement : "<h1>$1</h1>\n" },
	// specific titles
	{ pattern : /^(.*)\n[=]+.*$/gm, replacement : "<h1>$1</h1>\n" },
	{ pattern : /^(.*)\n[-][-]+.*\n$/gm, replacement : "<h2>$1</h2>\n" },
	{ pattern : /[\']{3}((.|\n)+)[\']{3}/gm, replacement : '<code>$1</code>' },  // code bloc
	// lines break
	{ pattern : /\n([- * _]{3,})\n/g,  replacement : '<hr />' },
	// manage caracters
	{ pattern : /\s\*{3}(.+)\*{3}\s/g, replacement : '<b><i>$1</i></b>' },  // bolt && italic
	{ pattern : /\s\*{2}(.+)\*{2}\s/g, replacement : '<b>$1</b>' },         // bolt
	{ pattern : /\s\*{1}(.+)\*{1}\s/g, replacement : '<i>$1</i>' },         // italic
	{ pattern : /\s_(.+)_\s/g,         replacement : '<u>$1</u>' },         // underline
	// manage lists
	{ pattern : /^[ \t]*(\w|\d)\.[ \t]+(.+)$\n\n/gm,        replacement : "<li class=\"endListNb\">$2</li></ol>\n\n" },                                    // end of number
	{ pattern : /\n\n^[ \t]*([\w\d])\.[ \t]+(.+)$/gm,       replacement : "\n\n<ol type=\"$1\" start=\"$1\"><li class=\"startListNb\">$2</li>\n" },        // start of number
	{ pattern : /^[ \t]*[-\*][ \t]+(.+)\n$/gm,              replacement : "<li class=\"endListLi\">$1</li></ul>\n\n" },                                    // end of list
	{ pattern : /\n\n^[ \t]*[-\*][ \t]+(.+)$/gm,            replacement : "\n\n<ul><li class=\"startListLi\">$1</li>\n" },                                 // start of list
	{ pattern : /^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$/gm, replacement : "<li class=\"contentList\">$4</li>\n" },                                         // content of list

	//'/\n\n\n/', "<br />\n",  // new line
	// paragraph
	//'/<.*>((.|\n)*)<\/.*>/', '<p>${1}</p>',
	// links
	{ pattern : /\s\[(.*)\]\((.*)\)\s/gm,  replacement : "<a href=\"$2\" target=\"_blank\">$1</a>" },
	// images
	{ pattern : /\s!\[(.*)\]\((.*)\)\s/gm, replacement : "<img src=\"$2\" alt=\"$1\" />" },
	// videos
];

var $MD = function(htmlText) {

	rulesRegex.forEach(element => htmlText = htmlText.replace(element.pattern, element.replacement));

	// treatment on TH part Table
	htmlText = htmlText.replace(/{{{{TH}}}}\|?(.*)\|?{{{{TH}}}}/gm,
								thelem => thelem
											.replace(/\|?{{{{TH}}}}\|?/g,"")
											.replace(/\|/g, "</th><th>"));

	// treatment on TD part Table
	htmlText = htmlText.replace(/{{{{TD}}}}((.|\n)*?)\n?{{{{TD}}}}/gm,
								tdelem => tdelem
											.replace(/\|?{{{{TD}}}}\|?/gm,"")
											.split('\n')
											.map(tmpline => tmpline.replace(/^\s?\|?(.*?)\|?\s?$/gm, "$1"))
											.join("</td></tr>\n  <tr><td>")
											.replace(/\|/gm, "</td><td>"));

	return htmlText;
};

function transformMdToHtml(divName, contentText) {
	document.getElementById(divName).innerHTML = $MD(contentText);
}