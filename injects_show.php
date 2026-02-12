<?php
	include "config.php";

	$id = $_GET['id'];
	//var_dump(is_numeric($id));
	if(is_numeric($id)){
		//echo "sadfsd";
		$name = get_name_inject_from_id($id);
		//var_dump($name);
		echo get_inject_html($name);
	}else{
		die();
	}

?>
