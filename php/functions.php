<?php
	session_start();

	$p=isset($_GET["p"]) ? $_GET["p"] : "Play";

	if($p == "Logout") {
		logout();
		$p = "Login";
	}
	if(isset($_GET["score"])) {
		submitScore($_GET["score"]);
	} if(isset($_POST['signin'])) {
		signIn();
	} if (isset($_POST['signup'])) {
		signUp();
	}
	$title = "2048";
	function db_connect(){
	$user = 'root';
	$pass = '';
	$host = 'localhost';
	$db_name = 'twothousandfourtyeight';
	
	$db = new PDO("mysql:host=$host;dbname=$db_name", $user,$pass);
	return $db;
}
	function printscores($t) {
		$db = db_connect();
		switch ($t) {
			case '7 Days':
 				$query = "WHERE date > DATE_SUB(NOW(), INTERVAL 1 WEEK)"; 
 				break;
 			case 'this Month':
 				$query = "WHERE (date between DATE_FORMAT(NOW(), '%Y-%m-01') AND NOW())";
 				break;
 			case 'this year':
 				$query = "WHERE (date between DATE_FORMAT(NOW(), '%Y-01-01') AND NOW())";
 				break;
 			case 'your scores':
 				$query = 'WHERE username = "'.$_SESSION["user"].'"';
 				break;
 			default:
 				$query = "";
 				break;
 		}
 		$res = $db->query("SELECT * FROM score $query ORDER BY score DESC");
		foreach ($res as $r) {
			echo "<div class='scoreRow'><p class='name'>".$r['username'].": ".$r['score']."</p><p class='date'>".$r['date']."</p></div>";
		}
	}
	function getScoreSubtitle($t) {
		foreach (getHighScoresArray() as $str) {
			if($t == $str)
				return $t;
		}
		return "all Time";
	}

	function getPage($p) {
		$path = "pages/$p.php";
		if(signedIn() || $p == "Login"){
			if(file_exists($path)) {
				printNotification();
				include($path);
			} else {
				getPage("Play");
			}
		} else { 
			getPage("Login");
		}
	}

	function db_close($db) {
		$db=nuLL;
	}
	function printMenuItems($p) {
		$menu = array();
		$menu[0] = "Play";
		$menu[1] = "High-Scores";
		$menu[2] = "About";
		if(signedIn()) {
			$menu[3] = "Logout";
		}
		else{
			$menu[3] = "Login";
		}

		$highScores = getHighScoresArray();
		$highScores[0] = 'last 7 Days';
		$highScores[1] = 'this Month';
		$highScores[2] = 'this year';
		$highScores[3] = 'all Time';
		$highScores[4] = 'your scores';

		foreach ($menu as $mI) {
			if($p == $mI) {
				echo "<li class='active'>";
			}
			else {
				echo "<li>";
			}
			echo "<a href='index.php?p=$mI'>$mI</a>";
			if($menu[1] == $mI) {
				echo "<ul class='dropdown'>";
				foreach ($highScores as $sM) {
					echo "<li><a href='index.php?p=$mI&t=$sM'>$sM</a></li>";
				}
				echo "</ul>";
			}
			echo "</li>";
		}
	}
	function getHighScoresArray() {
		$highScores = array();
		$highScores[0] = 'last 7 Days';
		$highScores[1] = 'this Month';
		$highScores[2] = 'this year';
		$highScores[3] = 'all Time';
		$highScores[4] = 'your scores';
		return $highScores;
	}
	
	function encrypt($pass){
		if(strlen($pass)>7)
			return hash("SHA512",md5($pass));
		else
			return null;
	}
	function signedIn(){
		return isset($_SESSION["user"]);
	}
	function getUserName(){

		if(signedIn())
			return $_SESSION["user"];
		else
			return false;
	}
	
	function signIn(){
		$a=$_POST["name"];
		$db= db_connect();
		$sql="SELECT * FROM user WHERE (name)=?";
		$state=$db->prepare($sql);
		$state->execute(array($a));
		$res=$state->fetchAll();

		foreach($res as $row){
			
			if($row["password"]==encrypt($_POST["pass"])){
				$_SESSION["user"]= $row["name"];
				setNotification("Login sucessfull");
				return;
			}
		}
		setNotification("Wrong Username or Password!");
	}
	
	function signUp() {
		if(strlen($_POST['name'])>=4){
			if(strlen($_POST['pass'])>=8) {
				$db= db_connect();
			
				$name=$_POST["name"];
				$state=$db->prepare("SELECT * FROM user WHERE (name)=?");
				$state->execute(array($name));
				$res=$state->fetchAll();
				if(count($res)==0){
					$sql = "INSERT INTO user(name,password) VALUES(:name,:password)";
					$ins=$db->prepare($sql);
					$ins->execute(array(':name'=>$name,':password'=>encrypt($_POST['pass'])));
					$_SESSION["user"]=$name;
				} else{
					setNotification("This Username already exists");
				}
			} else{
				setNotification("The Password must have at least 8 charcters");
			}	
		} else {
			setNotification("The Username must have at least 4 charcters");
		}
	}
	function logout() {
		unset($_SESSION['user']);
		setNotification("Logout sucessfull");
	}
	function submitScore($score) {
		$db = db_connect();
		$sql = "INSERT INTO score(score, username) VALUES(:score,:username)";
		$ins=$db->prepare($sql);
		if($ins->execute(array(':score'=>$score,':username'=>getUserName()))) {
			setNotification("Score was succefully submitted!");
		}
	}
	function setNotification($msg) {
		$_SESSION['msg'] = $msg;
	}
	function printNotification() {
		if(isset($_SESSION['msg'])){
			$msg = $_SESSION['msg'];

		unset($_SESSION['msg']);

		echo "<div class='visible' id='notification'><div class='note'><p>$msg</p></div></div>";
		}
	}
?>