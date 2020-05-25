#! /usr/bin/env python

import re

class MarkdownHelper(object):

	rulesRegex = [
		# tables
		(r'((?:([^\r\n|]*)\|)+(?:([^\r\n|]*)))\r?\n(?:( ?:?-+:? ?)\|)+(?:( ?:?-+:? ?))\r?\n(((?:([^\r\n|]*)\|)+(?:([^\r\n|]*))\r?\n)+)', "\n<table>\n  <tr><th>{{{{TH}}}}\g<1>{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\g<6>{{{{TD}}}}</td></tr>\n</table>\n<br/>\n", re.MULTILINE),
		(r'(\|(?:([^\r\n|]*)\|)+)\r?\n\|(?:( ?:?-+:? ?)\|)+\r?\n((\|(?:([^\r\n|]*)\|)+\r?\n)+)',                                         "\n<table>\n  <tr><th>{{{{TH}}}}\g<1>{{{{TH}}}}</th></tr>\n  <tr><td>{{{{TD}}}}\g<4>{{{{TD}}}}</td></tr>\n</table>\n<br/>\n", re.MULTILINE),
		# manage titles
		(r'^[#]{6}\s*(.+)$',            '<h6>\g<1></h6>\n', re.MULTILINE),
		(r'^[#]{5}\s*(.+)$',            '<h5>\g<1></h5>\n', re.MULTILINE),
		(r'^[#]{4}\s*(.+)$',            '<h4>\g<1></h4>\n', re.MULTILINE),
		(r'^[#]{3}\s*(.+)$',            '<h3>\g<1></h3>\n', re.MULTILINE),
		(r'^[#]{2}\s*(.+)$',            '<h2>\g<1></h2>\n', re.MULTILINE),
		(r'^[#]{1}\s*(.+)$',            '<h1>\g<1></h1>\n', re.MULTILINE),
		# specific titles
		(r'^(.*)\n[=]+.*$',          '<h1>\g<1></h1>\n',    re.MULTILINE),
		(r'^(.*)\n[-][-]+.*\n$',     '<h2>\g<1></h2>\n',    re.MULTILINE),
		(r'[\']{3}((.|\n)+)[\']{3}', '<code>\g<1></code>',  re.MULTILINE),  # code bloc
		# lines break
		(r'\n([- * _]{3,})\n',       '<hr />',              0),
		# manage caracters
		(r'\s\*{3}(.+)\*{3}\s',      '<b><i>\g<1></i></b>', 0),  # bolt && italic
		(r'\s\*{2}(.+)\*{2}\s',      '<b>\g<1></b>',        0),  # bolt
		(r'\s\*{1}(.+)\*{1}\s',      '<i>\g<1></i>',        0),  # italic
		(r'\s_(.+)_\s',              '<u>\g<1></u>',        0),  # underline
		# manage lists
		(r'^[ \t]*(\w|\d)\.[ \t]+(.+)$\n\n',        "<li class=\"endListNb\">\g<2></li></ol>\n\n",                                    re.MULTILINE),        # end of number
		(r'\n\n^[ \t]*([\w\d])\.[ \t]+(.+)$',       "\n\n<ol type=\"\g<1>\" start=\"\g<1>\"><li class=\"startListNb\">\g<2></li>\n",  re.MULTILINE),        # start of number
		(r'^[ \t]*[-\*][ \t]+(.+)\n$',              "<li class=\"endListLi\">\g<1></li></ul>\n\n",                                    re.MULTILINE),        # end of list
		(r'\n\n^[ \t]*[-\*][ \t]+(.+)$',            "\n\n<ul><li class=\"startListLi\">\g<1></li>\n",                                 re.MULTILINE),        # start of list
		(r'^[ \t]*(([\d\w]\.)|([-\*]))[ \t]+(.+)$', "<li class=\"contentList\">\g<4></li>\n",                                         re.MULTILINE),        # content of list

		#'/\n\n\n/', "<br />\n",  // new line
		# paragraph
		#'/<.*>((.|\n)*)<\/.*>/', '<p>${1}</p>',
		# links
		(r'\s\[(.*)\]\((.*)\)\s',  "<a href=\"\g<2>\" target=\"_blank\">\g<1></a>", re.MULTILINE),
		# images
		(r'\s!\[(.*)\]\((.*)\)\s', "<img src=\"\g<2>\" alt=\"\g<1>\" />",           re.MULTILINE),
		# videos
	]

	@staticmethod
	def transformMdToHtml(fullText):
	
		for rule in MarkdownHelper.rulesRegex:
			(patternReg, replacStr, flagsPat) = rule
			fullText = re.sub(patternReg, replacStr, fullText, flags=flagsPat)


		# treatment on TH part Table
		replaceTH = lambda matchobj: matchobj.group(1).replace('|',"</th><th>")
		fullText = re.sub(r'{{{{TH}}}}[|]?(.*)[|]?\{{{{TH}}}}', replaceTH, fullText, flags=re.MULTILINE)

		# treatment on TD part Table
		replaceTD = lambda matchobj: re.sub(r'\|?\r?\n\|?','</td></tr><tr><td>', matchobj.group(1), flags=re.MULTILINE).replace('|', "</td><td>")
		fullText = re.sub(r'{{{{TD}}}}\s*[|]?((.|\n)*?)\n?[|]?\s*{{{{TD}}}}', replaceTD, fullText, flags=re.MULTILINE)
	
		return fullText


#Do for tests
if __name__ == '__main__':

	test = """
		
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


"""

	# historic calling
	#with open('../tests.md', 'r') as myfile:
	#	test = myfile.read()
	from pathlib import Path
	txt = Path('../tests.md').read_text()

	transformedText = MarkdownHelper.transformMdToHtml(txt)
	print(transformedText)