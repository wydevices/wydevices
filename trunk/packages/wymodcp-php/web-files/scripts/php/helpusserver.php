<h2>Welcome to the cloud Enviroment</h2>
<?php $targetcmd= "strings /dev/mtd2 |grep WC |cut -d= -f2"; ?>

<form action="http://foro.wydev.es/wydevware/target.php" method="get" id="target">
<table>
<tr><td>
        <input type="text" id="target" name="target" value="<?php system($targetcmd) ?>"> Target
</td></tr><tr><td>
        <input type="text" id="username" name="username"> Wydev's forum username </input>
</td></tr><tr><td>
        <input type="submit">
</td></tr>
</table>

<!-- be ready for next wydevelopment integration -->
<iframe src="http://foro.wydev.es/wydevware/index.php" width="100%" height="500"></iframe>
<!-- be ready for next wydevelopment integration -->

</form>



