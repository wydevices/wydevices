<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<body>
<form id=channel action="channelform.php" method="put">

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


<input type=submit></input>

</table>
</body>
</html>



