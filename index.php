<?php

include "markdownHelper.php";

$md = new MarkdownAdapter();

// index.php?read=test_md

$fileToRead = $_GET["read"];

$htlmPage = ($fileToRead != "") ? $md->readMdFile($fileToRead) : $md->test_md() ;

print($htlmPage);

?>