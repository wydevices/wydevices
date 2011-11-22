<?php
/**
 * A function for easily uploading files. This function will automatically generate a new file name so that files are not overwritten.
 * Modified version of script from: http://www.bin-co.com/php/scripts/upload_function/
 * Arguments: $file_id - The name of the input field contianing the file.
 *            $folder - The folder to which the file should be uploaded to - it must be writable. OPTIONAL
 *            $types - A list of comma(,) seperated extensions that can be uploaded. If it is empty, anything goes. OPTIONAL
 *            $unique - By default, will over-write existing file with same name. Set to true to generate a unique filename. OPTIONAL
 * Returns: This is somewhat complicated - this function returns an array with two values...
 *          The first element is either blank for an error or the filename to which the file was uploaded to.
 *          The second element is the status - if the upload failed, it will be 'Error : Cannot upload the file 'name.txt'.' or something like that
 */
function upload($file_id, $folder="", $types="", $unique=false) {
    $file_title = $_FILES[$file_id]['name'];
    if($file_title == '') return array('','No file specified.');

    /* check the file extension */
    $file_arr = explode(".",basename($file_title));
    $ext = strtolower($file_arr[count($file_arr)-1]);  /* get the last extension */
    $all_types = explode(",",strtolower($types));
    if($types) {
        if(in_array($ext,$all_types));
        else {
            $result = "The file '".$_FILES[$file_id]['name']."' is not a valid file type."; /* show error if any */
            return array('',$result);
        }
    }

    /* check the folder */
    if($folder) {
      /* remove the trailing slash for now to check directory */
      if(substr($folder,strlen($folder)-1,1)=='/') {
        $folder = substr($folder,0,strlen($folder)-1);
      }
      if(!is_dir($folder)) {
        if(!mkdir($folder,0777,true)) {
          $result = "Cannot create upload directory."; /* show error if any */
          return array('',$result);
        }
      }
      /* now add slash at end of directory name */
      $folder .= '/';
    }

    /* generate a unique filename if required */
    $uploadfile = $folder . $file_title;
    if($unique) {
      $i = 0;
      while(file_exists($uploadfile)) {
        $i++;
        $uploadfile = $folder . $file_arr[0] . '_' . $i . '.';
        for($x=1;$x<count($file_arr);$x++) {
          $uploadfile .= $file_arr[$x];
        }
      }
    }

    $file_name = $uploadfile;

    $result = '';
    /* move the file from the stored location to the new location */
    if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
        $result = "Cannot upload the file '".$_FILES[$file_id]['name']."'";  /* show error if any */
        if(!file_exists($folder)) {
            $result .= " : Folder does not exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } elseif(!is_writable($uploadfile)) {
            $result .= " : File not writable.";
        }
        $file_name = '';

    } else {
        if(!$_FILES[$file_id]['size']) { /* check if the file is actually uploaded */
            @unlink($uploadfile); /* delete any empty file */
            $file_name = '';
            $result = "Empty file found - please use a valid file.";  /* and show the error message */
        } else {
            chmod($uploadfile,0777); /* make the file writable */
        }
    }

    return array($file_name,$result);
}
?>
