<?php 
try {
	$genreController = new GenreController();
	$genres = $genreController->getGenres();
	
	for ($i = 0 ;$i < 4; $i++) {
		echo "<div class='left genres'>";
		for ($j=0; $j<10;$j++) {
			echo " <a class='actionLinkLite' href='genres.php?name={$genres[$i*10 +$j]['name']}'>{$genres[$i*10 +$j]['name']}</a><br>";
		}
		echo "</div>";
	}
}
catch (Exception $e) {
	
}
?>