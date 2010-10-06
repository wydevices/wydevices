<?php 
$id = $_REQUEST["channel"];
$newid = $_REQUEST["neworder"]; 
$newname = $_REQUEST["newname"];  
$totalchannels = $_REQUEST["totalchannels"] + 1;  


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
	


