<?php
echo "<h2> Skins Operations </h2>";
echo '<blockquote><pre>';
echo system("/wymedia/usr/bin/skinops.sh -c");
echo '</pre></blockquote>';
?>

<form id="skinops" method="get" action="./scripts/php/skinops.php">
<ul >
			
<h1> Action </h1>

<table>
<tr><td><input id="flashmod" name="skinops" type="radio" value="f" /></td><td>Flash Mod</td></tr>
<tr><td><input id="skinwyplay" name="skinops" type="radio" value="s" /></td><td>System Skin</td></tr>
<tr><td><input id="skinmod" name="skinops" type="radio" value="m" /></td><td>Mod Skin</td></tr>
<tr><td><input id="export" name="skinops" type="radio" value="c" /></td><td>Export Skin to /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
<tr><td><input id="import" name="skinops" type="radio" value="e" /></td><td>Import Skin from /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
<tr><td><input id="reboot" name="skinops" type="radio" value="r" /></td><td>Reboot skin</td></tr>
<tr><td><input id="redfiff" name="skinops" type="radio" value="d" /></td><td>Redfiff skin</td></tr>
<tr><td><input id="themeserialized" name="skinops"  type="radio" value="t" /></td><td>Theme Serialized</td></tr>
<tr><td><input id="nothemeserialized" name="skinops" type="radio" value="nt" /></td><td>No Theme Serialized</td></tr>
</table>


<h1> Usage </h1>

<pre>
[-f] -flashmod : Delete current modskin and re-create from wydevice current skin.
[-s] -skinsystem : Skin Fast Switch to default skin
[-m] -skinmod : Skin Fast Switch to mod skin
[-c] -checkconsistency : Basic Skin Consistency Status

   ..:: Skin operations ::..
[-e] -exportskin : compress skin to /wymedia/usr/share/skins/
[-i] -importskin : uncompress skin from /wymedia/usr/share/skins/modskin.tar.gz
[-r] -rebootskin : Restart splash applying changes to skin
[-d] -redfiff : dfiff again all modskin png's

  ..:: Theme Serialization Options ::..
[-t] -themeserialized: set use_serialized_theme to True
[-nt] -nothemeserialized: set use_serialized_theme to False

</pre>
</li>

<li class="buttons">
<input type="submit" name="submit" value="Submit" />
</li>
</ul>
</form>	 
