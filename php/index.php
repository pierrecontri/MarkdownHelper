<?php

include "markdownHelper.php";

// index.php?read=tests.md
$fileToRead = $_GET["read"];
$htmlPage = ($fileToRead != "") ? MarkdownAdapter::transformMdToHtml(file_get_contents($fileToRead)) : MarkdownAdapter::test_md() ;

// print($htmlPage);

$fullPage = <<<EndText
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Transform Markdown to HTML helper</title>
    <meta charset=UTF-8 />
  </head>
  <body>
    <div id="mdText">${htmlPage}</div>
  </body>
</html>
EndText;

print($fullPage);

?>