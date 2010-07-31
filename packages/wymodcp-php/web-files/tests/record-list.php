<?php
    if(!$xml_records=simplexml_load_file('/wymedia/timeshift/myrecords.fxd')){
        trigger_error('Error reading XML file',E_USER_ERROR);
    }

    echo 'Displaying contents of XML file...<br /><br />';
    foreach($xml_records as $record){
        $record_name = $record->recording['name'];

        if ($record_name != "") {
            echo '<b>Record find : </b>'.$record_name.'<br />';
        }
    }

?>
