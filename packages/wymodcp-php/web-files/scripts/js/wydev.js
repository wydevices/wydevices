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

function PressButton(composedata) {
	jQueryHandler("wyremote","./scripts/php/wyremote.php","clickedbutton="+composedata);
}

function Skinops(composedata){
  var path = "./scripts/php/skins.php";
  jQueryHandler("skin", path, composedata);
}

function Reboot(composedata){
  var path = "./scripts/php/reboot.php";
  jQueryHandler("reboot", path, composedata);
}

function ExtrasHandler(composedata){
  var path = "./scripts/php/config.php";
  jQueryHandler("extras", path, composedata);
}

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
function ShowHome()   {ShowPage("showhome",      "scripts/php/home.php");}

function orderchannel(source,destination) {
  var totalchannels = document.channelform.totalchannels.value;
  //alert (source+" Will move to "+destination+" Considering totalchannles as:"+totalchannels);
  composeuri = "./scripts/php/channelform.php?channel="+source+"&totalchannels="+totalchannels+"&neworder="+destination+"&op=ajaxreorder";
  ShowPage("container", composeuri);
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
	if (confirm(alert_message)) ShowPage("recordsdiv", recordsuri);
}

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

function updatefromlocal() {
	alert ("It will install the latest version of wybox-extras.");
	window.open("./scripts/php/updatewe.php");
}

