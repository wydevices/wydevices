function Skinops(composedata){
  var path = "./scripts/php/skins.php"
  //alert (composedata);

	$.ajax({
			method: "get",url: path,data: composedata,
			beforeSend: function(){
				$("#loading").show("fast");
				$("#skinform").hide("fast");
				}, //show loading just when link is clicked
			complete: function(){
			//alert("complete");
				$("#loading").hide("fast");
				}, //stop showing loading when the process is complete
			success: function(html){ //so, if data is retrieved, store it in html
			//alert("success");
				$(".content").fadeIn("fast"); //animation
				$(".content").html(html); //show the html inside .content div
				$("#skincheck").slideUp();
			}
	}); //close $.ajax(
}

function Reboot(composedata){
  var path = "./scripts/php/reboot.php"
  //alert (composedata);

	$.ajax({
			method: "get",url: path,data: composedata,
			beforeSend: function(){
				$("#loading").show("fast");
				}, //show loading just when link is clicked
			complete: function(){
			//alert("complete");
				$("#loading").hide("fast");
				}, //stop showing loading when the process is complete
			success: function(html){ //so, if data is retrieved, store it in html
			//alert("success");
				$(".content").show("fast"); //animation
				$(".content").html(html); //show the html inside .content div
			}
	}); //close $.ajax(

}

function ExtrasHandler(composedata){
  var path = "./scripts/php/extras.php"
  //alert (composedata);

	$.ajax({
			method: "get",url: path,data: composedata,
			beforeSend: function(){
				$("#loading").show("fast");
				}, //show loading just when link is clicked
			complete: function(){
			//alert("complete");
				$("#loading").hide("fast");
				}, //stop showing loading when the process is complete
			success: function(html){ //so, if data is retrieved, store it in html
			//alert("success");
				$(".content").show("fast"); //animation
				$(".content").html(html); //show the html inside .content div
			}
	}); //close $.ajax(

}

function ReloadRecords(RecordsUri){
  //alert (RecordsUri);
  var xmlhttp;

  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  }
  xmlhttp.onreadystatechange=function() {
    if(xmlhttp.readyState==4) {
      document.getElementById('recordsdiv').innerHTML=xmlhttp.responseText;
    }
  }
  //alert ("./"+RecordsUri);
  xmlhttp.open("GET","./"+RecordsUri,true);
  xmlhttp.send(null);
}

function Backops(backop){
  var pathtotv = "./scripts/php/channelform.php"
  var composedata="backops="+backop+"&op=backops";
  //alert (composedata);

	$.ajax({
    method: "get",url: pathtotv,data: composedata,
    beforeSend: function(){
      $("#loading").show("fast");
      }, //show loading just when link is clicked
    complete: function(){
    //alert("complete");
      $("#loading").hide("fast");
      }, //stop showing loading when the process is complete
    success: function(html){ //so, if data is retrieved, store it in html
    //alert("success");
      $(".content").show("fast"); //animation
      $(".content").html(html); //show the html inside .content div
      //$(".backdivclass").hide();
    }
	}); //close $.ajax(
}

function ChannelRename(newname,channeltoupdate){
  var pathtotv = "./scripts/php/channelform.php"
  var composedata="channel="+document.channelform.channel.value+"&newname="+document.channelform.newname.value+"&op=rename";

	$.ajax({
    method: "get",url: pathtotv,data: composedata,
    beforeSend: function(){
      alert("Data:"+composedata);
      $("#loading").show("fast");
      }, //show loading just when link is clicked
    complete: function(){
        //alert("complete");
      $("#loading").hide("fast");
      }, //stop showing loading when the process is complete
      success: function(html){ //so, if data is retrieved, store it in html
      //alert("success");
      $(".content").show("fast"); //animation
      $(".content").html(html); //show the html inside .content div
      }
  }); //close $.ajax(
}

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
    $("#"+fromdiv).hide("fast");
    return false;
  } else {
    destinationid=channelid;
    orderchannel(sourceid,destinationid);
    document.channelform.stage.value = '0'
    document.getElementById(fromdiv).style.background ='#66FF33';
    document.getElementById(fromdiv).style.fontWeight ='bold';
    $("#"+fromdiv).hide("fast");
    return false;				
  }
}

function orderchannel(source,destination){
		var xmlhttp;

		if (window.XMLHttpRequest) {
			xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
		} else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
		}
		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4) {
			//alert("ready");
			document.getElementById('container').innerHTML=xmlhttp.responseText;
			}
		}

  var totalchannels = document.channelform.totalchannels.value;
  //alert (source+" Will move to "+destination+" Considering totalchannles as:"+totalchannels);
	composeuri="channel="+source+"&totalchannels="+totalchannels+"&neworder="+destination+"&op=ajaxreorder";
  //alert (composeuri);
  xmlhttp.open("GET","./scripts/php/channelform.php?"+composeuri,true);
  xmlhttp.send(null);
}

function ShowExtras() {
  var xmlhttp;

  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  }

  xmlhttp.onreadystatechange=function() {
    if(xmlhttp.readyState==4) {
      document.getElementById('showextras').innerHTML=xmlhttp.responseText;
    }
  }

  xmlhttp.open("GET","./scripts/php/extras.php",true);
  xmlhttp.send(null);
}

function ShowReboot() {
  var xmlhttp;

  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  }
  xmlhttp.onreadystatechange=function() {
    if(xmlhttp.readyState==4) {
      document.getElementById('showreboot').innerHTML=xmlhttp.responseText;
    }
  }

  xmlhttp.open("GET","./scripts/php/reboot.php",true);
  xmlhttp.send(null);
}

function ShowSkins() {
  var xmlhttp;

  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
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
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
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
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
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
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
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
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
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
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  }
  xmlhttp.onreadystatechange=function() {
    if(xmlhttp.readyState==4) {
      document.getElementById('showhome').innerHTML=xmlhttp.responseText;
    }
  }

  xmlhttp.open("GET","./scripts/php/home.php",true);
  xmlhttp.send(null);
}

function checkPass(){
  var pwd = document.chgpassword.pwd_1.value;
  var pwd_confirm = document.chgpassword.pwd_2.value;
  if (pwd != pwd_confirm){
    alert("Password are different !");
    document.chgpassword.pwd_1.value = "";
    document.chgpassword.pwd_2.value = "";
    return false;
  } else {
    return true;
  }
}

function confirmation(alert_message, recordsuri) {
	if (confirm(alert_message)) ReloadRecords(recordsuri);
}
