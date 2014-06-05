<?php
	$p=isset($_GET["p"]) ? $_GET["p"] : "Play";
	$title = "2048";

	function db_connect() 
{
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
 			default:
 				$query = "";
 				break;
 		}
 		$res = $db->query("SELECT * FROM score $query ORDER BY score DESC");
		foreach ($res as $r) {
			echo "<div class='scoreRow'><p class='name'>".$r['username'].": ".$r['score']."</p><p class='date'>".$r['date']."</p></div>";
		}
	}

	function getPage($p) {
		//echo $p;
		$path = "pages/$p.php";
		if(isset($_POST["OK"]))
			getUser($path);
		else if(isset($_POST["signup"]))
			creatUser($path);
		else if(user()){
			if(file_exists($path)) {
				include($path);
			}
			else {
				getPage("Play");
			}
		}
		else{
			if($p=="Logout")
				include("pages/Logout.php");
			else
				include("pages/login.php");
		}
	}

	function db_close($db) {
		$db=nuLL;
	}
	function printMenuItems($p) {
		session_start();
		$menu = array();
		$menu[0] = "Play";
		$menu[1] = "High-Scores";
		$menu[2] = "About";
		$menu[3] = "Logout";

		$highScores = array();
		$highScores[0] = 'last 7 Days';
		$highScores[1] = 'this Month';
		$highScores[2] = 'this year';
		$highScores[3] = 'all Time';

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
	
	function encrypt($pass){
		if(strlen($pass)>7)
			return hash("SHA512",md5($pass));
		else
			return null;
	}
	function user(){
		//$_SESSION["user"]="xdke09(ijfjeäü";
		//$_SESSION["user"]="";
		if(isset($_SESSION["user"])&&$_SESSION["user"]=="xdke09(ijfjeäü")
			return true;
		else
			return false;
	}
	
	function getUser($path){
		$_POST["OK"]="";
		//echo "getUser<br>";
		//echo "<br>hallo:".$_POST["username"];
		$a=$_POST["username"];
		$db=new PDO("mysql:host=localhost;dbname=twothousandfourtyeight","root","");
		//$res=$db->query("SELECT user.* FROM user WHERE (name)=\"".$a."\";");
		$sql="SELECT * FROM user WHERE (name)=?";
		//$state->bindParam(':name', $_POST['username']);
		$state=$db->prepare($sql/*, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)*/);
		$state->execute(array($a));
		$res=$state->fetchAll();
		//print_r($res);
		//$res=$state->getResult();
		
		//$res=$db->query("SELECT * FROM user ;");
		foreach($res as $row){
			/*echo "<br><br>";
			echo $row["name"];
			echo " : ";
			echo $_POST["username"];
			echo "<br>";
			echo $row["password"];
			echo " : ";
			echo $_POST["pass"];
			echo "<br>";
			echo "<br>";
			echo "<br>";*/
			if($row["password"]==encrypt($_POST["pass"])){
				$_SESSION["user"]="xdke09(ijfjeäü";
				//echo $_SESSION["user"];
				//$p=isset($_GET["p"]) ? $_GET["p"] : "Play";
				//$path = "pages/$p.php";
				if(file_exists($path)) 
					include($path);
				else
					getPage("Play");
				break;
			}
			else{
				$_SESSION["user"]="";
			}
		}
		
	}
	
	/*function getUser($path){
		echo "getUser<br>";
		$db=new PDO("mysql:host=localhost;dbname=twothousandfourtyeight","root","");
		$res=$db->query("SELECT * FROM user;");
		foreach($res as $row){
			echo $row["name"];
			echo " : ";
			echo $_POST["username"];
			echo "<br>";
			echo $row["password"];
			echo " : ";
			echo $_POST["pass"];
			echo "<br>";
			echo "<br>";
			echo "<br>";
			if($row["name"]==$_POST["username"]&&$row["password"]==$_POST["pass"]){
				$_SESSION["user"]="xdke09(ijfjeäü";
				//echo $_SESSION["user"];
				//$p=isset($_GET["p"]) ? $_GET["p"] : "Play";
				//$path = "pages/$p.php";
				if(file_exists($path)) 
					include($path);
				else
					getPage("Play");
				break;
			}
			else{
				$_SESSION["user"]="";
			}
		}
		$_POST["OK"]="";
	}*/
	
	function creatUser($path){
		//echo "creatUser<br>";
		if(isset($_POST["name"])&&isset($_POST["passnew"]))
		if(strlen($_POST['name'])>3&&strlen($_POST['passnew'])>7&&$_POST['passnew']==$_POST['passnd']){
			$db=new PDO("mysql:host=localhost;dbname=twothousandfourtyeight","root","");
			
			$a=$_POST["name"];
			$sql="SELECT * FROM user WHERE (name)=?";
			$state=$db->prepare($sql);
			$state->execute(array($a));
			$res=$state->fetchAll();
			if(size($res)==0){
		
				$ins=$db->prepare("INSERT INTO user(name,password) VALUES(:name,:password)");
				$ins->execute(array(':name'=>$_POST['name'],':password'=>encrypt($_POST['passnew'])));
				$_SESSION["user"]="xdke09(ijfjeäü";
				if(file_exists($path)) 
						include($path);
					else
						getPage("Play");
					break;
			}
			else{
				echo "This USERNAME already exists";
			}
		}
		$_POST["signup"]="";
	}
?>