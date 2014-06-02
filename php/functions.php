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
		$res = $db->query("SELECT * FROM score ORDER BY date DESC");
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
		$highScores[0] = 'today';
		$highScores[0] = 'this week';
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
			echo "<p><a href='index.php?p=$mI'>$mI</a></p></li>";
			if($menu[1] == $mI) {
				echo "<ul>";
				foreach ($highScores as $sM) {
					echo "<li><p><a href='index.php?p=$mI&t=$sM'>$sM</a></p></li>";
				}
				echo "</ul>";
			}

		}
	}
?>