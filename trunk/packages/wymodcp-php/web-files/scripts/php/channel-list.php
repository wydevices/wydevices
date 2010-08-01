<table>
<?php
	$dbh = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$sql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	foreach ($dbh->query($sql) as $row) {

		$id = $row['LOGICAL_CHANNEL_NUMBER'];
		$column = $id%"3";
		$channel = $row['NAME'];

		switch ($column) {
		    case 0: echo "<td>".$channel."</td></tr>";
				break;
		    case 1: echo "<tr><td>".$channel."</td>";
				break;
		    case 2: echo "<td>".$channel."</td>";
				break;
		    default: echo "default";
				break;
		}

	}
?>


</table>

