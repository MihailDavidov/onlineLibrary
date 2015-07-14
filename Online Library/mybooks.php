<?php
try {
	require_once("init.php");
	
	if (empty($_SESSION['user'])) {
		echo "<script>";
		echo 'window.location = "index.php"';
		echo "</script>";
	}
	
	$userController = new UserController();
	
	$allBooksCount = $userController->getUserAllBooksCount($_SESSION['user']['user_id']);
	$readBooksCount = $userController->getReadBooksCount($_SESSION['user']['user_id']);
	$toReadBooksCount = $userController->getToReadBooksCount($_SESSION['user']['user_id']);
	$readingBooksCount = $userController->getReadingBooksCount($_SESSION['user']['user_id']);
	
	$shelves = array("toread", "reading", "read" , "all");
	$status = array("Want To Read", "Currently Reading", "Read");
	
	if (empty($_GET['shelve']) || (!in_array($_GET['shelve'], $shelves))) {
		$shelve = "all";
	} else {
		$shelve = $_GET['shelve'];
	}
	
	$bookList = $userController->getShelveBooks($shelve, $_SESSION['user']['user_id']);
	
} catch (Exception $e) {

}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
	<script>
		function removeBook(image) {
			if (!confirm("Are you sure you want to remove this book?")) {
				return;
			}
				var isbn = $(image).attr('id');
				var posting = $.post("ajax.php", {"action": "removeBookFromShelve", "ISBN": isbn} );
				 
				posting.done(function(data) {
					$(image).closest("tr").hide();
				});
			}
	</script>
		<div id="content" class="fullWidth">
			<div class="topContent"><span>My Books</span></div>
			<div id="containersWrapper">
				<div id="myBooksMenu" class="container">
					<ul>
						<li id="bookshelvesText">my booksshelves</li>
						<li><a href="mybooks.php?shelve=all">All (<?php echo $allBooksCount;?>)</a></li>
						<li><a href="mybooks.php?shelve=read">Read (<?php echo $readBooksCount;?>)</a></li>
						<li><a href="mybooks.php?shelve=reading">Currently Reading (<?php echo $readingBooksCount;?>)</a></li>
						<li><a href="mybooks.php?shelve=toread">Want To Read (<?php echo $toReadBooksCount;?>)</a></li>
					</ul>
				</div>
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
									<?php if (empty($book['rating'])) {
												$book['rating'] = 0;
											}?>
								  	<form action="" id="<?php echo $book['book_ISBN'];?>">
									    <input class="star star-5" id="star-5" type="radio" name="star" <?php echo  ($book['rating'] == 5) ? 'checked' : '';?>/>
									    <label class="star star-5" for="star-5"></label>
									    <input class="star star-4" id="star-4" type="radio" name="star" <?php echo  ($book['rating'] == 4) ? 'checked' : '';?>/>
									    <label class="star star-4" for="star-4"></label>
									    <input class="star star-3" id="star-3" type="radio" name="star" <?php echo  ($book['rating'] == 3) ? 'checked' : '';?>/>
									    <label class="star star-3" for="star-3"></label>
									    <input class="star star-2" id="star-2" type="radio" name="star" <?php echo  ($book['rating'] == 2) ? 'checked' : '';?>/>
									    <label class="star star-2" for="star-2"></label>
									    <input class="star star-1" id="star-1" type="radio" name="star" <?php echo  ($book['rating'] == 1) ? 'checked' : '';?>/>
									    <label class="star star-1" for="star-1"></label>
								 	</form>
								</div>
								<?php if ($book['rating'] > 0) {
									echo "<script>$('form#".$book['book_ISBN']." .star').prop('disabled', true);</script>";
								} ?>			
								</td>
								<td>
									<div class="statusButton"> 
								 		<div class="userBookStatus"> 
								 			<button> 
								 				<span class="userBookStatus toread progressTriger"><?php echo $status[$book['status']];?></span> 
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
								<td><img id="<?php echo $book['book_ISBN'];?>" src="images/x.png" onclick="removeBook(this)"></td>
							</tr>
						<?php 
							}
						?>
						</tbody>
					</table>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>

<?php 
	require_once ("footers/footer.php");
?>