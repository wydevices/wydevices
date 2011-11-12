<div id="skinform">
  <form id="skinops" action="javascript:alert(document.getElementByName('skinops').value);">
  <h1> Action </h1>
  <br />
  <table>
    <tr><td><input id="flashmod" name="skinops" type="submit" class="wydevradio" value="f"  <?php echo"onclick=\"Skinops('skinops=f')\"";?>/></td><td>Flash mod</td></tr>
    <tr><td><input id="skinwyplay" name="skinops" type="submit" class="wydevradio" value="s" <?php echo"onclick=\"Skinops('skinops=s')\"";?>/></td><td>System skin</td></tr>
    <tr><td><input id="skinmod" name="skinops" type="submit" class="wydevradio" value="m" <?php echo"onclick=\"Skinops('skinops=m')\"";?>/></input></td><td>Mod skin</td></tr>
    <tr><td><input id="export" name="skinops" type="submit" class="wydevradio" value="c" <?php echo"onclick=\"Skinops('skinops=c')\"";?>/></td><td>Export skin to /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
    <tr><td><input id="import" name="skinops" type="submit" class="wydevradio" value="e" <?php echo"onclick=\"Skinops('skinops=e')\"";?>/></td><td>Import skin from /wymedia/usr/share/skins/modskin.tar.gz</td></tr>
    <tr><td><input id="exportimgpck" name="skinops" type="submit" class="wydevradio" value="ei" <?php echo"onclick=\"Skinops('skinops=ei')\"";?>/></td><td>Export imagepack to /wymedia/usr/share/imagepacks/</td></tr>
    <tr><td><input id="importimgpck" name="skinops" type="submit" class="wydevradio" value="ii" <?php echo"onclick=\"Skinops('skinops=ii')\"";?>/></td><td>Import imagepack from /wymedia/usr/share/imagepacks/</td></tr>
    <tr><td><input id="reboot" name="skinops" type="submit" class="wydevradio" value="r" <?php echo"onclick=\"Skinops('skinops=r')\"";?>/></td><td>Reboot skin</td></tr>
    <tr><td><input id="redfiff" name="skinops" type="submit" class="wydevradio" value="d" <?php echo"onclick=\"Skinops('skinops=d')\"";?>/></td><td>Redfiff skin</td></tr>
    <tr><td><input id="themeserialized" name="skinops" type="submit" class="wydevradio" value="t" <?php echo"onclick=\"Skinops('skinops=t')\"";?>/></td><td>Theme serialized</td></tr>
    <tr><td><input id="nothemeserialized" name="skinops" type="submit" class="wydevradio" value="nt" <?php echo"onclick=\"Skinops('skinops=nt')\"";?>/></td><td>No theme serialized</td></tr>
  </table>
  <br />
  <h1> Response Window </h1>
  <br />
  <div class="skinscontainer" id="skincheck"><?php
    $skinopsparam = $_GET['skinops'];
    echo "<pre>";
    system("/wymedia/usr/bin/skinops.sh -".$skinopsparam);
    echo "</pre><hr /><pre>";
    system("/wymedia/usr/bin/skinops.sh -c");
    echo "</pre>"; ?>
  </div>

  <input type="button" onClick="$('#skincheck').slideDown();" value="Show" class="wydevbutton" />
  <input type="button" onClick="$('#skincheck').slideUp();" value="Hide" class="wydevbutton" />

  </form>
</div>
