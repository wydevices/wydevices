#!/wymedia/usr/bin/php-cgi
<?php
header("Cache-Control: no-cache");
sleep(2);

$p = $_GET['page'];

switch($p) {
case "extras":
echo '<script type="text/javascript">ShowExtras()</script>';
echo '<div id="showextras"></div>';
break;
case "reboot":
echo '<script type="text/javascript">ShowReboot()</script>';
echo '<div id="showreboot"></div>';
break;
case "syslog":
echo '<script type="text/javascript">ShowSyslog()</script>';
echo '<div id="showsyslog"></div>';
break;
case "skins":
echo '<script type="text/javascript">ShowSkins()</script>';
echo '<div id="showskins"></div>';
break;
case "records":
echo '<script type="text/javascript">ShowRecords()</script>';
echo '<div id="showrecords"></div>';
break;
case "TV":
echo '<script type="text/javascript">ShowTV()</script>';
echo '<div id="showtv"></div>';
break;
case "helpus":
echo '<script type="text/javascript">ShowHelpUs()</script>';
echo '<div id="showhelpus"></div>';
break;

case "home": default:
echo '<script type="text/javascript">ShowHome()</script>';
echo '<div id="showhome"></div>';
break;
}


?>
