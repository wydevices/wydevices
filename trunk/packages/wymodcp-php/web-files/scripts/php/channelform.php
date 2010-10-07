
<h2>Channel List Updated!</h2>
<?php 
$id = $_REQUEST["channel"];
$newid = $_REQUEST["neworder"]; 
$newname = $_REQUEST["newname"];  
$totalchannels = $_REQUEST["totalchannels"] + 1;  
$backops = $_REQUEST["backops"];  

echo "<table><tr>";
switch ($backops) {
			case "restore": echo "<td>Will Restore</td></tr><tr><td>";
				system ("Restore_channels-net /wymedia/Backup/channels-net_backup.tar");
				echo "</td>";
				break;
			case "backup": echo "<td>Backing up</td>";
				system ("Backup_channels-net");
				echo "</td>";
			break;
			default: echo "<td>No Backup operation detected.</td>";
				break;
		 }
echo "</tr></table>";

	$objDB = new PDO('sqlite:/etc/params/wyscan/wyscan.db');

	$sql = "update T_SERVICE set LOGICAL_CHANNEL_NUMBER=".$totalchannels." where LOGICAL_CHANNEL_NUMBER=".$newid;
	$res = $objDB->query($sql);

	$sql = "update T_SERVICE set LOGICAL_CHANNEL_NUMBER=".$newid." where LOGICAL_CHANNEL_NUMBER=".$id;
	$res = $objDB->query($sql);

	$sql = "update T_SERVICE set LOGICAL_CHANNEL_NUMBER=".$id." where LOGICAL_CHANNEL_NUMBER=".$totalchannels;
	$res = $objDB->query($sql);


	if ($newname){

	$sql = "update T_SERVICE set NAME=\"".$newname."\" where LOGICAL_CHANNEL_NUMBER=".$newid;
	echo $sql;
	$res = $objDB->query($sql);
	}

	echo "<hr>";


	$total=0;
	$dbh = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$sql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	foreach ($dbh->query($sql) as $row) {

		$id = $row['LOGICAL_CHANNEL_NUMBER'];
		$column = $id%"3";
		$channel = $row['NAME'];
		$total = $total+1;

		echo $id." - ".$channel."<br>" ;
	}




?>
	


