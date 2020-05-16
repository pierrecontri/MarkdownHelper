<?php

// index.php?read=tests.md

$fileToRead = $_GET["read"];
$contentFile = file_get_contents($fileToRead);

//print("<div id=\"mdText\">\n\n" . $contentFile . "\n\n</div>");

?>
<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="./markdownHelper.js"></script>
	<script type="text/javascript">
<?php
print("var contentText=`");
print($contentFile);
print("`;");
?>
	</script>
  </head>
  <body onload="javascript:transformMdToHtml('mdText', contentText);">
    <div id ="mdText"></div>
  </body>
</html>