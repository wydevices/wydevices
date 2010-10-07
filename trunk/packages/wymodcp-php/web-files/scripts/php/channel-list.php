<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<body>
<h2>Channel List</h2>

<?php
	$Ctotal=0;
	$dbfile = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$selectsql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	echo "<table border=1>";
	foreach ($dbfile->query($selectsql) as $returnrow) {

		$channelid = $returnrow['LOGICAL_CHANNEL_NUMBER'];
		$Displaycolumn = $channelid%"3";
		$channelname = $returnrow['NAME'];
		$Ctotal = $Ctotal+1;

		
		switch ($Displaycolumn) {
			case 0: echo "<td>".$channelid." - ".$channelname."</td></tr>";
				break;
			case 1: echo "<tr><td>".$channelid." - ".$channelname."</td>";
				break;
			case 2: echo "<td>".$channelid." - ".$channelname."</td>";
				break;
			default: echo "default";
				break;
		 }
		
	}
	echo "</table>";
?>

<h2>Modify Channel List</h2>
<form id=channel action="./scripts/php/channelform.php" method="put">

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

<input type="hidden" name="totalchannels" class="text" value="<?php echo $total ?>">

<br>
<hr>
<label> New Order: </label>
<input id="neworder" name="neworder" class="text" type="text" maxlength="2" value=""/>
<br>
<label> New Name: </label>
<input id="newname" name="newname" class="text" type="text" maxlength="14" value=""/>  
<br>
<h2>Backup and restore operations</h2>
<input id="backup" name="backops" class="text" type="radio"  value="backup"/>  Backup
<input id="backup" name="backops" class="text" type="radio"  value="restore"/>  Restore from /wymedia/Backup/channels-net_backup.tar
<br>

<input type=submit></input>
</form>




</table>
</body>
</html>



