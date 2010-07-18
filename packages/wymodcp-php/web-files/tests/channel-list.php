<?php
	$dbh = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$sql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	foreach ($dbh->query($sql) as $row) {
		echo $row['LOGICAL_CHANNEL_NUMBER']." - ".$row['NAME']."<br />";
	}
?>
