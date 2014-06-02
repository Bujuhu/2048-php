	<?php
		$t=isset($_GET["t"]) ? $_GET["t"] : "all Time";
	?>

	<div class="container">
		<h1 class="scoreTitle title">High-Scores</h1>
		<p> of <?php echo $t ?></p>
   		 <div class="scoreContainer clearfix">
     		 <?php printScores($t); ?>
		</div>
	</div>