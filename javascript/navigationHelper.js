/* ***************** */
/* Navigation Helper */
/* Pierre     Contri */
/* ***************** */

'use strict';

var navigationHelper = {};

navigationHelper.loadTextDocument = function(theURL, callbackTreatment)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			callbackTreatment(xmlhttp.responseText);
		}
	};

	xmlhttp.open("GET", theURL, true);
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