<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
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
	
	$html .= "
	
	
	
	<div class=\"row\">
    <div class=\"col-lg-6\">
    <h5>Mass send sms</h5>
    
    <script>
    function change(data){
		var f = document.getElementById('smsSend');
		f.num.value=data;
	}
   </script>
    
    [<a href=\"#\" onClick=\"change(900);\">900</a>]  
    <br><br>
	 <form action=\"/ajax.php\" id=\"smsSend\">
		<div class=\"form-group\">
		  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter number\" name=\"num\">
		</div>
		<div class=\"form-group\">
		  <input type=\"text\" class=\"form-control\" id=\"pwd\" placeholder=\"Text message\" name=\"sms\">
		</div>
		<button type=\"submit\" class=\"btn btn-primary\">Send!</button> <strong><font color=red>Warning</font>, sms sended from all bots who filter in <a href=list.php>list</a></strong>
	  </form>
	  </div>
	  <div class=\"col-lg-6\"></div>
	  
	  
	  </div>
	  
	<hr> 
	
	<table class=\"table\">
    <thead>
      <tr>
        <th>Date</th>
        <th>Bot</th>
        <th>Contact</th>
        <th>Direction</th>
        <th>Text</th>
        
      </tr>
    </thead>
    <tbody>";
      
    //$dbm = new db;
	//$dbm->connect();
	$res = $dbm->query("select * from sms ORDER BY sms_time DESC");
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			if(my_bot($row[1])){
				$idc = "";
				$cid = 0;
				if($row[2]>0 && $row[3]==0){
					$idc = "<-";
					$cid = $row[2];
				}
				if($row[3]>0 && $row[2]==0){
					$idc = "->";
					$cid = $row[3];
				}
				$contact = get_contact_name_from_id($cid);
				//$return[] = $row;
				$html.= " ";
				$html.= "<tr>";
				$html.= "
					<th>".new_time(round($row[5]/1000))."</th>
					<th>".get_link_bot($row[1])."</th>
					<th>".$contact[0]." (".$contact[1].")</th>
					<th>$idc</th>
					<th>$row[4]</th>
					
				</tr>
				";	
			}		
		}
	}
      
    $html.="</tbody>
  </table>
	";
	
	print_content("Last sms logs",$html);
	
	print_footer();

?>

