#!/wymedia/usr/bin/php-cgi
<?php
header("Cache-Control: no-cache");
sleep(4);

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
echo '<pre>';
echo system("skinops.sh -c");
echo '</pre>';
echo system("cat ./forms/skins.html");
break;
case "helpus":
echo '<script type="text/javascript">ShowHelpUs()</script>';
break;

case "status": default:
echo '<script type="text/javascript">ShowStatus()</script>';
echo '<div id="showstatus"></div>';
break;
}


?>