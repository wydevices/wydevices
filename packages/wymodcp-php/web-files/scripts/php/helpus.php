#!/wymedia/usr/bin/php-cgi
<?php
$targetcmd= "strings /dev/mtd2 |grep WC |cut -d= -f2";

echo "<h3> You will send to us this information for statistics. Thanks for submitting! </h3>";

?>

<form action="http://www.wydevelopment.eu/wydleware/target.php" method="get" id="target">
<table>
<tr><td>
	<input type="text" id="target" name="target" value="<?php system($targetcmd) ?>"> Target 
</td></tr><tr><td>
	<input type="text" id="username" name="username"> Wydevelopment username </input>
</td></tr><tr><td>
	<input type="submit">
</td></tr>
</table>
</form>


