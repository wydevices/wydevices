<?php
$id             = $_REQUEST["channel"];
$newid          = $_REQUEST["neworder"]; 
$newname        = $_REQUEST["newname"];  
$totalchannels  = $_REQUEST["totalchannels"] + 1;  
$backops        = $_REQUEST["backops"];  
$operation      = $_REQUEST["op"];
?>
<div id="container">
<!-- Server side update and rename operations -->
<?php
	//Update channels when needed.

switch ($operation){
	case "ajaxreorder":
		$objDB = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
		$sql = "update T_SERVICE set LOGICAL_CHANNEL_NUMBER=".$totalchannels." where LOGICAL_CHANNEL_NUMBER=".$newid;
		$res = $objDB->query($sql);
		$sql = "update T_SERVICE set LOGICAL_CHANNEL_NUMBER=".$newid." where LOGICAL_CHANNEL_NUMBER=".$id;
		$res = $objDB->query($sql);
		$sql = "update T_SERVICE set LOGICAL_CHANNEL_NUMBER=".$id." where LOGICAL_CHANNEL_NUMBER=".$totalchannels;
		$res = $objDB->query($sql);
		break;

	case "rename":
		$objDB = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
		$sql = "update T_SERVICE set NAME=\"".$newname."\" where LOGICAL_CHANNEL_NUMBER=".$id.";";
		echo $sql;
		$res = $objDB->query($sql);
		break;
	
	case "backops":
		switch ($backops) {
			case "restore": 
				echo "<pre>";
				system ("/wymedia/usr/bin/Restore_channels-net /wymedia/Backup/channels-net_backup.tar");
				echo "</pre>";
				break;
			case "backup":
				echo "<pre>";
				system ("/wymedia/usr/bin/Backup_channels-net");
				echo "</pre>";
				break;
			default: echo "No Backup operation detected.";
				break;
			}
		break;

	default:
		echo $operation;
    break;
}
  echo "<h2>Channel List</h2><br/>";
  echo "<div class=\"channellistcontainer\">";

	$Ctotal=0;
	$dbfile = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$selectsql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
  foreach ($dbfile->query($selectsql) as $returnrow) {
    $channelid = $returnrow['LOGICAL_CHANNEL_NUMBER'];
    $Displaycolumn = $Ctotal%"4";			
    $channelname = $returnrow['NAME'];
    $Ctotal = $Ctotal+1;

    switch ($Displaycolumn) {
      case 0:
        echo  "<div id=pos".$Ctotal." class=\"column1\">";
        break;
      case 1:
        echo  "<div id=pos".$Ctotal." class=\"column2\">";
        break;
      case 2: 
        echo  "<div id=pos".$Ctotal." class=\"column3\">";
        break;
      case 3:
        echo  "<div id=pos".$Ctotal." class=\"column4\">";
        break;
      default:
        echo "default";
        break;
    }
    echo  "<a href='#' onclick=logicfire(".$Ctotal.",".$channelid."); style='line-height=40px'>".$channelid." - ".$channelname."</a></div>";
  }
?>
	</div> <!-- End of Channel list container -->
	<h2>Modify Channel List</h2>
	<form id=channel name=channelform action="javascript:UpdateTV(this.form);" method="put">
  <!-- Channel combo box -->
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
    <!-- End Channel combo box -->

    <input type="hidden" id="totalchannels" name="totalchannels" class="text" value="<?php echo $total ?>" />
    <input type="hidden" id="stage" name="stage" class="text" value="0" />
    <hr /><br />
    <label>New Name:</label>
    <input id="newname" name="newname" class="text" type="text" maxlength="20" value="" />
    <input type="button" class="button" style="width: 100px" onClick="javascript:ChannelRename()" value="Rename" />
    <hr /><br />
    <div id="backopsdiv" class="backdivclass">
      <h2>Backup and Restore operations</h2>
      <input type="button" class="button" style="width: 100px" onClick="javascript:Backops('backup')" value="Backup" />&nbsp;&nbsp;
      <input type="button" class="button" style="width: 100px" onClick="javascript:Backops('restore')" value="Restore" />
    </div>
	</form>
</div>
