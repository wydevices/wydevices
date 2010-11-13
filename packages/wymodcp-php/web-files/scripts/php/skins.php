<div class="skinscontainer" id="skincheck">
<?php
echo "<h2> Skins Operations </h2>";
echo '<blockquote><pre>';
echo system("/wymedia/usr/bin/skinops.sh -c");
echo '</pre></blockquote>';
?>
</div>
<input type="button" onClick="$('#skincheck').slideDown();" value="Show Consistency" />
<input type="button" onClick="$('#skincheck').slideUp();" value="Hide Consistency" />


<form id="skinops" method="get" action="./scripts/php/skinops.php">
<ul >
			
<h1> Action </h1>

<table>
<tr><td><input id="flashmod" name="skinops" type="radio" value="f"  <?php echo"onclick=\"Skinops('skinops=f')\"";?>/></td><td>Flash Mod</td></tr>
<tr><td><input id="skinwyplay" name="skinops" type="radio" value="s" <?php echo"onclick=\"Skinops('skinops=s')\"";?>/></td><td>System Skin</td></tr>
<tr><td><input id="skinmod" name="skinops" type="radio" value="m" <?php echo"onclick=\"alert('skinops=m')\"";?>/></input></td><td>Mod Skin</td></tr>
<tr><td><input id="export" name="skinops" type="radio" value="c" <?php echo"onclick=\"Skinops('skinops=c')\"";?>/></td><td>Export Skin to /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
<tr><td><input id="import" name="skinops" type="radio" value="e" <?php echo"onclick=\"Skinops('skinops=e')\"";?>/></td><td>Import Skin from /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
<tr><td><input id="exportimgpck" name="skinops" type="radio" value="ei" <?php echo"onclick=\"Skinops('skinops=ei')\"";?>/></td><td>export imagepack to /wymedia/usr/share/imagepacks/</td></tr>
<tr><td><input id="importimgpck" name="skinops" type="radio" value="ii" <?php echo"onclick=\"Skinops('skinops=ii')\"";?>/></td><td>import imagepack from /wymedia/usr/share/imagepacks/</td></tr>
<tr><td><input id="reboot" name="skinops" type="radio" value="r" <?php echo"onclick=\"Skinops('skinops=r')\"";?>/></td><td>Reboot skin</td></tr>
<tr><td><input id="redfiff" name="skinops" type="radio" value="d" <?php echo"onclick=\"Skinops('skinops=d')\"";?>/></td><td>Redfiff skin</td></tr>
<tr><td><input id="themeserialized" name="skinops"  type="radio" value="t" <?php echo"onclick=\"Skinops('skinops=t')\"";?>/></td><td>Theme Serialized</td></tr>
<tr><td><input id="nothemeserialized" name="skinops" type="radio" value="nt" <?php echo"onclick=\"Skinops('skinops=nt')\"";?>/></td><td>No Theme Serialized</td></tr>
</table>


<h1> Usage </h1>

<input type="button" onClick="$('.skinscontainer').slideDown();" value="Show Help" />
<input type="button" onClick="$('.skinscontainer').slideUp();" value="Hide Help" />


<div class="skinscontainer" id="skinhelp">
<?php
$skinopsparam = $_GET['skinops'];
$command= "/wymedia/usr/bin/skinops.sh -".$skinopsparam;
echo "<pre>";
system($command);
echo "</pre>";
?> 
</div>
</form>	 
