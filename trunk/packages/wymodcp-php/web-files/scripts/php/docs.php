<div id="docs" class="docscontainer">
<h1>Help Documents</h1><br />
<?php
$dir = "/wymedia/usr/share/wymodcp/docs/"; 
if (is_dir($dir)) {
  if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
      if ($file != "." && $file != "..") echo "<a href=\"./docs/".$file."\">".$file."</a><br>\n";
    }
    closedir($dh);
  }
}
?>
</div>
