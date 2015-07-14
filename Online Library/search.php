<?php
try {
	require_once("init.php");

	$bookControler = new BookController();
	$bookList = $bookControler->searchBook($_GET['search']);
	$shelves = array("toread", "reading", "read" , "all");
	$status = array("Want To Read", "Currently Reading", "Read");
	
	
} catch (Exception $e) {
	var_dump($e->getMessage());
	die();
}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
		<div id="content" class="fullWidth">
			<div id="myBooksTable" class="container">
				<table class="myTable">
					<thead>
						<tr>
							<th id="cover">cover</th>
							<th id="title">title</th>
							<th id="author">author</th>
							<th id="avgRaiting">avg raiting</th>
							<th id="raiting" class="stars">raiting</th>
							<th id="status">status</th>
							<th id="deleteButton"></th>
						</tr>
					</thead>
					<tbody>
					<?php 
						foreach ($bookList as $book) {
					?>
						<tr>
							<?php 
								if (empty($book['image'])) {
									$book['image'] = "bookIcon.png";
								}
							?>
							<td><a href="preview.php?ISBN=<?php echo $book['book_ISBN'];?>"><img src="images/books/<?php echo $book['image']?>" width="50" height="75"></a></td>
							<td><a href="preview.php?ISBN=<?php echo $book['book_ISBN'];?>"><span class="textColors"><?php echo $book['title']?></span></a></td>
							<?php 
								$authorsString = array();
								if (!empty($book['authors'])) {
									foreach ($book['authors'] as $author) {
										$authorsString[] = $author['name'];
									}
									
									$authorsString = implode(", ", $authorsString);
								} else {
									$authorsString = "Anonymous author";
								}
							?>
							<td><span class="textColors"><?php echo $authorsString?></span></td>
							<?php 
								if (!empty($book['raiting_votes'])) {
									$raiting = round(($book['raiting_points'] / $book['raiting_votes']), 2);
								} else {
									$raiting = 0;
								}
							?>
							<td><span class="raitingPoints"><?php echo $raiting?></span></td>
							<td>
								<div class="stars starsInMyBooks">
							  	<form action="">
								    <input class="star star-5" id="star-5" type="radio" name="star"/>
								    <label class="star star-5" for="star-5"></label>
								    <input class="star star-4" id="star-4" type="radio" name="star"/>
								    <label class="star star-4" for="star-4"></label>
								    <input class="star star-3" id="star-3" type="radio" name="star"/>
								    <label class="star star-3" for="star-3"></label>
								    <input class="star star-2" id="star-2" type="radio" name="star"/>
								    <label class="star star-2" for="star-2"></label>
								    <input class="star star-1" id="star-1" type="radio" name="star"/>
								    <label class="star star-1" for="star-1"></label>
							 	</form>
							</div>			
							</td>
							<td>
								<div class="statusButton"> 
							 		<div class="userBookStatus"> 
							 		<?php 
							 		$isMarked = true;
							 		if (!isset($book['status']) || $book['status'] == null) {
							 			$book['status'] = 0;
							 			$isMarked = false;
							 		}
							 		?>
							 			<button> 
							 				<span class="userBookStatus toread progressTriger<?php if ($isMarked) {echo " marked";}?>"><?php echo $status[$book['status']];?></span> 
							 				<span class="progressIndicator">saving ...</span> 
							 			</button> 
							 		</div> 
							 		<div class="userBookOptions"> 
										<button class="optionsButton"><img src="images/booksButton.png" width="18" height="15"></button>								 			<div class="buttonOptions"> 
							 				<ul> 
											  	<li onclick="changeStatus(this,'toread', '<?php echo $book['book_ISBN'];?>')">Want To Read</li>
												<li onclick="changeStatus(this,'reading', '<?php echo $book['book_ISBN'];?>')">Reading</li>
												<li onclick="changeStatus(this,'read', '<?php echo $book['book_ISBN'];?>')">Read</li>
							 				</ul> 
							 			</div> 
							 		</div> 
							 	</div> 
							</td>
						</tr>
					<?php 
						}
					?>
					</tbody>
				</table>
			</div>
		</div>

<?php 
	require_once ("footers/footer.php");
?>