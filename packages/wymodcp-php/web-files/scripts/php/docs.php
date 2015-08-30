
<div id="docs" class="docscontainer">
<h1>Help Documents</h1><br />

<?php
$dir = "/wymedia/usr/share/wymodcp/docs/"; 
if (is_dir($dir)) {
  if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
      if ($file != "." && $file != "..") {

	if (strstr($file, ".html")){

$fileid = substr($file, 0, 5);

echo "<h3> ".$file."<input type=\"button\" onClick=\"$('#".$fileid."').load( '/docs/".$file."'
); $('#".$fileid."').slideToggle() ;\" class=\"wydevslidebutton\"/></input></h3>";
	echo "<div id=\"". $fileid."\" name=\"".$fileid."\" style=\"display:none;\">";
	echo "</div>";

	}
	else
	{
		echo "<a href=\"./docs/".$file."\">".$file."</a><br>\n";
	}
	}
    }
    closedir($dh);
  }
}
?>
</div>
