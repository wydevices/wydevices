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
 *    - Delete a record.
 *    - Download only N chunck from start or end.
 *    - Create a cron job for run a rec2vid.
 *    - FIX: Template bug when you are lot of records to display (cut into pages).
 * 
 */

$myrecord_path = "/wymedia/timeshift/"; //Path of the myrecords.fxd file
$record_path   = $myrecord_path."records/"; //Path of records
$myrecord_name = "myrecords.fxd";
$recordxml_name= "record.xml";

// Function to recursively delete a directory
function rmdir_recursive($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rmdir_recursive($dir."/".$object); else unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

/*
 * Remove and Cleanup part
 */
if ($_GET['remove'] == 1 && !empty($_GET['remove']) && isset($_GET['path']) && !empty($_GET['path'])) {
    rmdir_recursive($_GET['path']);
    header("Location: /index.html");
    exit;
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

echo "There are ".$nb_record." records.<br />";
echo "There are ".$nb_rules." periodicity rules.<br />";
echo "Last modify on ".date("Y-m-d H:i:s", intval($last_saved)).".<br /><br />";
echo "<table>";
echo "<tr>
        \t<td><b>Action</b></td>
        \t<td><b>Date</b></td>
        \t<td><b>Periodicity</b></td>
        \t<td><b>Channel</b></td>
        \t<td><b>Status</b></td>
        \t<td><b>Record name</b></td>
        \t<td><b>Duration</b></td>
        \t<td><b>Size</b></td>
    </tr>";

unset($r, $record_file_dir, $recording);

//Loop into myrecords.fxd for each record and get informations
foreach ($xml_records->recordings->recording as $recording) {
    unset($record_file_info, $record_status, $record_size, $record_size_mb, $record_duration, $record_periodic, $record_name, $record_periodic_id);

    //Get value from XML and convert it if necessary
    $record_periodic_id = $recording['periodicity_rule_id'];
    $record_name = $recording['name'];
    $record_start_time = date("Y-m-d H:i:s", intval($recording['start_time'] - $recording['start_padding']));
    $record_duration = date("H:i:s", intval($recording['stop_time'] - $recording['start_time'] - 3600));
    $record_channel = $recording->service['name'];
    $record_file_info = $recording->file_info['file'];
    $record_status = $recording['status'];

    //Get the record size into the record.xml
    if (file_exists($record_file_info) && false !== ($xml_record=simplexml_load_file($record_file_info))) {
        $record_size = $xml_record->record[0]->information->size;
        $record_size_kb = substr($record_size, 0, strlen($record_size) - 3); //Get only KB size
        $record_size_mb = round($record_size_kb / 1048576,1); //Record size in MB
        $record_size_mb .= " GB";

        //Get only record directory name
        $record_file_dir[$r] = str_replace($recordxml_name, "", $record_file_info);
        $record_file_dir[$r] = str_replace($record_path, "", $record_file_dir[$r]);
        $record_file_dir[$r] = str_replace("/", "", $record_file_dir[$r]);

        $record_name = "<a href=\"scripts/php/records.php?path=".$record_file_dir[$r]."&amp;name=".$record_name."\">".$record_name."</a>";
        $r++;
    }

    switch($record_status){
        case 0  : $record_status = "<img src=\"style/expired.png\" title=\"Unknown\" />"; break;
        case 1  : $record_status = "<img src=\"style/awaiting.png\" title=\"Scheduled\" />"; break;
        case 2  : $record_status = "<img src=\"style/media-record.png\" title=\"Running\" />"; break;
        case 3  : $record_status = "<img src=\"style/expired.png\" title=\"In conflict\" />"; break;
        case 4  : $record_status = "<img src=\"style/available.png\" title=\"Completed\" />"; break;
        case 5  : $record_status = "<img src=\"style/expired.png\" title=\"Canceled\" />"; break;
        case 6  : $record_status = "<img src=\"style/expired.png\" title=\"Missed\" />"; break;
        case 7  : $record_status = "<img src=\"style/expired.png\" title=\"Aborted\" />"; break;
        case 8  : $record_status = "<img src=\"style/expired.png\" title=\"Macrovision\" />"; break;
        case 9  : $record_status = "<img src=\"style/expired.png\" title=\"Disk space error\" />"; break;
        case 10 : $record_status = "<img src=\"style/expired.png\" title=\"System failure\" />"; break;
        default : $record_status = "<img src=\"style/expired.png\" title=\"Unknown\" />"; break;
    }

    echo "<tr>
            \t<td></td>
            \t<td>".$record_start_time."</td>";

    if ($record_periodic_id > 0) {
        echo "\t<td align=\"center\">".$record_periodicity[$record_periodic_id - 1]."</td>";
    } else {
        echo "\t<td align=\"center\"></td>";
    }

    echo "\t<td>".$record_channel."</td>
            \t<td align=\"center\">".$record_status."</td>
            \t<td>".$record_name."</td>
            \t<td>".$record_duration."</td>
            \t<td>".$record_size_mb."</td>";
    echo "</tr>";
}

/*
 * List all records not referenced into myrecords.fxd
 */
if ($handle_record_path = opendir($record_path)) {
    $nb_record_dir = count($record_file_dir);

    //List all record directory not existing into myrecords.fxd
    while (false !== ($record_dir = readdir($handle_record_path))) {
        $record_dir_match = 0;

        for ($i = 0; $i <= $nb_record_dir; $i++) {
            if ($record_dir == $record_file_dir[$i]) $record_dir_match = 1;
        }
        $record_file_info = $record_path.$record_dir."/".$recordxml_name;

        //Get full path of record.xml directory
        $recordxml_dir    = str_replace("/".$recordxml_name, "", $record_file_info);

        if (!$record_dir_match && file_exists($record_file_info) && filesize($record_file_info) > 0 && false !== ($xml_record=simplexml_load_file($record_file_info))) {
            $record_size = $xml_record->record[0]->information->size;
            $record_size_kb = substr($record_size, 0, strlen($record_size) - 3); //Get only KB size
            $record_size_mb = round($record_size_kb / 1048576,1); //Record size in MB
            $record_size_mb .= " GB";

            $record_start_time = date("Y-m-d H:i:s", intval($xml_record->record[0]->information->start_time));
            $record_channel = $xml_record->record[0]->information->channel;
            $record_name = $xml_record->record[0]->information->name;
            $record_duration = date("H:i:s", intval($xml_record->record[0]->information->stop_time - $xml_record->record[0]->information->start_time));
            $record_status = "<img src=\"style/modem.png\" title=\"On disk\" />";

            //Get only record directory name
            $record_file_path = str_replace($recordxml_name, "", $record_file_info);
            $record_file_path = str_replace($record_path, "", $record_file_path);
            $record_file_path = str_replace("/", "", $record_file_path);

            $record_name = "<a href=\"scripts/php/records.php?path=".$record_file_path."&amp;name=".$record_name."\">".$record_name."</a>";

            echo "<tr>
                    \t<td align=\"center\">
                        <a onclick=\"if (confirm('Are you sure to delete \\n".$recordxml_dir." ?')) window.location = 'scripts/php/records.php?path=".$recordxml_dir."&amp;remove=1';\" href=\"#\">
                            <img border=\"0\" src=\"style/process-stop.png\" title=\"Delete\" />
                        </a>
                       </td>
                    \t<td>".$record_start_time."</td>
                    \t<td></td>
                    \t<td>".$record_channel."</td>
                    \t<td align=\"center\">".$record_status."</td>
                    \t<td>".$record_name."</td>
                    \t<td>".$record_duration."</td>
                    \t<td>".$record_size_mb."</td>\n</tr>";
        } elseif (!$record_dir_match && file_exists($record_file_info) && filesize($record_file_info) == 0) { //Malformed case : record.xml = 0 byte
            echo "<tr>
                    <td align=\"center\">
                        <a onclick=\"if (confirm('Are you sure to delete \\n ".$recordxml_dir." ?')) window.location = 'scripts/php/records.php?path=".$recordxml_dir."&amp;remove=1';\" href=\"#\">
                            <img border=\"0\" src=\"style/edit-clear.png\" title=\"Clean\" />
                        </a>
                    </td>
                    <td colspan=\"3\"><i>Malformed record.xml</i></td>
                    <td align=\"center\"><img src=\"style/important.png\" title=\"Error\" /></td>
                    <td colspan=\"3\"><i>in ".$recordxml_dir."</i></td>
                  </tr>\n";
        }
    }
    closedir($handle_record_path);
} else {
    echo "<tr><td colspan=\"8\"><b><i>Cannot open ".$record_path." directory !</i></b></td></tr>\n";
}

echo "</table>\n";
?>
