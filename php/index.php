<?php

include "markdownHelper.php";

// index.php?read=tests.md
$fileToRead = $_GET["read"];
$htlmPage = ($fileToRead != "") ? MarkdownAdapter::transformMdToHtml(file_get_contents($fileToRead)) : MarkdownAdapter::test_md() ;

print($htlmPage);

?>