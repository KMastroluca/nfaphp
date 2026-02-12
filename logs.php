<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	$html = " ";
	
	$html .= "
	<table class=\"table\">
    <thead>
      <tr>
        <th>Date</th>
        <th>BID</th>
        <th>Data</th>
        <th>Tech Information</th>
      </tr>
    </thead>
    <tbody>";
      
	$res = $dbm->query("select * from commands ORDER BY date_exec DESC LIMIT 50");
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			if(my_bot($row[1])){
				//$return[] = $row;
				$html.= " ";
				if((time()-$row[5])<300){
					$html.= "<tr class=\"success\">";
				}else{
					$html.= "<tr>";
				}
				$html.= "
					<th>".new_time($row[5])."</th>
					<th>".get_link_bot($row[1])."</th>
					<th>0</th>
					<th>".$row[7]."</th>
				</tr>
				";			
			}
		}
	}
	
	$html .= "</tbody></table>";

	print_content("Bot logs",$html);
	
	print_footer();

?>
