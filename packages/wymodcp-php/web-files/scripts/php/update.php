<script src='./scripts/js/wydev.js' type='text/javascript'></script>

<?php
/*
 * Update process :
 * - Download and read the latest.txt file on wydev.orgfree.com page.
 * - Compare if superior with a local version.
 * - Download the update in tar.gz format.
 * - Download the checksum file in .md5 format.
 * - Check if the download of update is correct.
 * - Unpack tar.gz update.
 * - Launch install.sh script contain in the udpate archive.
 * - Check if the log of the install script don't contain ERROR.
 * 
 */
?>
<h1>Wybox automatic update</h1><br />

<table width="100%">
<tr><td></td>
<td align="left" colspan="2">
<i>Here you can find available update found on official Wydev repository.</i><br /><br />
</td>
</tr>


<tr>
<td>Installed version of wybox-extras:</td>
<td><b><?php $current = system("cat /wymedia/usr/etc/wydev-mod-updaterelease"); ?></b></td>
</tr>

<tr>
<td>Latest packaged version of wybox-extras:</td>
<?php system ("wget http://wydev.orgfree.com/updates/we-latest.txt > /dev/null 2>&1"); ?>
<td><b><?php $latest = system("cat we-latest.txt"); ?></td>
</tr>

<?php
system ("mv -f we-latest.txt /wymedia/tmp/wybox-extras-latest.txt");
if ($current < $latest) {
	if (file_exists("/wymedia/tmp/wybox-extras-".$latest.".tar.gz")) {
		echo "<tr><td><i>There is available a newer version of wybox-extras.</i></td><td></td></tr>";
		echo "<tr><td>Update wybox.extras (the system will reboot):</td><td><button onclick='updatefromlocal()'>Click here!</button></td></tr>";
	} else {
		system ("wget http://wydev.orgfree.com/updates/we-latest.tar.gz -q");
                system ("wget http://wydev.orgfree.com/updates/we-latest.md5 -q");
		echo "<tr><td>Downloading latest packaged wybox-extras:</td><td>";		
		$checkmd5 = system ("md5sum -c we-latest.md5");
		$checkmd52 = split(" ",$checkmd5);
		if ($checkmd52[1] == "OK") {
			system ("mv we-latest.tar.gz /wymedia/tmp/wybox-extras-".$latest.".tar.gz");
			system ("rm -f we-latest.md5");
			echo "<tr><td><i>There are available a newer version of wybox-extras.</i></td><td></td></tr>";
			echo "<tr><td>Update wybox.extras (the system will reboot):</td><td><button onclick='updatefromlocal()'>Click here!</button></td></tr>";
		} else {
			system ("rm -f we-latest*");
			echo "<td><b>Download failed.</b></td></tr>";
		}		
	}
} else {
	echo "<tr><td></td><td><b>wybox-extras is updated.</b></td></tr>";
}
?>


</table>

<br /><hr width="100%" />

<h1>Add or update your wybox manually</h1><br />

<div id="manual_upload_container">
<form name="manual_update" id="manual_update" method="post" action="./scripts/php/upload.php">
  <table width="100%">
    <tr><td colspan="3" align="left">
      <div style="display:none" id="manual_update-message"></div>
      <div style="display:none" id="package_filename"></div>
      <div style="display:none" id="package_author"></div>
      <div style="display:none" id="package_version"></div>
      <div style="display:none" id="package_install_log"></div>
      <div style="display:none" id="package_description"></div>
    </td></tr>
    <tr><td></td><td align="left"><h2>Put a valid package provided by a wydev member</h2></td></tr>
    <tr><td></td>
      <td align="left" colspan="2">
        <i>Your package will be unpacked, installed and showed on this page.<br />
        Package must be tar.gz format.</i><br /><br />
      </td>
    </tr>
    <tr>
      <td></td>
      <td align="left">Select the package to install/update</td>
      <td align="left"><input type="file" name="manual_package" size="50" /></td>
    </tr>
    <tr><td colspan="3"><br /></td></tr>
    <tr>
      <td></td>
      <td align="right" colspan="2">
        <input type="submit" name="manual_upload" class="button" value="Upload and install package" style="width: 250px" />
        <div id="upload_wait" class="upload_wait">
          <br>Uploading and installing package. Please wait...<br><br style="line-height:4px;">
          <img src="./style/progress.gif" border="0" height="16" width="16">
        </div>
      </td>
    </tr>
  </table>
</form>
</div>
<br /><hr width="100%" />
<script type="text/javascript">
  $(document).ready(function() {
    new initAjaxForm('manual_update');
  });
</script>
