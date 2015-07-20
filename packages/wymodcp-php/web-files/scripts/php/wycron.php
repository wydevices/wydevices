<script type="text/javascript" src="./scripts/js/jquery.min.js"></script>
<script type="text/javascript" src="./scripts/js/jquery.form.js"></script>
<script type="text/javascript" src="./scripts/js/wydev.js"></script>
<script type="text/javascript" src="./scripts/js/jquery.easing.js" ></script>
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
function cron_edit($cronFile)
{
    header("Content-type: text/html");
    ?>
    <html>
        <head>

<!-- Para el jQueryFileTree -->
<script type="text/javascript">
	$('#jqftwyradio2').fileTree({ root: '../../WYRADIO/', script: './scripts/php/jqueryFileTree.php' }, function(file) { 

	window.open(file);

		});
</script>
<!-- Para el jQueryFileTree -->
          <title>WyRadio Editor</title>
        </head>
        <body style="font-family: Verdana, sans-serif; font-size: x-small">
<h1>WyCron / WyRadio</h1>
<hr>
<h2>Explorer<input type="button" onClick="$('#jqftwyradio2').slideToggle();" class="wydevslidebutton"/></input></h2>
<div class="jqft" >
	<div id="jqftwyradio2" class="jqftwyradio" style="display:none;"</div>
</div>

<h2>Streams<input type="button" onClick="$('#showstream').slideToggle();" class="wydevslidebutton"/></input></h2>

<div id="showstream" name="addstream" style="display:none;">
	<?php
	  	if (!file_exists ("/wymedia/.wyradio/wyradio.db3")):
			echo "Creating DB";
			system ("mkdir /wymedia/.wyradio/");
			system ("cat /wymedia/usr/share/wymodcp/scripts/sql/CreateEmptyWyradioDB.sql | sqlite3 /wymedia/.wyradio/wyradio.db3");
		endif;

	  	if (!file_exists ("/wymedia/usr/share/wymodcp/WYRADIO/")):
			echo "Creating root WYRADIO";
			system ("mkdir /wymedia/usr/share/wymodcp/WYRADIO/");
		endif;
	  
		$dbfile = new PDO('sqlite:/wymedia/.wyradio/wyradio.db3');
		$selectsql = 'SELECT * FROM streamsources';



		echo "<form name='deletestream' action='wycron.php' method='POST'>";
		echo "<table>";
	?>

	<?php
		if (isset($_GET['delstreamcount'])) {

		echo "SQL Syntax:";

		for ($acrcount = 0; $acrcount < $_GET['delstreamcount']; $acrcount++) {

			$delthisid = "ID".$acrcount;
			$delthis = $_GET[$delthisid];

			$SQLDelete = "DELETE FROM streamsources WHERE acronym='".$delthis."';";
			echo $SQLDelete."<br>";					
	
			$dbfile->exec($SQLDelete);
			}	
		}
	?>	

	<?php
		
			  foreach ($dbfile->query($selectsql) as $returnrow) {
				$name = $returnrow['name'];
				$acronym = $returnrow['acronym'];
				$path = $returnrow['outfolder'];    
				$streamurl = $returnrow['url'];   

				if (file_exists ($path)):
					if (!file_exists ("/wymedia/usr/share/wymodcp/WYRADIO/".$acronym."/")):
						echo "Creating symlink for:".$path."<br>";
						system("ln -s ".$path." /wymedia/usr/share/wymodcp/WYRADIO/".$acronym ); 
					endif;
				else:
					echo "Creating folder:".$path."<br>";
					system("mkdir -p ".$path);	
					if (!file_exists ("/wymedia/usr/share/wymodcp/WYRADIO/".$acronym)):
						echo "Creating symlink for:".$path."<br>";
						system("ln -s ".$path." /wymedia/usr/share/wymodcp/WYRADIO/".$acronym ); 
					endif;
				endif;

				echo "<tr><td>";
					echo "<b>".$name." :</b></td>";
					echo "<td> <a href='./WYRADIO/".$acronym."'>".$acronym."</a></td>";
					echo "<td><a href='".$streamurl."'>Stream URL</a>";
					echo "<td><input type=checkbox name=deletestream[] value=".$acronym.">";
				echo "</td></tr>";

			    }
	 	echo "<tr><td><input id='deletebutton' name='deletebutton' class='button' type='button' onclick='DeleteStream();' value='Delete Stream'/></td></tr>";
		echo "</table>";
		echo "</form>";
	?>

</div>


	<h2>Add new stream<input type="button" onClick="$('#addstream').slideToggle();" class="wydevslidebutton"/></input></h2>
<div id="addstream" name="addstream" style="display:none;">

	<?php if (isset($_GET['addstream'])) {
	//form submit code here

	$asname = $_GET['name'];
	$asacronym = $_GET['acronym'];
	$asstreamurl = $_GET['streamurl'];
	$asoutfolder = $_GET['outfolder'];

	$dbfile->exec("INSERT INTO streamsources (name, acronym, url, outfolder ) VALUES ('".$asname."', '".$asacronym."', '".$asstreamurl."', '".$asoutfolder."' );");


	echo $asname;
	echo "<br>";
	echo $asacronym;
	echo "<br>";
	echo $asstreamurl;
	echo "<br>";
	echo $asoutfolder;
	echo "<br>";

	} else { ?>

	<form Name="addstream" action="./scripts/php/wycron.php" method="post">	
	Name: <input type="text" name="name" value="" />
	Acronym: <input type="text" name="acronym" value="" />	
	<br>
	Stream URL: <input type="text" name="streamurl" value="" size="75" /> </br>
	Out Folder: <input type="text" name="outfolder" value="/wymedia/Music/WYRADIO/" />
	<input id="addstream" name="addstream" class="button" type="button" onclick="AddStream();" value="Add Stream"/>

	</form>




	<?php } // end of form ?>

</div>



<h2>Shows<input type="button" onClick="$('#shows').slideToggle();" class="wydevslidebutton"/></input></h2>

<div id="shows" name="shows" style="display:none;">
<pre>
	<?php
		if (isset($_GET['delshowcount'])) {
		echo "SQL Syntax:";
		for ($acrcount = 0; $acrcount < $_GET['delshowcount']; $acrcount++) {
			$delthisid = "ID".$acrcount;
			$delthis = $_GET[$delthisid];
			$SQLDelete = "DELETE FROM shows WHERE acronym='".$delthis."';";
			echo $SQLDelete."<br>";					
	
			$dbfile->exec($SQLDelete);
			}	
		}
	?>
</pre>



	<form name='deleteshow' action='wycron.php' method='POST'>

	<?php

		$dbfile = new PDO('sqlite:/wymedia/.wyradio/wyradio.db3');
		$selectsql = 'SELECT * FROM shows';
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

	?>
	<input id='deleteshowbutton' name='deleteshowbutton' class='button' type='button' onclick='DeleteShow();' value='DeleteShow'/>
	</form>
	</pre>
</div>

<h2> Add Shows <input type="button" onClick="$('#addshow').slideToggle();" class="wydevslidebutton"/></input></h2>
<div id="addshow" name="addshow" style="display:none;">

<?php if (isset($_GET['addshow'])) {
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

	$insertsql = "INSERT INTO shows ([name],[acronym],[streamsourceid],[duration],[outsinglefile],[minute],[hour],[monthday],[weekday],[month]) VALUES ('".$asname."', '".$asacronym."', '".$asstreamsourceid."', ".$asduration.", '".$asoutsinglefile."',".$asminute.",".$ashour.",".$asmonthday.",".$asweekday.",".$asmonth." );";

	echo $insertsql;

	$dbfile->exec($insertsql);



	} else { ?>

	<form  Name="addshow" action="./scripts/php/wycron.php" method="post">

	<table><tr><td>Nombre</td><td>Emisora</td><td>Hora</td><td>Minuto</td><td>Dia del Mes</td><td>Mes</td><td>Dia de la Semana</td><td>Duracion</td><td>Archivo Unico</td></tr>
	<tr><td>
	<input type="text" name="showname" value="" />
	</td><td>
	<select name="streamsource">
		<?php 
		$selectsql = 'SELECT name,acronym FROM streamsources';
		  foreach ($dbfile->query($selectsql) as $returnrow) 
			{
			$name = $returnrow['name'];
			$acronym = $returnrow['acronym'];
			echo "<option value=".$acronym.">".$name."</option>";
			}


	 ?>
	</select>
	</td><td>
	<select name="hour">
		<option value="null">Todos</option>
		<?php for ($hour = 0; $hour <= 23; $hour++) { echo "<option value=".$hour.">".$hour."</option>"; } ?>
	</select>
	</td><td>
	<select name="minute">
		<option value="null">Todos</option>
		<?php for ($min = 0; $min <= 59; $min++) { echo "<option value=".$min.">".$min."</option>"; } ?>
	</select>
	</td><td>
	<select name="monthday">
		<option value="null">Todos</option>
		<?php for ($monthday = 1; $monthday <= 31; $monthday++) { echo "<option value=".$monthday.">".$monthday."</option>"; } ?>
	</select>
	</td><td>
	<select name="month">
		<option value="null">Todos</option>
		<option value="1">Enero</option>
		<option value="2">Febrero</option>
		<option value="3">Marzo</option>
		<option value="4">Abril</option>
		<option value="5">Mayo</option>
		<option value="6">Junio</option>
		<option value="7">Julio</option>
		<option value="8">Agosto</option>
		<option value="9">Septiembre</option>
		<option value="10">Octubre</option>
		<option value="11">Noviembre</option>
		<option value="12">Diciembre</option>
	</select>
	</td><td>

	<select name="weekday">
		<option value="null">Todos</option>
		<option value="1">Lunes</option>
		<option value="2">Martes</option>
		<option value="3">Miercoles</option>
		<option value="4">Jueves</option>
		<option value="5">Viernes</option>
		<option value="6">Sabado</option>
		<option value="0">Domingo</option>
	</select>
	</td><td>


	<input type="text" name="duration" default="3600" size="6">
	</td><td>
	<input type="checkbox" name="singlefile" default="1">
	</td>
	<tr><td><input id="addshow" name="addshow" class="button" type="button" onclick="AddShow();" value="Add Show"/></td></tr>
	</table>

	</form>
	<?php } // end of form ?>
</div>

<hr>

<?php if (isset($_GET['iceshandler'])) {
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

if ($action == "start"){ 
   
   $cmd = 'ices -h '.$address.' -m /'.$mountpoint.' -P \''.$mountpwd.'\' -F '.$playlist.' -t http -p '.$port.' -v -b 128 -n '.$name.' -g '.$description.' -i -B' ; 

echo $cmd;

    exec($cmd);
}


if ($action == "next"){ 
   exec('pkill -USR1 ices'); 
}


if ($action == "kill"){ 
   exec('pkill -9 ices'); 
}


if ($action == "reload"){ 
   exec('pkill -HUP ices'); 
}



if ($action == "feed"){
   system("mkdir -p /wymedia/icesconf");
   $cmd = '/wymedia/usr/bin/find '.$startfolder.' -name '.$filter.' > '.$playlist ; 
  
   echo $cmd;
    system($cmd);


}

}
?>

 <form Name="icesdata" method="GET" action="./scripts/php/wycron.php" >
<table>
<tr><td>
Server Address: </td><td> <input type="text" name="address" value="w.x.y.z" /> </td><td>
Mount Point: </td><td> <input type="text" name="mountpoint" value="stream" /> </td><td>
Server Port: </td><td> <input type="text" name="port" value="8000" /> </td></tr><tr><td>

Mount Password: </td><td> <input type="password" name="mountpwd" value="" /> </td><td>
Station Name: </td><td> <input type="text" name="name" value="WyRadio" /> </td><td>
Stream Description: </td><td> <input type="text" name="description" value="WyRadio now streaming" /> </td></tr><tr><td>

</tr><tr> </tr><tr> </tr><tr><td>
Playlist file: </td><td> <input type="text" name="playlist" value="/wymedia/icesconf/playlist.txt" size=30 /> </td></tr><tr><td>

Folder: </td><td> <input type="text" name="startfolder" value="/wymedia/Music/WYRADIO/IGR/" size=30 /> </td></tr><tr><td>
Name filter: </td><td> <input type="text" name="filter" value="*2015*.mp3" size=30 /> </td></tr>

</table>


<p> 

<input id="startices" name="startices" class="button" type="button" onclick="IcesHandler('start');" value="Start Ices"/>
<input id="nextices" name="nextices" class="button" type="button" onclick="IcesHandler('next');" value="Next"/>
<input id="killices" name="killices" class="button" type="button" onclick="IcesHandler('kill');" value="Kill"/>
<input id="reloadices" name="reloadices" class="button" type="button" onclick="IcesHandler('reload');" value="Reload playlist"/>
<input id="feedices" name="feedices" class="button" type="button" onclick="IcesHandler('feed');" value="Feed playlist"/>
</p>

</form>
<?php
$icespid = exec("pidof ices");
if($icespid > 0){
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
--------------- minuto (0 - 59) 
|  .------------- hora (0 - 23)
|  |  .---------- día del mes (1 - 31)
|  |  |  .------- mes (1 - 12) O jan,feb,mar,apr ... (los meses en inglés)
|  |  |  |  .---- día de la semana (0 - 6) (Domingo=0 ó 7) O sun,mon,tue,wed,thu,fri,sat (los días en inglés) 
|  |  |  |  |
*  *  *  *  *  comando para ser ejecutado

</pre>

            <?php
            if (isset($_GET['crontab'])):
                  ?><p style="color: red">The crontab file will be updated.</p><?php           
                // Negate escaping
                if (get_magic_quotes_gpc())
                {
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
                    ?><p style="color: red">The crontab file was updated successfully.</p><?php
                else:
                    ?><p style="color: red">The crontab file could not be updated.</p><?php
                endif;

		?><p style="color: red">Trying to restart crond:</p><?php
	
			system ("/wymedia/usr/etc/init.d/crond stop");		      
			system ("/wymedia/usr/etc/init.d/crond start");
		      
		else:

		//echo "nodata";

            endif;
            ?>

            <form name="wycron" id="wycron" action="./scripts/php/wycron.php" method="post">
                <div>
                    <textarea name="crontab" cols="120" rows="30"><?php
                        if (file_exists($cronFile))
                            echo file_get_contents($cronFile);
			    ?></textarea>
                    
<input id="updatecron" name="updatecron" class="button" type="button" onclick="WyCron(document.wycron.crontab.value)" value="Update Crontab"/>
 
                </div>
            </form>
            
       </body>
    </html>
    <?php } ?>
