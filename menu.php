<?php 
	$p=isset($_GET["p"]) ? $_GET["p"] : "home";
	$menu="
	<div id='cssmenu'>
		<ul>
		<li class='";
		if($p=="home")
			$menu.="active";
		$menu.="'><a href='?p=home'>Home</a>
		</li>
		<li class='";
		if($p=="day"||$p=="week"||$p=="month")
			$menu.="active ";
		$menu.="has-sub'><a>Scoreboard</a>
			<ul>
				<li><a href='?p=day'>Day</a></li>
				<li><a href='?p=week'>Week</a></li>
				<li><a href='?p=month'>Month</a></li>
			</ul>
		</li>
		<li class='";
		if($p=="about")
			$menu.="active";
		$menu.="'><a href='?p=about'>About</a>
		</li>
		<ul>
	</div>";
	echo $menu;
?>