<?php
error_reporting(0);
	function print_header(){
		global $kernel;
		include "templates/header.php";
	}
	
	function print_menu(){
		global $kernel;
		include "templates/menu.php";
	}
	
	function print_content($header,$html,$sheader = ""){
		global $kernel;
		$data = file_get_contents("templates/content.php");
		$data = str_replace("[header]",$header,$data);
		if($sheader!=""){
				$data = str_replace("[header2]","<li class=\"breadcrumb-item active\">$sheader</li>",$data);
		}else{
			$data = str_replace("[header2]","",$data);
		}
		$data = str_replace("[content]",$html,$data);
		$timec = filemtime("./tmp/".$kernel['userhash'].".apk");
		$message = "";
		if($_SESSION['message']!=""){
			$message = "
						<div class=\"alert alert-success alert-dismissable\">
			  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
			  <strong>Info!</strong> ".$_SESSION['message']."
			</div>
			";
			$_SESSION['message'] = "";
		}
		$data = str_replace("[message]",$message,$data);
		echo $data;
	}
	
	function print_footer(){
		global $kernel;
		global $dbm;
		$data = file_get_contents("templates/footer.php");
		
		$qq = "
		<strong>System info</strong> : crypt update [cryptupdate] , bot version : <strong><font color=green>[botver]</font></strong> , <small>sql querys : <strong>".$dbm->count_query."</strong></small><br>
		";
		
		$timec = filemtime("./tmp/".$kernel['userhash'].".apk");
		$qq = str_replace("[cryptupdate]","<strong><font color=red>".new_time($timec)."</font></strong> - status ok ",$qq);
		$qq = str_replace("[botver]",$kernel['botver'],$qq);
		
		$data = str_replace("[footer]",$qq,$data);
		//echo $data."<!--Support jabber : <font color=black>---</font> 2016-2018 year(c)--></center>";
		//include "templates/footer.php";
		echo $data;
	}

?>
