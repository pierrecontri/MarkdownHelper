/* *************** */
/* Markdown Helper */
/* Pierre   Contri */
/* *************** */

'use strict';

var markdownHelper = {};

markdownHelper.rulesRegex = [
	// tables
	{ pattern : /((?:([^\r\n|]*)\|)+(?:([^\r\n|]*)))\r?\n(?:( ?:?-+:? ?)\|)+(?:( ?:?-+:? ?))\r?\n(((?:([^\r\n|]*)\|)+(?:([^\r\n|]*))\r?\n)+)/gm, replacement : "<table>\n  <tr><th>{{{{TH}}}}$1{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}$6{{{{TD}}}}</td></tr>\n</table>\n" },
	{ pattern : /(\|(?:([^\r\n|]*)\|)+)\r?\n\|(?:( ?:?-+:? ?)\|)+\r?\n((\|(?:([^\r\n|]*)\|)+\r?\n)+)/gm, replacement : "<table>\n  <tr><th>{{{{TH}}}}$1{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}$4{{{{TD}}}}</td></tr>\n</table>\n" },
	// code
	{ pattern : /[\']{3}((.|\n)*?)[\']{3}/gm,               replacement : '<pre><code>$1</code></pre>' },                                       // code bloc
	// manage lists
	{ pattern : /^[ \t]*(\w|\d)\.[ \t]+(.+)\n$/gm,          replacement : "<li class=\"endListNb\">$2</li></ol>\n" },                           // end of number
	{ pattern : /^\n[ \t]*([\w\d])\.[ \t]+(.+)$/gm,         replacement : "<ol type=\"$1\" start=\"$1\"><li class=\"startListNb\">$2</li>" },   // start of number
	{ pattern : /^[ \t]*[-\*][ \t]+(.+)\n$/gm,              replacement : "<li class=\"endListLi\">$1</li></ul>\n" },                           // end of list
	{ pattern : /^\n[ \t]*[-\*][ \t]+(.+)$/gm,              replacement : "<ul><li class=\"startListLi\">$1</li>" },                            // start of list
	{ pattern : /^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$/gm, replacement : "<li class=\"contentList\">$4</li>" },                                // content of list
	// manage titles
	{ pattern : /^\r?\n?#{6}\s*(.+)\r?\n?$/gm,              replacement : "<h6>$1</h6>" },
	{ pattern : /^\r?\n?#{5}\s*(.+)\r?\n?$/gm,              replacement : "<h5>$1</h5>" },
	{ pattern : /^\r?\n?#{4}\s*(.+)\r?\n?$/gm,              replacement : "<h4>$1</h4>" },
	{ pattern : /^\r?\n?#{3}\s*(.+)\r?\n?$/gm,              replacement : "<h3>$1</h3>" },
	{ pattern : /^\r?\n?#{2}\s*(.+)\r?\n?$/gm,              replacement : "<h2>$1</h2>" },
	{ pattern : /^\r?\n?#{1}\s*(.+)\r?\n?$/gm,              replacement : "<h1>$1</h1>" },
	{ pattern : /^\r?\n?(.*)\n==+.*\r?\n?$/gm,              replacement : "<h1>$1</h1>" },
	{ pattern : /^\r?\n?(.*)\n--+.*\r?\n?$/gm,              replacement : "<h2>$1</h2>" },
	// lines break
	{ pattern : /\r?\n([-*_]{3,})\r?\n/g,                   replacement : '<hr />' },
	// manage caracters
	{ pattern : /([\W\s_])\*{3}(.*?)\*{3}([\W\s_])/g,       replacement : '$1<b><i>$2</i></b>$3' },  // bolt && italic
	{ pattern : /([\W\s_])\*{2}(.*?)\*{2}([\W\s_])/g,       replacement : '$1<b>$2</b>$3' },         // bolt
	{ pattern : /([\W\s_])\*{1}(.*?)\*{1}([\W\s_])/g,       replacement : '$1<i>$2</i>$3' },         // italic
	{ pattern : /([\W\s])_(.*?)_([\W\s])/g,                 replacement : '$1<u>$2</u>$3' },         // underline
    // new line
	{ pattern : /(.*?\r?\n)(\r?\n)/gm,                      replacement : "$1<br />\n" },
	// paragraph
	//'/<.*>((.|\n)*)<\/.*>/', '<p>${1}</p>',
	// links
	{ pattern : /(\s)\[(.*)\]\((.*)\)\s?/gm,                replacement : "$1<a href=\"$3\" target=\"_blank\">$2</a>" },
	// images
	{ pattern : /(\s)!\[(.*)\]\((.*)\)\s/gm,                replacement : "$1<img src=\"$3\" alt=\"$2\" />" },
	// videos
];

markdownHelper.transformMdToHtml = function (fullText) {

	markdownHelper.rulesRegex.forEach(element => fullText = fullText.replace(element.pattern, element.replacement));

	// treatment on TH part Table
	fullText = fullText.replace(/{{{{TH}}}}\|?(.*)\|?{{{{TH}}}}/gm,
								thelem => thelem
											.replace(/\|?{{{{TH}}}}\|?/g,"")
											.replace(/\|/g, "</th><th>"));

	// treatment on TD part Table
	fullText = fullText.replace(/{{{{TD}}}}((.|\n)*?)\n?{{{{TD}}}}/gm,
								tdelem => tdelem
											.replace(/\|?{{{{TD}}}}\|?/gm,"")
											.split('\n').filter(ligne => ligne != "")
											.map(tmpline => tmpline.replace(/^\s?\|?(.*?)\|?\s?$/gm, "$1"))
											.join("</td></tr>\n  <tr><td>")
											.replace(/\|/gm, "</td><td>"));

	return fullText;
};