<?php
  include("func.upload.php");

  $target_path = "/wymedia/tmp/";
  $unpack_path = "/wymedia/usr/share/updates/";
  $response = array();
  unset($error);

  if ($_POST) {
    list($file,$error) = upload('manual_package',$target_path,'gz,bz2,tar',false);
    if($error) {
      $response['status'] = 'error';
      $response['message'] = $error;
      echo json_encode($response);
      exit;
    }

    $pkg_name = str_replace($target_path,"",$file);
    $pkg_name = str_replace(".tar.gz","",$pkg_name);
    $pkg_path = $unpack_path.$pkg_name."/";

    if ($pkg_path != $unpack_path) system("rm -Rf ".$pkg_path);
    system("tar xzf ".$file." -C ".$unpack_path);

    $pkg_author = exec("cat ".$pkg_path."AUTHOR");
    $pkg_version = exec("cat ".$pkg_path."VERSION");
    $pkg_description = nl2br(exec("cat ".$pkg_path."DESCRIPTION"));

    //Launch install.sh and log to /wymedia/usr/share/updates/my_package_install.log
    system("sh ".$pkg_path."install.sh >".$unpack_path.$pkg_name."_install.log");

    $response['status'] = 'success';
    $response['package_filename'] = $pkg_name;
    $response['package_author'] = $pkg_author;
    $response['package_version'] = $pkg_version;
    $response['package_install_log'] = $unpack_path.$pkg_name."_install.log";
    $response['package_description'] = $pkg_description;
    echo json_encode($response);
  }
?>
