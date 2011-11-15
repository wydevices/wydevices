<?php
header("Cache-Control: no-cache");

$p = $_GET['page'];
switch($p) {
  case "home": default:
    echo '<script type="text/javascript">ShowHome()</script>';
    echo '<div id="showhome"></div>';
    break;
}
?>
