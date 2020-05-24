<?php

include "markdownHelper.php";

$md = new MarkdownAdapter();

// index.php?read=tests.md

$fileToRead = $_GET["read"];

$htlmPage = ($fileToRead != "") ? $md->readMdFile($fileToRead) : $md->test_md() ;

print($htlmPage);

?>