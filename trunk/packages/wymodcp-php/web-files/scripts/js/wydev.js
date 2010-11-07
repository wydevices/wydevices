function logicfire(curdivid,channelid){

//alert (curdivid+"-"+channelid);
var fromdiv = "pos"+curdivid
//alert (fromdiv);


var stage = document.channelform.stage.value;

if (stage == '0') {
				sourceid=channelid;
				document.channelform.stage.value = '1';
				document.getElementById(fromdiv).style.background ='#66FF33';
				document.getElementById(fromdiv).style.fontWeight ='bold';
				return false;
				}
else
				{
				destinationid=channelid;
				orderchannel(sourceid,destinationid);
				document.channelform.stage.value = '0'
				document.getElementById(fromdiv).style.background ='#66FF33';
				document.getElementById(fromdiv).style.fontWeight ='bold';
				return false;				
				}
}


function orderchannel(source,destination){
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
				document.getElementById('channellist').innerHTML=xmlhttp.responseText;
			}
		}
var totalchannels = document.channelform.totalchannels.value;

//alert (source+" Will move to "+destination+" Considering totalchannles as:"+totalchannels);

		composeuri="channel="+source+"&totalchannels="+totalchannels+"&neworder="+destination;
//		alert (composeuri);
	
		xmlhttp.open("GET","./scripts/php/channelform.php?"+composeuri,true);
		xmlhttp.send(null);
}



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

function ShowSkins() {
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
				document.getElementById('showskins').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/php/skinforms.php",true);
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

		xmlhttp.open("GET","./scripts/php/syslog.php",true);
		xmlhttp.send(null);
	}
	
	function ShowRecords() {
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
				document.getElementById('showrecords').innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","./scripts/php/records.php",true);
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
		function UpdateTV(form) {
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
				document.getElementById('channellist').innerHTML=xmlhttp.responseText;
			}
		}

		//alert('inicio');
		
		var f= document.channelform.backops;
		for (var i=0;i<=f.length - 1;i++){
		//alert('for');
		if (f[i].checked){
			//alert('dentroif');
			backops = f[i].value;
			composeuri="channel="+document.channelform.channel.value+"&totalchannels="+document.channelform.totalchannels.value+"&neworder="+document.channelform.neworder.value+"&newname="+document.channelform.newname.value+"&backops="+backops;
			//alert (composeuri);
					

			window.open("./scripts/php/channelform.php?"+composeuri,true);


			}
			else
			{
			//alert('noaction');
			}
		}
		
				//alert('composeuri fuera if');
		
		composeuri="channel="+document.channelform.channel.value+"&totalchannels="+document.channelform.totalchannels.value+"&neworder="+document.channelform.neworder.value+"&newname="+document.channelform.newname.value;
		//alert (composeuri);
	
		
		//xmlhttp.open("GET","channelform.php?"+composeuri,true);
		xmlhttp.open("GET","./scripts/php/channelform.php?"+composeuri,true);
	
		
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
	
	function updatefromlocal() {
	alert ("System will uncompress /wymedia/usr/share/updates/ to /wymedia");
	window.open("./scripts/php/update.php");
	}
	function updatefromstatic() {
	alert ("System will uncompress /wymedia/usr/share/updates/static/ to /wymedia");
	window.open("./scripts/php/updatestatic.php");
	}

	
	
	
function confirmation(alert_message, href_location) {
	if (confirm(alert_message)) window.location = href_location;
}
