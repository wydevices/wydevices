<script type="text/javascript" src="./scripts/js/jquery.min.js"></script>
<script type="text/javascript" src="./scripts/js/jquery.form.js"></script>
<script type="text/javascript" src="./scripts/js/wydev.js"></script>
<script type="text/javascript" src="./scripts/js/jquery.easing.js"></script>
<script type="text/javascript" src="./scripts/js/jqueryFileTree.js"></script>

<?php

// PHPCron v1.01
// written by Katy Coe - http://www.djkaty.com
// (c) Intelligent Streaming 2006

// WyCron.php v1.0
//
// updated the PHPCron v1.01 to fit with native cron for wydevices by Beats
// Not for commercial purpose
// Thanks goes by to Katy Coe for her code.
//
// Freedom to the Wy!

// File containing cronjobs
$cronFile = '/wymedia/usr/etc/cron.d/root';

// No timeout expiration
set_time_limit(0);

cron_edit($cronFile);

exit;

// Crontab editor
function cron_edit($cronFile) {

	header("Content-type: text/html");

?>

<html>

<head>

<!-- Para el jQueryFileTree -->
<script type="text/javascript">
	$('#jqftwyradio2').fileTree({ root: '../../WYRADIO/', script: './scripts/php/jqueryFileTree.php' }, function(file) { window.open(file); });
</script>
<!-- Para el jQueryFileTree -->

<title>WyRadio Editor</title>

</head>

<body style="font-family: Verdana, sans-serif; font-size: x-small">

<h1>WyCron / WyRadio</h1>

<hr>

<?php

	if (!file_exists ("/wymedia/Music")) {
		echo "Creating /wymedia/Music symlink.<br><br>";
		system("ln -s '/wymedia/My Music' /wymedia/Music");
	}
	if (!file_exists ("/wymedia/.wyradio/wyradio.db3")) {
		echo "Creating DB: /wymedia/.wyradio/wyradio.db3.<br><br>";
		system ("mkdir /wymedia/.wyradio/");
		system ("cat /wymedia/usr/share/wymodcp/scripts/sql/CreateEmptyWyradioDB.sql | sqlite3 /wymedia/.wyradio/wyradio.db3");
	}
	if (!file_exists ("/wymedia/usr/share/wymodcp/WYRADIO")) {
		echo "Creating root WYRADIO: /wymedia/usr/share/wymodcp/WYRADIO.<br><br>";
		system ("mkdir /wymedia/usr/share/wymodcp/WYRADIO");
	}

	$dbfile = new PDO('sqlite:/wymedia/.wyradio/wyradio.db3');

	if (isset($_GET['addstream'])) {
		//form submit code here
		$asname = $_GET['name'];
		$asacronym = $_GET['acronym'];
		$asstreamurl = $_GET['streamurl'];
		$asoutfolder = $_GET['outfolder'];
		$SQLAdd = "INSERT INTO streamsources (name, acronym, url, outfolder) VALUES ('".$asname."', '".$asacronym."', '".$asstreamurl."', '".$asoutfolder."');";
		echo "<br>";
		echo "SQL command: <i>".$SQLAdd."</i><br><br>";
		$dbfile->exec($SQLAdd);
		echo "Adding new stream.<br>";
		echo "Name: <i>".$asname."</i>";
		echo "<br>";
		echo "Acronym: <i>".$asacronym."</i>";
		echo "<br>";
		echo "Stream URL: <i>".$asstreamurl."</i>";
		echo "<br>";
		echo "Out Folder: <i>".$asoutfolder."</i>";
		echo "<br><br>";
	}

	if (isset($_GET['delstreamcount'])) {
		echo "<br>";
		for ($acrcount = 0; $acrcount < $_GET['delstreamcount']; $acrcount++) {
			$delthisid = "ID".$acrcount;
			$delthis = $_GET[$delthisid];
			$SQLDelete = "DELETE FROM streamsources WHERE acronym='".$delthis."';";
			echo "SQL command: <i>".$SQLDelete."</i><br><br>";
			$dbfile->exec($SQLDelete);
			echo "Deleting stream <i>".$delthis."</i>.<br>";
		}
		echo "<br>";
	}

	if (isset($_GET['addshow'])) {
		//form submit code here
		$asname = $_GET['name'];
		$asstreamsourceid = $_GET['streamsource'];
		$ashour = $_GET['hour'];
		$asminute = $_GET['minute'];
		$asweekday = $_GET['weekday'];
		$asmonth = $_GET['month'];
		$asmonthday = $_GET['monthday'];
		$asoutsinglefile = $_GET['singlefile'];
		if ($asoutsinglefile == "true"):
			$asoutsinglefile = 1;
		else:
			$asoutsinglefile = 0;
		endif;
		$asduration = $_GET['duration'];
		$asacronym = substr (str_replace(" ","",$asname),0,9);
		$insertsql = "INSERT INTO shows ([name], [acronym], [streamsourceid], [duration], [outsinglefile], [minute], [hour], [monthday], [weekday], [month]) VALUES ('".$asname."', '".$asacronym."', '".$asstreamsourceid."', ".$asduration.", '".$asoutsinglefile."', ".$asminute.", ".$ashour.", ".$asmonthday.", ".$asweekday.", ".$asmonth.");";
		echo "<br>";
		echo "SQL command: <i>".$insertsql."</i><br><br>";
		$dbfile->exec($insertsql);
		echo "Adding new show.<br>";
		echo "Name: <i>".$asname."</i>";
		echo "<br>";
		echo "Stream: <i>".$asstreamsourceid."</i>";
		echo "<br><br>";
	}

	if (isset($_GET['delshowcount'])) {
		for ($acrcount = 0; $acrcount < $_GET['delshowcount']; $acrcount++) {
			$delthisid = "ID".$acrcount;
			$delthis = $_GET[$delthisid];
			$SQLDelete = "DELETE FROM shows WHERE acronym='".$delthis."';";
			echo "SQL command: <i>".$SQLDelete."</i><br><br>";
			$dbfile->exec($SQLDelete);
			echo "Deleting show <i>".$delthis."</i>.<br>";
		}
		echo "<br>";
	}

?>

<h2>Explorer<input type="button" onClick="$('#jqftwyradio2').slideToggle();" class="wydevslidebutton"/></input></h2>
<div class="jqft">
	<div id="jqftwyradio2" class="jqftwyradio" style="display:none;"></div>
</div>

<h2>Streams<input type="button" onClick="$('#showstream').slideToggle();" class="wydevslidebutton"/></input></h2>
<div id="showstream" name="addstream" style="display:none;">

<?php

	$selectsql = 'SELECT * FROM streamsources';
	echo "<form name='deletestream' action='wycron.php' method='POST'>";
	echo "<table>";
	foreach ($dbfile->query($selectsql) as $returnrow) {
		$name = $returnrow['name'];
		$acronym = $returnrow['acronym'];
		$path = $returnrow['outfolder'];
		$streamurl = $returnrow['url'];
		if (file_exists ($path)):
			if (!file_exists ("/wymedia/usr/share/wymodcp/WYRADIO/".$acronym."/")):
				echo "Creating symlink for <i>".$path."</i>.<br>";
				system("ln -s ".$path." /wymedia/usr/share/wymodcp/WYRADIO/".$acronym);
			endif;
		else:
			echo "Creating folder <i>".$path."</i>.<br>";
			system("mkdir -p ".$path);
			if (!file_exists ("/wymedia/usr/share/wymodcp/WYRADIO/".$acronym)):
				echo "Creating symlink for <i>".$path."</i>.<br>";
				system("ln -s ".$path." /wymedia/usr/share/wymodcp/WYRADIO/".$acronym);
			endif;
		endif;
		echo "<tr><td>";
		echo "<b>".$name." :</b></td>";
		echo "<td> <a href='./WYRADIO/".$acronym."'>".$acronym."</a></td>";
		echo "<td><a href='".$streamurl."'>Stream URL</a>";
		echo "<td><input type=checkbox name=deletestream[] value=".$acronym.">";
		echo "</td></tr>";
	}
	echo "<tr><td><input id='deletebutton' name='deletebutton' class='button' type='button' onclick='DeleteStream();' value='Delete stream'/></td></tr>";
	echo "</table>";
	echo "</form>";

?>

</div>

<h2>Add new stream<input type="button" onClick="$('#addstream').slideToggle();" class="wydevslidebutton"/></input></h2>
<div id="addstream" name="addstream" style="display:none;">
<form Name="addstream" action="./scripts/php/wycron.php" method="post">
Name: <input type="text" name="name" value=""/>
Acronym: <input type="text" name="acronym" value=""/>
<br>
Stream URL: <input type="text" name="streamurl" value="" size="75"/> </br>
Out Folder: <input type="text" name="outfolder" value="/wymedia/Music/WYRADIO/"/>
<input id="addstream" name="addstream" class="button" type="button" onclick="AddStream();" value="Add stream"/>
</form>
</div>

<h2>Shows<input type="button" onClick="$('#shows').slideToggle();" class="wydevslidebutton"/></input></h2>
<div id="shows" name="shows" style="display:none;">

<?php
	
	$selectsql = 'SELECT * FROM shows';
	echo "<form name='deleteshow' action='wycron.php' method='POST'>";
	foreach ($dbfile->query($selectsql) as $returnrow) {
		$name = $returnrow['name'];
		$acronym = $returnrow['acronym'];
		$path = $returnrow['outfolder'];    
		$streamid = $returnrow['streamsourceid'];    
		$duration = $returnrow['duration'];    
		$outsinglefile = $returnrow['outsinglefile'];               
		$defaultpic = $returnrow['defaultpic'];
		if (isset($returnrow['minute'])):
			$minute = $returnrow['minute'];
		else:
			$minute = "*";
		endif;
		if (isset($returnrow['hour'])):
			$hour = $returnrow['hour'];
		else:
			$hour = "*";
		endif;
		if (isset($returnrow['monthday'])):
			$monthday = $returnrow['monthday'];
		else:
			$monthday = "*";
		endif;
		if (isset($returnrow['month'])):
			$month = $returnrow['month'];
		else:
			$month = "*";
		endif;
		if (isset($returnrow['weekday'])):
			$weekday = $returnrow['weekday'];
		else:
			$weekday = "*";
		endif;
		echo "<input type=checkbox name=deleteshow[] value=".$acronym.">".$minute." ".$hour." ".$monthday." ".$month." ".$weekday." RecordShowNG.sh ".str_replace(" ","_",$name)." ".$duration." ".$streamid." ".$outsinglefile." ".$defaultpic." </br>" ;
	}
	echo "<input id='deleteshowbutton' name='deleteshowbutton' class='button' type='button' onclick='DeleteShow();' value='Delete show'/>";
	echo "</form>";

?>

</div>

<h2>Add new show <input type="button" onClick="$('#addshow').slideToggle();" class="wydevslidebutton"/></input></h2>
<div id="addshow" name="addshow" style="display:none;">
<form Name="addshow" action="./scripts/php/wycron.php" method="post">
<table>
<tr><td>Name</td><td>Stream</td><td>Hour</td><td>Minute</td><td>Day of the month</td><td>Month</td><td>Weekday</td><td>Duration</td><td>Single file</td></tr>
<tr><td>
<input type="text" name="showname" value=""/>
</td><td>
<select name="streamsource">

<?php

	$selectsql = 'SELECT name,acronym FROM streamsources';
	foreach ($dbfile->query($selectsql) as $returnrow) {
		$name = $returnrow['name'];
		$acronym = $returnrow['acronym'];
		echo "<option value=".$acronym.">".$name."</option>";
	}

?>

</select>
</td><td>
<select name="hour">
<option value="null">All</option>

<?php

	for ($hour = 0; $hour <= 23; $hour++) { echo "<option value=".$hour.">".$hour."</option>"; }

?>

</select>
</td><td>
<select name="minute">
<option value="null">All</option>

<?php

	for ($min = 0; $min <= 59; $min++) { echo "<option value=".$min.">".$min."</option>"; }

?>

</select>
</td><td>
<select name="monthday">
<option value="null">All</option>

<?php

	for ($monthday = 1; $monthday <= 31; $monthday++) { echo "<option value=".$monthday.">".$monthday."</option>"; }

?>

</select>
</td><td>
<select name="month">
<option value="null">All</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</td><td>
<select name="weekday">
<option value="null">All</option>
<option value="1">Monday</option>
<option value="2">Tuesday</option>
<option value="3">Wednesday</option>
<option value="4">Thursday</option>
<option value="5">Friday</option>
<option value="6">Saturday</option>
<option value="0">Sunday</option>
</select>
</td><td>
<input type="text" name="duration" default="3600" size="6">
</td><td>
<input type="checkbox" name="singlefile" default="1">
</td>
<tr><td><input id="addshow" name="addshow" class="button" type="button" onclick="AddShow();" value="Add show"/></td></tr>
</table>
</form>
</div>

<hr>

<h2>IceS</h2>

<?php

	if (isset($_GET['iceshandler'])) {
		//form submit code here
		$name = $_GET['name'];
		$address = $_GET['address'];
		$port = $_GET['port'];
		$mountpoint = $_GET['mountpoint'];
		$mountpwd = $_GET['mountpwd'];
		$description = $_GET['description'];
		$playlist = $_GET['playlist'];
		$filter = $_GET['filter'];
		$startfolder = $_GET['startfolder'];
		$action = $_GET['action'];
		if ($action == "start") {
			$cmd = 'ices -h '.$address.' -m /'.$mountpoint.' -P \''.$mountpwd.'\' -F '.$playlist.' -t http -p '.$port.' -v -b 128 -n '.$name.' -g '.$description.' -i -B';
			echo "ices -h ".$address." -m /".$mountpoint." -P ****** -F ".$playlist." -t http -p ".$port." -v -b 128 -n ".$name." -g ".$description." -i -B";
			echo "<br><br>";
			exec($cmd);
		}
		if ($action == "next") {
			exec('pkill -USR1 ices');
		}
		if ($action == "kill") {
			exec('pkill -9 ices');
		}
		if ($action == "reload") {
			exec('pkill -HUP ices');
		}
		if ($action == "feed") {
			system("mkdir -p /wymedia/icesconf");
			$cmd = '/wymedia/usr/bin/find '.$startfolder.' -name '.$filter.' > '.$playlist ;
			echo $cmd;
			echo "<br><br>";
			system($cmd);
		}
	}

?>

<form name="icesdata" method="GET" action="./scripts/php/wycron.php">
<table>
<tr>
<td>Server Address: </td><td> <input type="text" name="address" value="<?php system('cat /wymedia/usr/etc/pydev-pi-ip'); ?>"/> </td>
<td>Mount Point: </td><td> <input type="text" name="mountpoint" value="stream"/> </td>
<td>Server Port: </td><td> <input type="text" name="port" value="8000"/> </td>
</tr>
<tr>
<td>Mount Password: </td><td> <input type="password" name="mountpwd" value=""/> </td>
<td>Station Name: </td><td> <input type="text" name="name" value="WyRadio"/> </td>
<td>Stream Description: </td><td> <input type="text" name="description" value="WyRadio now streaming"/> </td>
</tr>
<tr>
<td>Playlist File: </td><td> <input type="text" name="playlist" value="/wymedia/icesconf/playlist.txt" size=30 /> </td>
</tr>
<tr>
<td>Folder: </td><td> <input type="text" name="startfolder" value="/wymedia/Music/WYRADIO/" size=30 /> </td>
</tr>
<tr>
<td>Name Filter: </td><td> <input type="text" name="filter" value="*2015*.mp3" size=30 /> </td>
</tr>
</table>
<p> 
<input id="startices" name="startices" class="button" type="button" onclick="IcesHandler('start');" value="Start IceS"/>
<input id="nextices" name="nextices" class="button" type="button" onclick="IcesHandler('next');" value="Next"/>
<input id="killices" name="killices" class="button" type="button" onclick="IcesHandler('kill');" value="Kill"/>
<input id="reloadices" name="reloadices" class="button" type="button" onclick="IcesHandler('reload');" value="Reload playlist"/>
<input id="feedices" name="feedices" class="button" type="button" onclick="IcesHandler('feed');" value="Feed playlist"/>
</p>
</form>

<?php

	$icespid = exec("pidof ices");
	if($icespid > 0) {
		echo "<hr>";
		$lastsong = exec("tail /tmp/ices.log | grep Playing");
		echo $lastsong;
	}

?>

<hr>

<h1>Crontab</h1>

<pre>

###################################################################

<b>Current Date & Time: </b> <?php system('date');?>

###################################################################
--------------- minute (0 - 59) 
|  .------------- hour (0 - 23)
|  |  .---------- day of the month (1 - 31)
|  |  |  .------- month (1 - 12) or jan,feb,mar,apr...
|  |  |  |  .---- weekday (0 - 6) (Sunday=0 or 7) or sun,mon,tue,wed,thu,fri,sat 
|  |  |  |  |
*  *  *  *  *  command to be executed

</pre>

<?php

	if (isset($_GET['crontab'])):

?>

<p style="color: red">The crontab file will be updated.</p>

<?php

		// Negate escaping
		if (get_magic_quotes_gpc()) {
			if (ini_get('magic_quotes_sybase'))
				$data = strtr($_GETT['crontab'], array("''" => "'"));
			else
				$data = stripslashes($_GET['crontab']);
		}
		else
			$data = $_GET['crontab'];
		$data = str_replace("@","\r\n", $data);
		$data = str_replace("%","#", $data);
		echo "<pre>".$data."</pre>";
		$result = @file_put_contents($cronFile, $data);
		if ($result):

?>

<p style="color: red">The crontab file was updated successfully.</p>

<?php

		else:

?>

<p style="color: red">The crontab file could not be updated.</p>

<?php

		endif;

?>

<p style="color: red">Trying to restart crond:</p>

<?php

		system ("/wymedia/usr/etc/init.d/crond stop");
		system ("/wymedia/usr/etc/init.d/crond start");
		echo "<br><br>";
	else:
		//echo "nodata";
	endif;

?>

<form name="wycron" id="wycron" action="./scripts/php/wycron.php" method="post">
<div>
<textarea name="crontab" cols="120" rows="30">

<?php

	if (file_exists($cronFile))
		echo file_get_contents($cronFile);

?>

</textarea>
<input id="updatecron" name="updatecron" class="button" type="button" onclick="WyCron(document.wycron.crontab.value)" value="Update crontab"/>
</div>
</form>
            
</body>

</html>

<?php

}

?>
