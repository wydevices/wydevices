	function ShowExtras() {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4) {
				document.getElementById('showextras').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/php/extras.php",true);
		xmlhttp.send(null);
	}

function ShowReboot()
{
var xmlhttp;
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  // code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {
  document.getElementById('showreboot').innerHTML=xmlhttp.responseText;
  }
}
xmlhttp.open("GET","./scripts/php/reboot.php",true);
xmlhttp.send(null);
}



	function ShowSyslog() {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4) {
				document.getElementById('showsyslog').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/wydev/syslog.shtml",true);
		xmlhttp.send(null);
	}

	function ShowHelpUs() {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4) {
				document.getElementById('showhelpus').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/php/helpus.php",true);
		xmlhttp.send(null);
	}
	function ShowTV() {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4) {
				document.getElementById('showtv').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/php/channel-list.php",true);
		xmlhttp.send(null);
	}
	function ShowHome() {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4) {
				document.getElementById('showhome').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/php/home.php",true);
		xmlhttp.send(null);
	}




