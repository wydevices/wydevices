#!/wymedia/usr/bin/php-cgi

<?php
echo "<h2> Recordings </h2>";
    if(!$xml_records=simplexml_load_file('/var/www/myrecords.fxd')){
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
