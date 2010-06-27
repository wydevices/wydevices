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
case "Skins":
echo '<script type="text/javascript">ShowSkins()</script>';
echo '<div id="showskins"></div>';
break;
case "Data":
echo '<script type="text/javascript">ShowData()</script>';
echo '<div id="showdata"></div>';
break;

case "status": default:
echo '<script type="text/javascript">ShowStatus()</script>';
echo '<div id="showstatus"></div>';
break;
}


?>