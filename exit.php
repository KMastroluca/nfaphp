<?php
	include "config.php";

	if(strlen($_SESSION['key'])>0){
		$dbm->deauth($_SESSION['key']);
		$_SESSION['key']="";
	}
	$_SESSION['key']="";
	header("Location: index.php");
?>
