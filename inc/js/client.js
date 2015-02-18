var WPS = {};
WPS.Client = (function(){
	var init = function(){};
	var isFF = function(){
		return jQuery.browser.mozilla;
	};
	var isWebkit = function(){
		return jQuery.browser.webkit;
	};
	var isIE = function(){
		return jQuery.browser.msie;
	};
	var isIE6 = function(){
		return (jQuery.browser.msie && parseFloat(jQuery.browser.version) < 7);
	};
	var isIE7 = function(){
		return (jQuery.browser.msie && parseFloat(jQuery.browser.version) < 8);
	};
	var isIE8 = function(){
		return (jQuery.browser.msie && parseFloat(jQuery.browser.version) < 9);
	};
	var isIE9 = function(){
		return (jQuery.browser.msie && parseFloat(jQuery.browser.version) < 10);
	};
	var isTouch = function () {
		return VSD.Client.isIPhone() || VSD.Client.isIPad() || VSD.Client.isIPod() || VSD.Client.isAndroid(); 
	};
	var isIPod = function(){ 
		return navigator.userAgent.match(/iPod/i) !== null; 
	};	
	var isIPad = function(){ 
		return navigator.userAgent.match(/iPad/i) !== null; 
	};	
	var isIPhone = function(){ 
		return navigator.userAgent.match(/iPhone/i) !== null; 
	};
	var isIOS = function(){ 
		return VSD.Client.isIPhone() || VSD.Client.isIPad() || VSD.Client.isIPod(); 
	};
	var iOSVersion = function() {
		var match = navigator.userAgent.match(/OS (\d+)_/i);
		if (match && match[1]) { 
			return match[1]; 
		}
	};
	var isAndroid = function(){ 
		return navigator.userAgent.match(/Android/i) !== null; 
	};
	var androidVersion = function() {
		var match = navigator.userAgent.match(/Android (\d+)\./i);
		if (match && match[1]) { 
			return match[1]; 
		}
	};
	
	return {
		"init": init,
		"isFF": isFF,
		"isWebkit": isWebkit,
		"isIE": isIE,
		"isIE6": isIE6,
		"isIE7": isIE7,
		"isIE8": isIE8,
		"isIE9": isIE9,
		"isTouch": isTouch,
		"isIPod": isIPod,
		"isIPad": isIPad,
		"isIPhone": isIPhone,
		"isIOS": isIOS ,
		"iOSVersion": iOSVersion,
		"isAndroid": isAndroid,
		"androidVersion": androidVersion
	};
}());