<form id="extrasform" method="get" action="./scripts/php/extrashandler.php">
	<div class="form_description"><p>Update extras configuration</p></div>
		<table>
	<tr><th>Actual Status</th><th>AutoStart</th><th>Enable</th><th>Disable</th><th>Start</th><th>Stop</th></tr>
<?php


$initdfolder = '/wymedia/usr/etc/init.d/';

if ($handle = opendir($initdfolder)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
			echo "<tr><td><strong>";
			$readstatus = system($initdfolder.$file." status");
			$extraname = split(" ",$readstatus);
			echo "</strong></td>";
						
			if ($extraname[1] == "not"){
				$status = false;
				}
			else
				{
				$status = true;
				}
			
			
			?>
			
			<td><?php if ($status == true) {echo "<img src=./style/play.png>";} else {echo "<img src=./style/cross.png>";} ?></td><td><input id="<?php echo $extraname[0];?>" name="autostart<?php echo $extraname[0];?>" type="radio" value="true" />Enable</td>
			<td><input id="<?php echo $extraname[0];?>" name="autostart<?php echo $extraname[0];?>" type="radio" value="false" />Disable</td>
			<td><input id="<?php echo $extraname[0];?>" name="activate<?php echo $extraname[0];?>" type="radio" value="true" />Start</td>
			<td><input id="<?php echo $extraname[0];?>" name="activate<?php echo $extraname[0];?>" type="radio" value="false" />Stop</td>	
			
			<?php
			
			}
    }
    closedir($handle);
}
	?>

					</table>
				<input type="submit">
				</form>	

        <h1>Extras Usage</h1>
        <blockquote>
          <p>
            You can select up to 4 actions to apply to extras services
          </p>
        </blockquote>
        <h2>Actions</h2>
        <ul>
          <li><strong>Enable:</strong> Extra will be automatically started after reboot</li>
          <li><strong>Start:</strong> Extra will start now</li>
          <li><strong>Stop:</strong> Extra will stop now</li>
          <li><strong>Disable:</strong> Extra will not be automatically started after reboot</li>
        </ul>
        <br />
		
