// ########### jQuery Functions #####################

function jQueryHandler(form, path, composedata){
  $.ajax({
    method: "get",url: path,data: composedata,
    beforeSend: function(){
      $("#loading").show("fast");
      if (form == "skin") $("#skinform").hide("fast");
      }, //show loading just when link is clicked
    complete: function(){
      //alert("complete");
      $("#loading").hide("fast");
      }, //stop showing loading when the process is complete
    success: function(html){ //so, if data is retrieved, store it in html
      //alert("success");
      if (form == "skin") {
        $(".content").fadeIn("fast");
      } else {
        $(".content").show("fast"); //animation
      }
      $(".content").html(html); //show the html inside .content div
      if (form == "skin") $("#skincheck").slideUp();
    }
  }); //close $.ajax(
}

function jQueryPostHandler(form, path, composedata){
  $.ajax({
    method: "post",url: path,data: composedata,
    beforeSend: function(){
	alert("bsend");
      $("#loading").show("fast");
      }, //show loading just when link is clicked
    complete: function(){
	alert("complete");
      $("#loading").hide("fast");
      }, //stop showing loading when the process is complete
    success: function(html){ //so, if data is retrieved, store it in html
	alert("success");
      $(".content").show("fast"); //animation
      $(".content").html(html); //show the html inside .content div
      }
  }); //close $.ajax(
}

// ########### WyRemote Functions #####################

function PressButton(composedata) {
	jQueryHandler("wyremote","./scripts/php/wyremote.php","clickedbutton="+composedata);
}

// ########### Skin Functions #####################

function Skinops(composedata){
  var path = "./scripts/php/skins.php";
  jQueryHandler("skin", path, composedata);
}

// ########### Extras Functions #####################

function Reboot(composedata){
  var path = "./scripts/php/reboot.php";
  jQueryHandler("reboot", path, composedata);
}

function ExtrasHandler(composedata){
  var path = "./scripts/php/config.php";
  jQueryHandler("extras", path, composedata);
}

// ########### Ices Functions #####################
//document.icesdata.address
//document.icesdata.mountpoint
//document.icesdata.port
//document.icesdata.mountpwd
//document.icesdata.name
//document.icesdata.description
//document.icesdata.playlist
//document.icesdata.startfolder
//document.icesdata.playlist

function IcesHandler(action){
  var path = "./scripts/php/wycron.php";
  var composedata="address="+document.icesdata.address.value+
"&mountpoint="+document.icesdata.mountpoint.value+
"&port="+document.icesdata.port.value+
"&mountpwd="+document.icesdata.mountpwd.value+
"&name="+document.icesdata.name.value+
"&description="+document.icesdata.description.value+
"&playlist="+document.icesdata.playlist.value+
"&startfolder="+document.icesdata.startfolder.value+
"&filter="+document.icesdata.filter.value+
"&action="+action+"&iceshandler";
  jQueryHandler("startices", path, composedata);
}




// ########### StreamRipper Streams Functions #####################

function AddStream(){
  var path = "./scripts/php/wycron.php";
  var composedata="name="+document.addstream.name.value+"&acronym="+document.addstream.acronym.value+"&streamurl="+document.addstream.streamurl.value+"&outfolder="+document.addstream.outfolder.value+"&addstream";
  jQueryHandler("addstream", path, composedata);
}

function DeleteStream(){
  var path = "./scripts/php/wycron.php";
  var arracronyms = document.deletestream.elements['deletestream[]'];
	  // BUG: Si hay solo un objeto, [object HTMLInputElement] si hay mas en la lista [object RadioNodeList]
	  // http://javascript.info/tutorial/type-detection#only-primitive-values

	  var toClass = {}.toString;
	  // alert ( toClass.call(arracronyms) );
	  if ( toClass.call(arracronyms) == "[object RadioNodeList]")
		{
		  var composedata="";  
		  var j=0;
		    for (var i = 0; i < arracronyms.length; i++) {       
				if (arracronyms[i].checked) {
				    composedata=composedata+"ID"+j+"="+arracronyms[i].value+"&";
				    j = j+1;
				}
			}

			composedata = composedata+"delstreamcount="+j;
			// Descomentar para analizar lo que se envía al php en el GET
			// alert(composedata);
	}
	else
	{
	//Si el objeto retornados es [object HTMLInputElement], podemos asignar 1 a la cuenta e identificar como 0 el ID
	composedata="ID0="+arracronyms.value+"&delstreamcount=1";
	}
	  jQueryHandler("deletestream", path, composedata);
}

// ########### StreamRipper Shows Functions #####################

function AddShow(){
  var path = "./scripts/php/wycron.php";
  var composedata="name="+document.addshow.showname.value+
"&streamsource="+document.addshow.streamsource.value+
"&hour="+document.addshow.hour.value+
"&minute="+document.addshow.minute.value+
"&monthday="+document.addshow.monthday.value+
"&month="+document.addshow.month.value+
"&weekday="+document.addshow.weekday.value+
"&duration="+document.addshow.duration.value+
"&singlefile="+document.addshow.singlefile.checked+
"&addshow";
  jQueryHandler("addstream", path, composedata);
}


function DeleteShow(){
  var path = "./scripts/php/wycron.php";
  var arracronyms = document.deleteshow.elements['deleteshow[]'];
  var composedata="";  
  var j=0;
    for (var i = 0; i < arracronyms.length; i++) {       
		if (arracronyms[i].checked) {
		    composedata=composedata+"ID"+j+"="+arracronyms[i].value+"&";
		    j = j+1;
		}
        }

	composedata = composedata+"delshowcount="+j;
	// alert(composedata);

  jQueryHandler("deleteshow", path, composedata);
}

// ########### Channel Functions #####################

function Backops(backop){
  var path = "./scripts/php/channelform.php";
  var composedata = "backops="+backop+"&op=backops";
  jQueryHandler("backops", path, composedata);
}

function ChannelRename(newname,channeltoupdate){
  var path = "./scripts/php/channelform.php";
  var composedata="channel="+document.channelform.channel.value+"&newname="+document.channelform.newname.value+"&op=rename";
  jQueryHandler("skin", path, composedata);
}

function logicfire(curdivid,channelid){
  //alert (curdivid+"-"+channelid);
  var fromdiv = "pos"+curdivid;
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

function orderchannel(source,destination) {
  var totalchannels = document.channelform.totalchannels.value;
  //alert (source+" Will move to "+destination+" Considering totalchannles as:"+totalchannels);
  composeuri = "./scripts/php/channelform.php?channel="+source+"&totalchannels="+totalchannels+"&neworder="+destination+"&op=ajaxreorder";
  ShowPage("container", composeuri);
}

function confirmation(alert_message, recordsuri) {
	if (confirm(alert_message)) ShowPage("recordsdiv", recordsuri);
}

// ########### Cron Functions #####################

function WyCron(composedata){
  var path = "./scripts/php/wycron.php";
//var senddata = 'crontab=\"'+composedata+'\"';
var senddata = 'crontab='+composedata;
senddata = senddata.replace(/(?:\r\n|\r|\n)/g, '@');
senddata = senddata.replace(/#/g,'%');
//senddata = 'crontab=test'

//alert(senddata);

  jQueryHandler("crontab", path, senddata);
}


// ########### ShowPage Functions #####################

function ShowPage(page_name, page_path) {
  var xmlhttp;

  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  } else xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5

  xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4){document.getElementById(page_name).innerHTML=xmlhttp.responseText;}}
  xmlhttp.open("GET","./"+page_path,true);
  xmlhttp.send(null);
}

function ShowWyRemote() {ShowPage("showwyremote","scripts/php/wyremote.php");}
function ShowReboot() {ShowPage("showreboot",    "scripts/php/reboot.php");}
function ShowSkins()  {ShowPage("showskins",     "scripts/php/skinforms.php");}
function ShowSyslog() {ShowPage("showsyslog",    "scripts/php/syslog.php");}
function ShowRecords(){ShowPage("showrecords",   "scripts/php/records.php");}
function ShowUpdate() {ShowPage("showupdate",    "scripts/php/update.php");}
function ShowTV()     {ShowPage("showtv",        "scripts/php/channel-list.php");}
function ShowWyCron()   {ShowPage("showwycron",      "scripts/php/wycron.php");}
function ShowHome()   {ShowPage("showhome",      "scripts/php/home.php");}





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




// ########### Update Functions #####################

function updatefromlocal() {
	alert ("It will install the latest version of wybox-extras.");
	window.open("./scripts/php/updatewe.php");
}

// ########### AjaxInit Functions #####################

function initAjaxForm(form_id, form_validations){
		var form = '#' + form_id;
		var form_message = form + '-message';

		/* enable/disable submit button */
		var disableSubmit = function(val){$(form + ' input[type=submit]').attr('disabled', val);};

		/* setup jQuery Plugin 'ajaxForm' */
		var options = {
				dataType:	'json',
				beforeSubmit: function(){
						/* run form validations if they exist */
						if(typeof form_validations == "function" && !form_validations()) {
								/* this will prevent the form from being submitted */
								return false;
						}
						disableSubmit(true);
						/* you can use these methods to access element style or css */
						$(form_message).attr('style','display:none');
						$('#package_filename').attr('style','display:none');
						$('#package_author').attr('style','display:none');
            $('#package_version').attr('style','display:none');
            $('#package_install_log').attr('style','display:none');
            $('#package_description').attr('style','display:none');
						$(".upload_wait").css("display", "block");
				},
				success: function(json){
						/*
						 * The response from AJAX request will look something like this:
						 *  status : success or error,
						 *  message : File uploaded successfully.
						 *  file : filename
						 *  type : image file type
             *
						 * Once the jQuery Form Plugin receives the response, it evaluates the string into a JavaScript object, allowing you to access
						 * object members as demonstrated below.
						 */
						$(".upload_wait").css("display", "none");
						disableSubmit(false);
						if(json.status == 'error') {
							$(form_message).attr('style','display:block');
							$(form_message).html('<span style="color:#F00;font-weight:bold;">' + json.message + '</span>');
						} else {
							$(form_message).attr('style','display:block');
							$('#package_filename').attr('style','display:block');
							$('#package_author').attr('style','display:block');
              $('#package_version').attr('style','display:block');
              $('#package_install_log').attr('style','display:block');
              $('#package_description').attr('style','display:block');
							$(form_message).html(json.message);
							$('#package_filename').html('<b>File Name: </b>' + json.package_filename);
							$('#package_author').html('<b>Author: </b>' + json.package_author);
              $('#package_version').html('<b>Version: </b>' + json.package_version);
              $('#package_install_log').html('<b>Install log: </b>' + json.package_install_log);
              $('#package_description').html('<b>Description: </b>' + json.package_description);
						}
						if(json.status == 'success') {
							$(form).clearForm(); /* clears the form but this does not clear the file input field */
							$("form")[ 0 ].reset(); /* or you can use this to clear all the fields */
						}
				}
		};
		$(form).ajaxForm(options);
}

