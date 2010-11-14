<h1> Usage and Results </h1>

<div class="skinscontainer" id="skincheck">
<?php
$skinopsparam = $_GET['skinops'];
$command= "/wymedia/usr/bin/skinops.sh -".$skinopsparam;
echo "<pre>";
system($command);
system("/wymedia/usr/bin/skinops.sh -c");
system("pwd");
echo "</pre>";
?>
</div>
<input type="button" onClick="$('#skincheck').slideDown();" value="Show" class="wydevbutton"/></input>
<input type="button" onClick="$('#skincheck').slideUp();" value="Hide" class="wydevbutton"/></input>


<div id="skinform">
<form id="skinops" action="javascript:alert(document.getElementByName('skinops').value)";>
<ul >
			
<h1> Action </h1>

<table>
<tr><td><input id="flashmod" name="skinops" type="submit" class="wydevhiddenbox" value="f"  <?php echo"onclick=\"Skinops('skinops=f')\"";?>/></td><td>Flash Mod</td></tr>
<tr><td><input id="skinwyplay" name="skinops" type="submit" class="wydevhiddenbox" value="s" <?php echo"onclick=\"Skinops('skinops=s')\"";?>/></td><td>System Skin</td></tr>
<tr><td><input id="skinmod" name="skinops" type="submit" class="wydevhiddenbox" value="m" <?php echo"onclick=\"Skinops('skinops=m')\"";?>/></input></td><td>Mod Skin</td></tr>
<tr><td><input id="export" name="skinops" type="submit" class="wydevhiddenbox" value="c" <?php echo"onclick=\"Skinops('skinops=c')\"";?>/></td><td>Export Skin to /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
<tr><td><input id="import" name="skinops" type="submit" class="wydevhiddenbox" value="e" <?php echo"onclick=\"Skinops('skinops=e')\"";?>/></td><td>Import Skin from /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
<tr><td><input id="exportimgpck" name="skinops" type="submit" class="wydevhiddenbox" value="ei" <?php echo"onclick=\"Skinops('skinops=ei')\"";?>/></td><td>export imagepack to /wymedia/usr/share/imagepacks/</td></tr>
<tr><td><input id="importimgpck" name="skinops" type="submit" class="wydevhiddenbox" value="ii" <?php echo"onclick=\"Skinops('skinops=ii')\"";?>/></td><td>import imagepack from /wymedia/usr/share/imagepacks/</td></tr>
<tr><td><input id="reboot" name="skinops" type="submit" class="wydevhiddenbox" value="r" <?php echo"onclick=\"Skinops('skinops=r')\"";?>/></td><td>Reboot skin</td></tr>
<tr><td><input id="redfiff" name="skinops" type="submit" class="wydevhiddenbox" value="d" <?php echo"onclick=\"Skinops('skinops=d')\"";?>/></td><td>Redfiff skin</td></tr>
<tr><td><input id="themeserialized" name="skinops" type="submit" class="wydevhiddenbox" value="t" <?php echo"onclick=\"Skinops('skinops=t')\"";?>/></td><td>Theme Serialized</td></tr>
<tr><td><input id="nothemeserialized" name="skinops" type="submit" class="wydevhiddenbox" value="nt" <?php echo"onclick=\"Skinops('skinops=nt')\"";?>/></td><td>No Theme Serialized</td></tr>
</table>
</form>	 
</div>