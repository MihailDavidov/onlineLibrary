<?php
try {
	require_once("init.php");
	
	if (!empty($_GET['logout'])) {
		session_destroy();
		session_start();
	}
	
	$bookController = new BookController();
 	$recomendedBooks = $bookController->getRecomendedBooks();
	$mostRatedBooks = $bookController->getMostRatedBooks();

} catch (Exception $e) {

}

require_once ("headers/head.php");
require_once ("headers/indexHeader.php");
?>
		
		<div id="content">
			<div id="lineImage">
				<img src="images/lineImage.png">
			</div>
			<div id="leftContent">
				<h3 id="whatToRead">Deciding what to read next?</h3>
					<p id="textWhatToRead">
						You're in the right place. Tell us what titles or genres you've enjoyed in the past, and we'll give you 
						surprisingly insightful recommendations.
					</p>
				<div id="firstRow" class="row">
					<span class="recommendedBooksHeader">Recomended books</span>
					<div class="bookShelveImages">
						<?php 
							foreach ($recomendedBooks as $book) {
								echo "<a href='preview.php?ISBN=" . $book['book_ISBN'] . "'><img class='recommendedBooksImages' src = 'images/books/" . $book['image'] . "'></a>";
							}
						?>
					</div>
				</div>
				<div id="secondRow" class="row">
					<span class="recommendedBooksHeader">Books with higher rating</span>
					<div class="bookShelveImages">
						<?php 
							foreach ($mostRatedBooks as $book) {
								echo "<a href='preview.php?ISBN=" . $book['book_ISBN'] . "'><img class='recommendedBooksImages' src = 'images/books/" . $book['image'] . "'></a>";
							}
						?>
					</div>
				</div>
			</div>
			<div id="searchBooksBox">
				<div class="siteSearch">
					<form accept-charset="UTF-8" action="search.php" method="get">
						<div>
							<span id="searchText">Search and browse books </span>
							<div class="searchField">
								<input id="searchBox" class="searchF" type="search" name="search" placeholder="Title/ Author/ ISBN">
								<button type="submit"><img src="images/magnifyingGlass.png"></button>
							</div>
						</div>
					</form>
				</div>
				<?php 
					require_once ("genresButtons.php");
				?>
		      <div id="tree"><img src="images/tree.png"></div>
			</div>
			<div style="clear:both;"></div>
		</div>
<?php 
	require_once ("footers/footer.php");
?>