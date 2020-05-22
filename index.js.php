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

    function loadXMLDoc(theURL)
    {
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                alert(xmlhttp.responseText);
            }
        };
        xmlhttp.open("GET", theURL, false);
        xmlhttp.send();
    }

//var xmlhttp=false;
//loadXMLDoc('http://myhost/mycontent.htmlpart');
//if(xmlhttp==false){ /* set timeout or alert() */ }
//else { /* assign `xmlhttp.responseText` to some var */ }


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