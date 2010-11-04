<?php 
$id = $_REQUEST["channel"];
$newid = $_REQUEST["neworder"]; 
$newname = $_REQUEST["newname"];  
$totalchannels = $_REQUEST["totalchannels"] + 1;  
$backops = $_REQUEST["backops"];  

echo "<pre>";
switch ($backops) {
			case "restore": 
				system ("/wymedia/usr/bin/Restore_channels-net /wymedia/Backup/channels-net_backup.tar");
				break;
			case "backup":
				system ("/wymedia/usr/bin/Backup_channels-net");
			break;
			default: echo "No Backup operation detected.";
				break;
		 }

echo "</pre>";

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

	$Ctotal=0;
	$dbfile = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$selectsql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	echo "<div style = 'display: table; margin-left: auto; margin-right: auto; width: 500px;'>";
	foreach ($dbfile->query($selectsql) as $returnrow) {

		$channelid = $returnrow['LOGICAL_CHANNEL_NUMBER'];

			
		$channelname = $returnrow['NAME'];
		$Ctotal = $Ctotal+1;

		echo  "<div id=".$Ctotal." style='width=100px;height=40px;background=#ccc; text-align=center;padding: 5px; border: thin solid black;'>";
		echo  "<a href='#' onclick=logicfire(".$Ctotal.",".$channelid."); style='line-height=40px'>";
		echo $channelid." - ".$channelname;
		echo "</a></div>";
		
		
		
	}
	echo "</div>";
?>

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
<input type="hidden" id="stage" name="stage" class="text" value="0">
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