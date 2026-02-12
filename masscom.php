<?php
	include "config.php";
	
	if($_POST['op']=="addmasscom"){
		$command = $_POST['idcom'];
		$data = $_POST['json'];
		$repeat = $_POST['repeat'];
		$repeat_time = $_POST['repeattime'];
		$pamams = array();
		$params['country'] = $_POST['country'];
		add_mass_command($command,$data,$repeat,$repeat_time,json_encode($params));
		header("Location: masscom.php");
		die();
	}
	
	if($_POST['op']=="editmasscom"){
		$id = $_POST['id'];
		$command = $_POST['idcom'];
		$data = $_POST['json'];
		$repeat = $_POST['repeat'];
		$repeat_time = $_POST['repeattime'];
		$pamams = array();
		$params['country'] = $_POST['country'];
		$params = json_encode($params);
		$sql = "update mass_commands set idcom='$command', value='$data', repeat_need='$repeat', repeat_time='$repeat_time', params='$params' where id='$id';";
		//echo $sql;
		//die();
		$dbm->query($sql);
		//add_mass_command($command,$data,$repeat,$repeat_time,json_encode($params));
		header("Location: masscom.php");
		die();
	}
	
	$needredir = true;
	if($_GET['op']!=""){
		switch($_GET['op']){
			case "delete":
				$id = $_GET['id'];
				if($id>0){
					$sql = "delete from mass_commands where id='".$id."'";
					$dbm->query($sql);
				}
				break;
			case "editform":
				$needredir = false;
				break;
		}
		if($needredir){
			header("Location: masscom.php");
			die();
		}
	}
	
	print_header();
	print_menu();
	
	$html = "";

	$op = "addmasscom";
	$edit = false;
	$idcom = "";
	$json = "";
	$repean = "";
	$repeattime = "";
	$country = "";
	$button = "Add!";
	$id = 0;
	if($_GET['op']=="editform"){
		$edit = true;
		$op = "editmasscom";
		
		$sql = "select * from mass_commands where userid='".$kernel['userid']."' and id=".$_GET['id']."";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$id = $row[0];
				$idcom = $row[1];
				$json = $row[2];
				$repeat = $row[4];
				$repeattime = $row[5];
				$country = $row[7];
				$button = "Save!";
			}
		}
		//$json = str_replace("\"","\\\"",$json);
		//$json = str_replace("/","\\/",$json);
		var_dump($json);
	}else{
		
	}
	
	$html .= "
		<form action=\"/masscom.php\" method=post>
			<input type=hidden name=id value=$id>
			<input type=hidden name=op value=$op>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter id command\" name=\"idcom\" value=\"".$idcom."\">
			</div>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter json value\" name=\"json\" value='".$json."'>
			</div>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Need repeat (1,0)\" name=\"repeat\" value=\"$repeat\">
			</div>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Repeat time (min) example : 360 \" name=\"repeattime\" value=\"$repeattime\">
			</div>
			<hr>
			<div class=\"form-group\">
			  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter country, for example : ru,us,pl..... \" name=\"country\" value='$country'>
			</div>
			<button type=\"submit\" class=\"btn btn-primary\">$button</button>
		</form>
	
	";
	
	$html .= "<hr>";
	
	$html .= "	<table class=\"table table-sm\">
    <thead class=\"thead-inverse\">
		<tr>
			<th>Id</th>
			<th>Com</th>
			<th>Value</th>
			<th>Date create</th>
			<th>Need repeat</th>
			<th>Time repeat</th>
			<th>Count execute</th>
			<th>Count execute unic</th>
			<th>params</th>
			<th>Operation</th>
		</tr>
	</thead><tbody>";
	
	$sql = "select * from mass_commands where userid='".$kernel['userid']."'";
	$res = $dbm->query($sql);
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			$html .= "<tr>";
				$html .= "<th>".$row[0]."</th>";
				$html .= "<th>".$commands[$row[1]]."</th>";
				$html .= "<th>$row[2]</th>";
				$html .= "<th>".new_time($row[3])."</th>";
				if($row[4]==0){
					$html .= "<th>no</th>";
					$html .= "<th>none</th>";
				}else{
					$html .= "<th>yes</th>";
					$html .= "<th>$row[5]m</th>";
				}
				$sqlce = "select * from commands where ";
				$html .= "<th>none</th>";
				$html .= "<th>none</th>";
				
				$html .= "<th>";
				$params = json_decode($row[7]);
				foreach($params as $key=>$p){
					$html .= "<strong>". $key . "</strong> : ".$p."<br>";
				}
				$html .= "</th>";
				
				$html .= "<th>
							<a href=masscom.php?op=editform&id=$row[0]><i class=\"fa fa-edit\"></i></a>
							<a href=masscom.php?op=delete&id=$row[0]><i class=\"fa fa-trash\"></i></a>
						</th>";
			$html .= "</tr>";
		}
	}
	
	$html .= "</tbody></table>";
	
	print_content("Mass command list",$html);
	print_footer();
?>
