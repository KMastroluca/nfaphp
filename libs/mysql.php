<?php

class db{
	public $link;
	public $count;
	public $count_query = 0;

	function connect(){
		global $mysql;
		$this->link = mysqli_connect($mysql['host'], $mysql['user'], $mysql['pass'],$mysql['db']);
		if (!$this->link) {
    			die('Ошибка соединения: ' . mysql_error());
		}
//print_r($mysql);;
		//mysqli_select_db($mysql['db'], $link) or die('Could not select database.');
	}

	function query($sql){
		$res = mysqli_query($this->link,$sql);
		$this->count = mysqli_num_rows($res);
		$this->count_query++;
		return $res;
	}

	function close(){
		mysqli_close($link);
	}

	function check_auth($key){
		if(strlen($key)==0)return false;
		$result = $this->query("select * from users where session='".$key."';");
		if(mysqli_num_rows($result)>0){
			return true;
		}else{
			return false;
		}
	}

	function auth($user,$pass){
		global $kernel;
		global $_SESSION;
		if(strlen($user)>0 && strlen($pass)>0){
			$result = $this->query("select * from users where login='".$user."' and password='".md5($pass)."';");
			//print_r($result);die();
			$id = 0;
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_array($result)) {
					$id = $row[0];
				}
				$key = md5(time());
				$this->query("update users set session='".$key."' where login='".$user."';");
				
				$_SESSION['key']=$key;
				$kernel['userid'] =$id;
				notification_add("You entered to panel","",1);
                        	return true;
                	}else{
                        	return false;
                	}
		}
		return false;
	}

	function deauth($key){
		$this->query("update users set session='' where session='".$key."';");
	}
}

?>
