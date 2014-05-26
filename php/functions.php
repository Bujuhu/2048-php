<?php
	$p=isset($_GET["p"]) ? $_GET["p"] : "Spielen";
	$title = "2048";

	if($p!="Spielen"){
		$title.=" - ".strtoupper($p);
	}
			
	function getPage($pagename){
		$path = "pages/$pagename.php";
		if(file_exists($path)){
			return openPage($path);
		}
		else{
			return openPage("pages/Spielen.php");
		}
	}
	function printMenuItems($p) {
		$menu = array();
		$menu[0] = "Play";
		$menu[1] = "High-Score";

		foreach ($menu as $name) {
			if($p == $name) {
				echo "<li class='active'>";
			}
			else {
				echo "<li>";
			}
			echo "<p><a href='index.php?p=$name'>$name</a></p></li>";
		}
	}
	function openPage($path){
		$fh = fopen($path, "r");
		$fc = fread($fh, filesize($path));
		fclose($fh);
		return $fc;
	}
?>