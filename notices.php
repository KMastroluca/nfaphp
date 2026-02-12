<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	$html = " ";
	
	$html .= "
	
	[<a href=notices.php?count=50>50</a>] 
	[<a href=notices.php?count=200>200</a>] 
	[<a href=notices.php?count=500>500</a>] 
	[<a href=notices.php?count=1000>1000</a>] 
<hr>
	<table class=\"table\">
    <thead>
      <tr>
        <th>Date</th>
        <th>Text</th>
        <th>Link</th>
      </tr>
    </thead>
    <tbody>";
    
    $count_msg =  200;
    if($_GET['count']!=""){
		$count_msg = $_GET['count'];
	}
      
    $sql = "select * from notifications where userid=".$kernel['userid']." ORDER BY id DESC LIMIT $count_msg";
	$res = $dbm->query($sql);
	if($dbm->count>0){
		$ids = array();
		while($row=mysqli_fetch_array($res)) {
			$ids[] = $row[0];
			//$return[] = $row;
			$html.= " ";
			if((time()-$row[5])<300){
				$html.= "<tr class=\"success\">";
			}else{
				$html.= "<tr>";
			}
			$html.= "
				<th>".new_time($row[4])."</th>
				<th>".$row[2]."</th>
				<th><a href=\"".$row[3]."\">GO</a></th>
			</tr>
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
	
	$html .= "</tbody></table>";

	print_content("$count_msg last notifications",$html);
	
	print_footer();

?>
