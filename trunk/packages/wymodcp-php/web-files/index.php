<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WyMod Control Panel v3.1</title>

<!-- JS Libs includes -->
<script type="text/javascript" src="./scripts/js/jquery.min.js"></script>
<script type="text/javascript" src="./scripts/js/jquery.form.js"></script>
<script type="text/javascript" src="./scripts/js/wydev.js"></script>

<!-- JQuery effects -->
<script type="text/javascript">
// When the document loads do everything inside here ...
$(document).ready(	function(){
  $('.content').load('wymod-php.php'); //by default initally load text from wymod-php.php
  // Event Click on Menu Handler (Here will accept jquery ajax call to php backend files)
  $('#menu a').click(	function(){ //start function when any link is clicked
      $(".content").slideUp(1000);
      var content_show = $(this).attr("title"); //retrieve title of link so we can compare with php file
      // Based on the title of the a link on the menu id, we can select where to go
      switch(content_show){
        case "home":
          var path = "./scripts/php/home.php" //set the path here to next ajax method call
          break;
        case "syslog":
          var path = "./scripts/php/syslog.php"
          break;
        case "update":
          var path = "./scripts/php/update.php"
          break;
        case "reboot":
          var path = "./scripts/php/reboot.php"
          break;
        case "skins":
          var path = "./scripts/php/skins.php"
          break;
        case "TV":
          var path = "./scripts/php/channelform.php"
          break;
        case "records":
          var path = "./scripts/php/records.php"
          break;
        case "docs":
          var path = "./scripts/php/docs.php"
          break;
        case "wyremote":
          var path = "./scripts/php/wyremote.php"
          break;
        case "config":
          var path = "./scripts/php/config.php"
          break;
        default:
          var path = "wymod-php.php"
          break;
      }

      $.ajax({
      method: "get",url: path,data: "",
        beforeSend: function(){
          //alert("Current Stage: Before Send");
          $("#loading").show("slow");
        }, 
        complete: function(){
          //alert("Current Stage: Complete");
          $("#loading").hide("slow");
        }, 
        success: function(html){ 
          //alert("Current Stage: Success");
          $(".content").show("slow"); //animation
          $(".content").html(html); //show the html inside .content div
        }
      }); //close $.ajax(
  }); //close click(
}); //close $(
</script>
<link rel="stylesheet" type="text/css" href="./style/wymod.css" />
</head>

<body>
  <div id="upperbar">
    <div id="links"><a href="http://code.google.com/p/wydevices/"><img src="./style/wydev_logo.png"></a></div>
    <!--<div id="space"><img src="./style/wydev_logo.png"></div>-->
    <!--<div class="clear"><img src="./style/wydev_logo.png"></div>-->
  </div>

  <div class="shadow">
    <div id="header">
      <ul id="menu">
        <li id="home"><a href="#" title="home">Home</a></li>
        <li id="wyremote"><a href="#" title="wyremote">WyRemote</a></li>
        <li id="reboot"><a href="#" title="reboot">Reboot</a></li>
        <li id="syslog"><a href="#" title="syslog">Syslog</a></li>
        <li id="skins"><a href="#" title="skins">Skins</a></li>
        <li id="records"><a href="#" title="records">Records</a></li>
        <li id="TV"><a href="#" title="TV">TV</a></li>
        <li id="config"><a href="#" title="config">Config</a></li>
        <li id="update"><a href="#" title="update">Update</a></li>
        <li id="docs"><a href="#" title="docs">Docs</a></li>
      </ul>
    </div>

    <div id="entire">
      <div id="content">
        <div id="content-full">
          <br style="clear:both;" />
          <h1><div id="loading"><br />Loading<br /><br /><br /><br /></div></h1>
          <div class="content"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="bottombar2">
    <?php 
    if (substr_compare(exec("grep global_passwords_file /wymedia/usr/etc/mongoose.conf"),"\#", 0, 1) <= 1) { ?>
    Go to config menu now for set a password.
    <?php } ?>
  </div>

  <div class="bottombar">
    <a href="http://code.google.com/p/wydevices/">Project homepage</a>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
    <a href="http://wydev.orgfree.com/foro/">Forum</a>
  </div>
</body>
</html>
