<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<body >
<h2>Channel List</h2>
<div id="channellist"><?php include 'channelform.php' ?></div>

<h2>Modify Channel List</h2>
<form id=channel name=channelform action="javascript:UpdateTV(this.form);" method="put">

<select id="channel" name="channel"> 

<?php
	$total=0;
	$dbh = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$sql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	foreach ($dbh->query($sql) as $row) {

		$id = $row['LOGICAL_CHANNEL_NUMBER'];
		$column = $id%"3";
		$channel = $row['NAME'];
		$total = $total+1;

		echo "<option value=\"".$id."\" >".$channel."</option> ";
	}

?>

</select>

<input type="hidden" id="totalchannels" name="totalchannels" class="text" value="<?php echo $total ?>">

<br>
<hr>
<label> New Order: </label>
<input id="neworder" name="neworder" class="text" type="text" maxlength="2" value=""/>
<br>
<label> New Name: </label>
<input id="newname" name="newname" class="text" type="text" maxlength="14" value=""/>  
<br>

<h2>Backup and restore operations</h2>
<input id="backops" name="backops" class="text" type="radio"  value="backup"/>  Backup
<input id="backops" name="backops" class="text" type="radio"  value="restore"/>  Restore from /wymedia/Backup/channels-net_backup.tar
<br>
<input type=submit></input>

</table>
</body>
</html>



