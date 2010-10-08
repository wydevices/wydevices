<?php
/*
 * 
 */

// Path of the myrecords.fxd file
$myrecord_path = "/wymedia/timeshift/";
//$myrecord_path = "/var/www/test/record/"; // Test

// Load myrecords.fxd file, this files contain scheduled record
if (!file_exists($myrecord_path."myrecords.fxd")) {
	echo "XML record file doesn't exist (".$myrecord_path."myrecords.fxd)\n";
	exit;
} else {
	if($xml_records=simplexml_load_file($myrecord_path."myrecords.fxd")) {
		$nb_rules = count($xml_records->periodicity->rule);
		$nb_record = count($xml_records->recordings->recording);
		$last_saved = $xml_records->general['save_time'];

		for ($p = 0; $p < $nb_rules; $p++) { // Get periodic name
			$periodicity = $xml_records->periodicity->rule[$p];
			//$record_period_id[$p] 	= $periodicity->recording_ref['periodicity_rule_id']; // Get perdiocity ID
			$record_periodicity[$p] = $periodicity->extern_name; // Get perdiocity name (daily, weekly, etc ...)
			//$record_period_name[$p] = $periodicity->recording_ref['name']; // Get name of the periodic record
		}
	} else {
		trigger_error('Error reading XML file', E_USER_ERROR);
		exit;
	}

	echo "There are ".$nb_record." records.<br />\n";
    echo "There are ".$nb_rules." periodicity rules.<br />\n";
    echo "Last modify on ".date("Y-m-d H:i:s", intval($last_saved)).".<br /><br />\n";
	echo "<table>\n";
	echo "<tr>\n
			\t<td><b>Date</b></td>\n
			\t<td><b>Channel</b></td>\n
			\t<td><b>Record name</b></td>\n
			\t<td><b>Duration</b></td>\n
			\t<td><b>Size</b></td>\n
			\t<td><b>Status</b></td>\n
            \t<td><b>Periodicity</b></td>\n
		</tr>\n";

	// Loop into myrecords.fxd for each record and get informations
	foreach ($xml_records->recordings->recording as $recording) {
        // Variable initialization
        $record_file_info = "";
        $record_size = 0;
        $record_size_mb = "";
        $record_periodic = 0;

        // Get value from XML and convert it if necessary
		$record_periodic_id = $recording['periodicity_rule_id'];
		$record_name = $recording['name'];
		$record_start_time = date("Y-m-d H:i:s", intval($recording['start_time'] - $recording['start_padding']));
		$record_duration = date("H:i:s", intval($recording['stop_time'] - $recording['stop_padding'] - $recording['start_time'] - $recording['start_padding'] - 3600));
		$record_channel = $recording->service['name'];
		$record_file_info = $recording->file_info['file'];
		$record_status = $recording['status'];
		/* status :
		 * 1 = scheduled
         * 2 = in progress
		 * 3 = exceeded / expired
		 * 4 = recorded
		 */

		//Uncomment for force value to test
		//$record_file_info = $myrecord_path."records/REC_20100322_204057_1_36258114307590_TMC_10/record.xml";

        //Get the record size into the record.xml
		if (file_exists($record_file_info)) {
			if($xml_record=simplexml_load_file($record_file_info)) {
                $record_size = $xml_record->record[0]->information->size;
                $record_size_kb = substr($record_size, 0, strlen($record_size) - 3); // Get only KB size
				$record_size_mb = round($record_size_kb / 1048576,1); // Record size in MB
				$record_size_mb .= " GB";
			}
		}

        switch($record_status){
            case 0 : $record_status = "0"; break;
            case 1 : $record_status = "scheduled"; break;
            case 2 : $record_status = "in progess"; break;
            case 3 : $record_status = "expired"; break;
            case 4 : $record_status = "recorded"; break;
        }

		echo "<tr>\n
				\t<td>".$record_start_time."</td>\n
				\t<td>".$record_channel."</td>\n
				\t<td>".$record_name."</td>\n
				\t<td>".$record_duration."</td>\n
				\t<td>".$record_size_mb."</td>\n
				\t<td>".$record_status."</td>\n";
		if ($record_periodic_id > 0) {
			echo "\t<td>".$record_periodicity[$record_periodic_id - 1]."</td>\n";
		} else {
			echo "\t<td></td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
