<?php
try {
	require_once("init.php");
	
	$genreController = new GenreController();
	
	$genre = $genreController->getGenreByName($_GET['name']);

	
	$bookController = new BookController();
	$books = $bookController->getBooksByGenre($genre['name']);
} catch (Exception $e) {
}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
		<div id="content">
<!-- 			<div id="popupWindow"> -->
<!-- 				<div class="bookDescription"> -->
<!-- 					<div class="bookTitle" class="fullWidth"> -->
<!-- 						<span class="titleOfTheBook"></span> -->
<!-- 					</div> -->
<!-- 					<div class="bookAuthors" class="fullWidth"> -->
<!-- 						<span class="byClass authorsName">by</span> -->
<!-- 						<a href="#" class="authorsName"></a> -->
<!-- 					</div> -->
<!-- 					<div class="description" class="fullWidth"></div> -->
<!-- 				</div> -->
<!-- 			</div> -->
			<div id="lContent" class="leftContainer container">
				<div id="similarBooksText">
					<div class="textLeftContainer textContent">
						<?php 
							echo $genre['name'];
						?>
					</div>
					<div class="genreDescription">
						<?php 
							echo $genre['description'];
						?>
					</div>
					<div class="textLeftContainer textContent">New Releases in genre "<?php echo $genre['name'];?>"</div>
				</div>
				<div class="bigBoxForBooks">
					<?php 	
						for ($i = 0; $i < 2; $i++) {
							echo "<div class='booksRow'>";
							for ($j = 0; $j < 5; $j++) {
								if (empty($books[$i * 5 + $j])) {
									break;
								}
							
								if (empty($books[$i * 5 + $j]['image'])) {
									$books[$i * 5 + $j]['image'] = "bookIcon.png";
								}
								echo "<div class='books firstBook'>";
								echo "<a href='preview.php?ISBN=" . $books[$i * 5 + $j]['book_ISBN'] . "'><img src='images/books/" . $books[$i * 5 + $j]['image'] . "' height='160' width='100'></a>";
								echo "</div>";
							}
							echo "</div>";
						}
					?>
				</div>
			</div>
			<div id="rContent" class="rightContainer container">
				<?php 
					require_once ("genresButtons.php");
				?>
			</div>
		</div>

<?php 
	require_once ("footers/footer.php");
?>