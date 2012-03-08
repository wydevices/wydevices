<?php
/*
 * This file is part of Wydevices project, http://code.google.com/p/wydevices
 * 
 * Wydevices record utility
 * 
 * Author : Argos <argos66@gmail.com>
 * 
 * TODO :
 *    - Add a cleaner for myrecords.fxd (In conflict record, Rules not linked, Record without video content, etc ...)
 *    - Group records are in the same schedule rule and add a possibility to expand this group.
 *    - Schedule a single record.
 *    - Schedule a periodic record.
 *    - Rename a record.
 *    - Download only N chunck from start or end.
 *    - Create a cron job for run a rec2vid.
 *    - FIX: Template bug when you are lot of records to display (cut into pages).
 * 
 */

$myrecord_path = "/wymedia/timeshift/"; //Path of the myrecords.fxd file
$record_path   = $myrecord_path."records/"; //Path of records
$myrecord_name = "myrecords.fxd"; //Name of principal record file
$recordxml_name= "record.xml";

include("func.records.php");

/*
 * Load myrecords.fxd file, this files contain scheduled record
 */
if (!file_exists($myrecord_path.$myrecord_name)) {
    echo "XML record file doesn't exist (".$myrecord_path.$myrecord_name.")\n";
    exit;
}

if($xml_records=simplexml_load_file($myrecord_path.$myrecord_name)) {
    $nb_rules = count($xml_records->periodicity->rule);
    $nb_record = count($xml_records->recordings->recording);
    $last_saved = $xml_records->general['save_time'];

    for ($p = 0; $p < $nb_rules; $p++) { //Get periodic name
        $periodicity = $xml_records->periodicity->rule[$p];
        $record_periodicity[$p] = $periodicity->extern_name; //Get perdiocity name (daily, weekly, etc ...)
    }
} else {
    trigger_error("Error reading XML file", E_USER_ERROR);
    exit;
}

/*
 * Remove and Cleanup part
 */
if ($_GET['remove'] == 1 && !empty($_GET['remove'])) {
    gui_splash("shutdown");

    $xml_write_records = new SimpleXMLElement($myrecord_path.$myrecord_name, null, true);
    $xml_write_records->general['save_time'] = time(); //Get current UNIX time into XML buffer

    if (isset($_GET['id']) && intval($_GET['id']) >= 0 ) {
        $del_record_id = intval($_GET['id']);
        unset($xml_write_records->recordings->recording[$del_record_id]);
        echo $del_record_id." As del_record_ID<br>";
    }

    if (isset($_GET['path']) && !empty($_GET['path'])) {
        echo $record_path.$_GET['path']." - As rmdir recursive vars<br>";
        rmdir_recursive($record_path.$_GET['path']);
    }

    $new_myrecords = fix_xml_output($xml_write_records->asXML()); //Fix XML generated code by asXML function

    //Write updated XML data into myrecords.fxd by regenerating this file
    $myrecord_write = fopen($myrecord_path.$myrecord_name, 'w') or die("ERROR : Can't open file ".$myrecord_path.$myrecord_name);
    fwrite($myrecord_write, $new_myrecords);
    fclose($myrecord_write);

    unset($xml_write_records);
    gui_splash("start");
}

/*
 * Rename a record
 */
if ($_GET['rename'] == 1 && !empty($_GET['rename'])) {
    // myrecords.fxd rename part only if ID and newname param exist
    if (isset($_GET['id']) && intval($_GET['id']) >= 0 ) {
        $xml_write_records = new SimpleXMLElement($myrecord_path.$myrecord_name, null, true);
        $xml_write_records->general['save_time'] = time(); //Get current UNIX time into XML buffer
        $ren_record_id = intval($_GET['id']);

        if (isset($_GET['newname'])) {
            gui_splash("shutdown");
            unset($xml_write_records->recordings->recording[$ren_record_id][name]);
            $xml_write_records->recordings->recording[$ren_record_id]->addAttribute('name', $_GET['newname']);
            $new_myrecords = fix_xml_output($xml_write_records->asXML()); //Fix XML generated code by asXML function

            //Write updated XML data into myrecords.fxd by regenerating this file
            $myrecord_write = fopen($myrecord_path.$myrecord_name, 'w') or die("ERROR : Can't open file ".$myrecord_path.$myrecord_name);
            fwrite($myrecord_write, $new_myrecords);
            fclose($myrecord_write);

            unset($xml_write_records);
            gui_splash("start");
        }
    }

    // record.xml rename part only if record.xml exist in path and newname param exist
    $recordxml_fullpath = $record_path."/".$_GET['path']."/".$recordxml_name;
    if (isset($_GET['newname']) && isset($_GET['path']) && file_exists($recordxml_fullpath)) {
        $xml_write_records = new SimpleXMLElement($recordxml_fullpath, null, true);
        $xml_write_records->general['save_time'] = time(); //Get current UNIX time into XML buffer

        $xml_write_records->record->information->name = $_GET['newname'];
        $new_myrecords = fix_xml_output($xml_write_records->asXML()); //Fix XML generated code by asXML function

        //Write updated XML data into record.xml by regenerating this file
        $myrecord_write = fopen($recordxml_fullpath, 'w') or die("ERROR : Can't open file ".$recordxml_fullpath);
        fwrite($myrecord_write, $new_myrecords);
        fclose($myrecord_write);

        unset($xml_write_records);
    }
}

/*
 * Download a record
 */
if (isset($_GET['name']) && !empty($_GET['name']) && isset($_GET['path']) && !empty($_GET['path'])) {
    $record_file_dir = $record_path.$_GET['path']."/";
    $filename = $_GET['name'];

    if (file_exists($record_file_dir)) {
        unset($record_chunk_filepath, $record_filesize);
        $i = 0;

        $handle_record_file_dir = opendir($record_file_dir);

        //List all chunks of one record into an array
        while (false !== ($record_file = readdir($handle_record_file_dir))) {
            if ($record_file != "." && $record_file != ".." && strstr($record_file, '.ts')) {
                $record_filesize = $record_filesize + filesize($record_file_dir.$record_file);
                $record_chunk_filepath[$i] = $record_file_dir.$record_file;
                $i++;
            }
        }
        closedir($handle_record_file_dir);
    }

    //Purge filename for Operating System compatibility
    $filename_download = str_replace(" ", "_", $filename);
    $filename_download = str_replace("/", "", $filename_download);
    $filename_download = str_replace(":", "h", $filename_download);

    //Create download stream header
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: public", false);
    header("Accept-Ranges: bytes");
    header("Content-Transfer-Encoding: binary");
    header("Content-type: application/force-download");
    header("Content-Disposition: attachment; filename=\"".$filename_download.".ts\"");
    header("Content-Length: ".$record_filesize);

    set_time_limit(0); //Stop timeout counter

    /*
     * Warning     : At this time, all error will be logged into the .ts file ! (use a Hex editor for check error return)
     * Warning     : This download code don't support browser pause or reprise.
     * Information : Chunk max size = 20MB (Number of seconds per chunk depend of AV compression ratio)
     */

    //Loop each chunks to buffer
    for ($r = 0; $r < $i; $r++) {
        $chunk_size = filesize($record_chunk_filepath[$r]);
        $chunk_handle = fopen($record_chunk_filepath[$r], 'r') or die("Error opening file");

        //Loop for download one chunk
        while (!feof($chunk_handle)) {
            print fread($chunk_handle, $chunk_size) or die("Error reading file");
            flush(); //Free buffers
        }
        fclose($record_chunk_filepath[$r]);
    }
    exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>WyMod Control Panel v3.1</title>
    <script src="scripts/js/wydev.js" type="text/javascript"></script>
</head>
<body>
<h2>Records List</h2>

<?php
/*
 * Display content of myrecords.fxd file
 */
//echo "There are ".$nb_record." records.<br />";
//echo "There are ".$nb_rules." periodicity rules.<br />";
echo "Last modify on ".date("Y-m-d H:i:s", intval($last_saved)).".<br />";

// Added extra read for ajax refreshed info
if($xml_records=simplexml_load_file($myrecord_path.$myrecord_name)) {
    $nb_rules = count($xml_records->periodicity->rule);
    $nb_record = count($xml_records->recordings->recording);
    $last_saved = $xml_records->general['save_time'];

    for ($p = 0; $p < $nb_rules; $p++) { //Get periodic name
        $periodicity = $xml_records->periodicity->rule[$p];
        $record_periodicity[$p] = $periodicity->extern_name; //Get perdiocity name (daily, weekly, etc ...)
    }
} else {
    trigger_error("Error reading XML file", E_USER_ERROR);
    exit;
}
// Added extra read for ajax refreshed info

echo "<div id=\"recordsdiv\">";
echo "<table>";
echo "<tr><td colspan=\"8\"><hr width=\"100%\" /></td></tr>";
echo "<tr>
        \t<td><b>Action</b></td>
        \t<td><b>Date</b></td>
        \t<td><b>Frequency</b></td>
        \t<td><b>Channel</b></td>
        \t<td><b>Status</b></td>
        \t<td><b>Record name</b></td>
        \t<td><b>Duration</b></td>
        \t<td align=\"center\"><b>Size</b></td>
    </tr>";
echo "<tr><td colspan=\"8\"><hr width=\"100%\" /></td></tr>";

unset($r, $record_file_dir, $recording);

//Loop into myrecords.fxd for each record and get informations
for ($i = 0; $i < $nb_record; $i++) {
    unset($record_file_info, $record_status, $record_size, $record_size_mb, $record_duration, $record_periodic, $record_name, $record_id, $record_periodic_id, $record_name_link);
    $recording = $xml_records->recordings->recording[$i];

    //Get value from XML and convert it if necessary
    $record_periodic_id = $recording['periodicity_rule_id'];
    $record_name = $recording['name'];
    $record_id = intval($recording['id']);
    $record_start_time = date("Y-m-d H:i:s", intval($recording['start_time'] - $recording['start_padding']));
    $record_duration = date("H:i:s", intval($recording['stop_time'] - $recording['start_time'] - 3600));
    $record_channel = $recording->service['name'];
    $record_file_info = $recording->file_info['file'];
    $record_status = $recording['status'];

    //Get the record size into the record.xml
    if (file_exists($record_file_info) && false !== ($xml_record=simplexml_load_file($record_file_info))) {
        $r++;
        $record_size = $xml_record->record[0]->information->size;
        $record_size_kb = substr($record_size, 0, strlen($record_size) - 3); //Get only KB size
        $record_size_mb = round($record_size_kb / 1048576,1); //Record size in MB
        $record_size_mb .= "GB";

        //Get only record directory name
        $record_file_dir[$r] = str_replace($recordxml_name, "", $record_file_info);
        $record_file_dir[$r] = str_replace($record_path, "", $record_file_dir[$r]);
        $record_file_dir[$r] = str_replace("/", "", $record_file_dir[$r]);

        $record_name_link = "<a href=\"scripts/php/records.php?path=".$record_file_dir[$r]."&amp;name=".$record_name."\">".$record_name."</a>";
        $RecordsUri = "scripts/php/records.php?id=".$i."&amp;remove=1&amp;path=".$record_file_dir[$r];
    } else {
        $record_name_link = $record_name;
        $RecordsUri = "scripts/php/records.php?id=".$i."&amp;remove=1";
    }

    switch($record_status){
        case 0  : $record_status = "<img src=\"style/expired.png\" title=\"Unknown\" alt=\"\" />"; break;
        case 1  : $record_status = "<img src=\"style/awaiting.png\" title=\"Scheduled\" alt=\"\" />"; break;
        case 2  : $record_status = "<img src=\"style/media-record.png\" title=\"Running\" alt=\"\" />"; break;
        case 3  : $record_status = "<img src=\"style/expired.png\" title=\"In conflict\" alt=\"\" />"; break;
        case 4  : $record_status = "<img src=\"style/available.png\" title=\"Completed\" alt=\"\" />"; break;
        case 5  : $record_status = "<img src=\"style/expired.png\" title=\"Canceled\" alt=\"\" />"; break;  
        case 6  : $record_status = "<img src=\"style/expired.png\" title=\"Missed\" alt=\"\" />"; break;  
        case 7  : $record_status = "<img src=\"style/expired.png\" title=\"Aborted\" alt=\"\" />"; break;     
        case 8  : $record_status = "<img src=\"style/expired.png\" title=\"Macrovision\" alt=\"\" />"; break;   
        case 9  : $record_status = "<img src=\"style/expired.png\" title=\"Disk space error\" alt=\"\" />"; break;      
        case 10 : $record_status = "<img src=\"style/expired.png\" title=\"System failure\" alt=\"\" />"; break;       
        default : $record_status = "<img src=\"style/expired.png\" title=\"Unknown\" alt=\"\" />"; break;
    }

    echo "<tr>
            \t<td align=\"center\">
                <a onclick=\"confirmation('! This action going to restart your Wybox GUI !\\nAre you sure to delete (id ".$i.")\\n".$record_name." ?','".$RecordsUri."');\" href=\"#\">
                    <img border=\"0\" src=\"style/process-stop.png\" title=\"Delete ID : ".$i." LINK : ".$RecordsUri."\" alt=\"\" />
                </a>
            </td>
            \t<td>".$record_start_time."</td>";

    if ($record_periodic_id > 0) {
        echo "\t<td align=\"center\">".$record_periodicity[$record_periodic_id - 1]."</td>";
    } else {
        echo "\t<td align=\"center\"></td>";
    }

    echo "\t<td>".$record_channel."</td>
            \t<td align=\"center\">".$record_status."</td>
            \t<td>
                <table>
                    <tr><td>
                        <button class=\"renamebutton\" onClick=
                            \"
                                $('#renamedivid".$i."').slideToggle();
                                $('#reninputdivid".$i."').slideToggle();
                            \" 
                            src=\"style/rename.png\" title=\"Rename\" alt=\"Rename a record...\">
                        </button>
                    </td><td>
                        <div id=\"renamedivid".$i."\">".$record_name_link."</div>
                        <div id=\"reninputdivid".$i."\" style=\"display:none;\">
                            <input type=\"text\" name=\"renameto\" id=\"renametoid".$i."\" value=\"".$record_name."\" />
                            <button class=\"valid_renamebutton\" onClick=
                                \"
                                    var var_new_name = document.getElementById('renametoid".$i."').value;
                                    $('#renamedivid".$i."').slideToggle();
                                    $('#reninputdivid".$i."').slideToggle();
                                    recordsuri = 'scripts/php/records.php?rename=1&amp;newname='+var_new_name+'&amp;id=".$i."&amp;path=".$record_file_dir[$r]."';
                                    //alert(recordsuri);
                                    confirmation('! This action going to restart your Wybox GUI !\\nAre you sure to rename :\\n".$record_name."\\ninto :\\n'+var_new_name+'  ?',recordsuri);
                                \" 
                                src=\"style/rename.png\" title=\"OK for this name\" alt=\"Rename a record...\">
                            </button>
                        </div>
                    </td></tr>
                </table>
            </td>
            \t<td>".$record_duration."</td>
            \t<td>".$record_size_mb."</td>";
    echo "</tr>";
}

/*
 * List all records not referenced into myrecords.fxd
 */
if ($handle_record_path = opendir($record_path)) {
    $nb_record_dir = count($record_file_dir);

    $j = 0;
    
    //List all record directory not existing into myrecords.fxd
    while (false !== ($record_dir = readdir($handle_record_path))) {
        $record_dir_match = 0;
        $j++;
        for ($i = 0; $i <= $nb_record_dir; $i++) {
            if ($record_dir == $record_file_dir[$i]) $record_dir_match = 1;
        }
        $record_file_info = $record_path.$record_dir."/".$recordxml_name;

        //Get only record directory name
        $record_file_path = str_replace($recordxml_name, "", $record_file_info);
        $record_file_path = str_replace($record_path, "", $record_file_path);
        $record_file_path = str_replace("/", "", $record_file_path);

        if (!$record_dir_match && file_exists($record_file_info) && filesize($record_file_info) > 0 && false !== ($xml_record=simplexml_load_file($record_file_info))) {
            $record_size = $xml_record->record[0]->information->size;
            $record_size_kb = substr($record_size, 0, strlen($record_size) - 3); //Get only KB size
            $record_size_mb = round($record_size_kb / 1048576,1); //Record size in MB
            $record_size_mb .= " GB";

            $record_start_time = date("Y-m-d H:i:s", intval($xml_record->record[0]->information->start_time));
            $record_channel = $xml_record->record[0]->information->channel;
            $record_name = $xml_record->record[0]->information->name;
            $record_duration = date("H:i:s", intval($xml_record->record[0]->information->stop_time - $xml_record->record[0]->information->start_time));
            $record_status = "<img src=\"style/modem.png\" title=\"On disk\" alt=\"\" />";

            $record_name_link = "<a href=\"scripts/php/records.php?path=".$record_file_path."&amp;name=".$record_name."\">".$record_name."</a>";

            echo "<tr>
                    \t<td align=\"center\">
                        <a onclick=\"confirmation('! This action going to restart your Wybox GUI !\\nAre you sure to delete ".$record_name." ?','scripts/php/records.php?path=".$record_file_path."&amp;remove=1');\" href=\"#\">
                            <img border=\"0\" src=\"style/process-stop.png\" title=\"Delete\" alt=\"\" />
                        </a>
                       </td>
                    \t<td>".$record_start_time."</td>
                    \t<td></td>
                    \t<td>".$record_channel."</td>
                    \t<td align=\"center\">".$record_status."</td>
                    \t<td>
                        <table>
                            <tr><td>
                                <button class=\"renamebutton\" onClick=
                                    \"
                                        $('#renamedivhddid".$j."').slideToggle();
                                        $('#reninputdivhddid".$j."').slideToggle();
                                    \" 
                                    src=\"style/rename.png\" title=\"Rename\" alt=\"Rename a record...\">
                                </button>
                            </td><td>
                                <div id=\"renamedivhddid".$j."\">".$record_name_link."</div>
                                <div id=\"reninputdivhddid".$j."\" style=\"display:none;\">
                                    <input type=\"text\" name=\"renameto\" id=\"renametoidhdd".$j."\" value=\"".$record_name."\" />
                                    <button class=\"\" onClick=
                                        \"
                                            var var_new_name = document.getElementById('renametoidhdd".$j."').value;
                                            $('#renamedivhddid".$j."').slideToggle();
                                            $('#reninputdivhddid".$j."').slideToggle();
                                            recordsuri = 'scripts/php/records.php?rename=1&amp;newname='+var_new_name+'&amp;path=".$record_file_path."';
                                            //alert(recordsuri);
                                            confirmation('! This action going to restart your Wybox GUI !\\nAre you sure to rename :\\n".$record_name."\\ninto :\\n'+var_new_name+'  ?',recordsuri);
                                        \" 
                                        src=\"style/rename.png\" title=\"Rename\" alt=\"Rename a record...\">
                                    </button>
                                </div>
                            </td></tr>
                        </table>
                    </td>
                    \t<td>".$record_duration."</td>
                    \t<td>".$record_size_mb."</td>\n</tr>";
        } elseif (!$record_dir_match && file_exists($record_file_info) && filesize($record_file_info) == 0) { //Malformed case : record.xml = 0 byte
            echo "<tr>
                    <td align=\"center\">
                        <a onclick=\"confirmation('! This action going to restart your Wybox GUI !\\nAre you sure to delete ".$record_name." ?','scripts/php/records.php?path=".$record_file_path."&amp;remove=1');\" href=\"#\">
                            <img border=\"0\" src=\"style/edit-clear.png\" title=\"Clean\" alt=\"\" />
                        </a>
                    </td>
                    <td colspan=\"3\"><i>Malformed record.xml</i></td>
                    <td align=\"center\"><img src=\"style/important.png\" title=\"Error\" alt=\"\" /></td>
                    <td colspan=\"3\"><i>in ".$record_file_path."</i></td>
                  </tr>\n";
        }
    }
    closedir($handle_record_path);
} else {
    echo "<tr><td colspan=\"8\"><b><i>Cannot open ".$record_path." directory !</i></b></td></tr>\n";
}
?>
</table>
</div>
</body>
</html>
