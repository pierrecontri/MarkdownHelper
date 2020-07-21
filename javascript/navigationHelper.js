/* ***************** */
/* Navigation Helper */
/* Pierre     Contri */
/* ***************** */

'use strict';

var navigationHelper = {};

navigationHelper.loadTextDocument = function(theURL, acceptOrigin, callbackTreatment)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4) {
			if (xmlhttp.status==200 || xmlhttp.status==0)
				callbackTreatment(xmlhttp.responseText);
			else
				callbackTreatment("Error on getting document or no file");
		}
	};

	xmlhttp.open("GET", theURL, true);
	xmlhttp.setRequestHeader('Access-Control-Allow-Origin', acceptOrigin);
	xmlhttp.setRequestHeader('Accept', 'application/json, text/javascript, text/plain');
	xmlhttp.setRequestHeader('Content-Type','text/plain; charset=UTF-8');
	xmlhttp.send();
};

navigationHelper.getParametersFromUrl = function() {
	
	var params = {};
	window.location.search
		.replace("?","")
		.split("&")
		.forEach(
			function (tmpline) {
				var tmpKV = tmpline.split("=", 2);
				params[tmpKV[0]] = tmpKV[1]; 
			}
		);
	return params;
};

navigationHelper.printMdFile = function(...args) {

	// get arguments
    let [idContainer, mdUrl, acceptOrigin, other] = args;

    if (mdUrl == undefined || mdUrl == '')
		mdUrl = navigationHelper.getParametersFromUrl().read;

    if (acceptOrigin == undefined || acceptOrigin == '') acceptOrigin = "*";

	navigationHelper.loadTextDocument(
		mdUrl, acceptOrigin,
		function (contentText) {
			document.getElementById(idContainer).innerHTML = markdownHelper.transformMdToHtml(contentText);
		}
	);
};
