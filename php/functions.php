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
		$path = "pages/$p.php";
		if(file_exists($path)) {
			include($path);
		}
		else {
			getPage("Play");
		}
	}

	function db_close($db) {
		$db=nuLL;
	}
	function printMenuItems($p) {
		$menu = array();
		$menu[0] = "Play";
		$menu[1] = "High-Scores";

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
?>