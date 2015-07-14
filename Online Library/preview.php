<?php
try {
	require_once("init.php");
	
	if (empty($_GET['ISBN'])) {
		header("Location: index.php");
		exit();
	}
	$bookContoller = new BookController();
	
	$userController = new UserController();
	if ((!empty($_POST['comment'])) && (!empty($_GET['ISBN'])) && (!empty($_SESSION['user']))) {
		
		$userController->addComment($_POST['comment'], $_GET['ISBN'], $_SESSION['user']['user_id']);
			
		header('Location: preview.php?ISBN=' . $_GET['ISBN']);
		exit;
	}
	
	if (!empty($_SESSION['user'])) {
		$bookRating = $userController->getBookRating($_GET['ISBN'], $_SESSION['user']['user_id']);
		if (empty($bookRating)) {
			$bookRating = array();
			$bookRating['rating'] = 0;
		}
	}
	
	$status = array("Want To Read", "Currently Reading", "Read");
	$book = $bookContoller->getBookInfo($_GET['ISBN']);
	
	if (empty($book['book_ISBN'])) {
		header("Location: index.php");
		exit();
	}

	$comments = $bookContoller->getBookComments($_GET['ISBN']);
	
	if (empty($book['status'])) {
		$book['status'] = 0;
	}
	
	$similarBooks = $bookContoller->getSimilarBooks($book['book_ISBN']);

} catch (Exception $e) {
}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
		<div id="content">
			<div class="leftContainer container">
				<div id="bookPreview">
					<div class="imageContainer">
						<?php 
							if (empty($book['image'])) {
								$book['image'] = "bookIcon.png";
							}
						?>
						<div class="bookImage"><img src="images/books/<?php echo $book['image'];?>" height="200" width="140"></div>
						<div class="statusButton" > 
								 		<div class="userBookStatus"> 
								 			<button> 
								 				<span class="userBookStatus toread progressTriger"><?php echo $status[$book['status']];?></span> 
								 				<span class="progressIndicator">saving ...</span> 
								 			</button> 
								 		</div> 
								 		<div class="userBookOptions"> 
											<button class="optionsButton"><img src="images/booksButton.png" width="18" height="15"></button>
								 			<div class="buttonOptions"> 
								 				<ul> 
												  	<li onclick="changeStatus(this,'toread', '<?php echo $book['book_ISBN'];?>')">Want To Read</li>
													<li onclick="changeStatus(this,'reading', '<?php echo $book['book_ISBN'];?>')">Reading</li>
													<li onclick="changeStatus(this,'read', '<?php echo $book['book_ISBN'];?>')">Read</li>
								 				</ul> 
								 			</div> 
								 		</div> 
								 	</div> 
						<div class="fiveStars">
							<div class="rateThisBookText"><span>Rate this book</span></div>
							<div class="fiveStars">				
								<div class="stars">
								  	<form action="" id="<?php echo $book['book_ISBN'];?>">
									    <input class="star star-5" id="star-5" type="radio" name="star" <?php echo  ($bookRating['rating'] == 5) ? 'checked' : '';?>/>
									    <label class="star star-5" for="star-5"></label>
									    <input class="star star-4" id="star-4" type="radio" name="star" <?php echo ($bookRating['rating'] == 4) ? 'checked' : '';?>/>
									    <label class="star star-4" for="star-4"></label>
									    <input class="star star-3" id="star-3" type="radio" name="star" <?php echo ($bookRating['rating'] == 3) ? 'checked' : '';?>/>
									    <label class="star star-3" for="star-3"></label>
									    <input class="star star-2" id="star-2" type="radio" name="star" <?php echo ($bookRating['rating'] == 2) ? 'checked' : ''?>/>
									    <label class="star star-2" for="star-2"></label>
									    <input class="star star-1" id="star-1" type="radio" name="star"<?php echo ($bookRating['rating'] == 1) ? 'checked' : '';?>/>
									    <label class="star star-1" for="star-1"></label>
								 	</form>
								</div>					
							</div>
						</div>
						<?php if (!empty($_SESSION['user']) && is_file("pdfs/".$book['book_ISBN'].".pdf")) {?>
						<div class="readThisBook">
							<button class="readTheBook" onclick="window.location='reader.php?ISBN=<?php echo $book['book_ISBN'];?>'">Read this book</button>
						</div>
						<?php }?>
					</div>
					<div class="bookDescription">
						<div class="bookTitle" class="fullWidth">
							<span class="titleOfTheBook"><?php echo $book['title'];?></span>
						</div>
						<div class="bookAuthors" class="fullWidth">
							<span class="byClass authorsName">by</span>
							<a href="#" class="authorsName">
							<?php 
								$authorsString = array();
								foreach ($book['authors'] as $author) {
									$authorsString[] = $author['name'];
								}
								
								$authorsString = implode(", ", $authorsString);
								
								echo $authorsString;
							?>
								
							</a>
						</div>
						<div class="description" class="fullWidth">
							<?php echo nl2br($book['description']);?>
						</div>
					</div>
					<div style="clear: both"></div>
					<?php 
						if (!empty($_SESSION['user'])) {
					?>
					<div id="addComment">
						<form action="preview.php?ISBN=<?php echo $_GET['ISBN'];?>" method="post">
							<label id="commentText" for="comment">add comment</label><br/>
							<textarea id="commentBox" name="comment"></textarea><br/>
							<input id="commentButton" type="submit" name="commentButton" value="Post">
						</form>
					</div>
					<?php }?>
					<div id="showComments">
						<div>
							<span class="comment infoText">Comments</span>
						</div>
						<?php 
							foreach ($comments as $comment) {
						?>
						<div class="brownLine">
							<div class="left">
								<span class="infoText">by &nbsp;</span>
								<a href="myprofile.php?username=<?php echo $comment['username'];?>" class="infoText userText"><?php echo $comment['username'];?></a>
							</div>
							<div class="right">
								<span class="infoText userText"><?php echo $comment['date'];?></span>
							</div>
							<div style="clear: both"></div>
						</div>
						<div class="commentContent">
							<?php echo $comment['comment'];?>
						</div>
						<?php }?>
					</div>
					<div id="previewGenres">
					</div>
				</div>
				</div>
				<div class="rightContainer container">
					<div id="similarBooksText">
						<div class="textRightContainer textContent">Similar Books</div>
						<div class="smallBooksRow">
							<?php 
								for ($i = 0; $i < 3; $i++) {
									if (empty($similarBooks[$i])) {
										break;
									}
									echo "<div class='books firstBook'>";
									echo "<a href='preview.php?ISBN=" . $similarBooks[$i]['book_ISBN'] . "'><img src='images/books/" . 
										$similarBooks[$i]['image'] . "' height='130' width='90'></a>";
									echo "</div>";
								}
							?>
						</div>
					</div>
					<div style="clear: both"></div>
					<div id="aboutTheAuthor">
						
							<?php 
								foreach ($book['authors'] as $author) {
									if (!empty($author['biography'])) {
										echo "<div class='textRightContainer textContent'>About ";
										echo $author['name'];
										echo "</div>";
										echo "<div class='authorInfo'>";
										echo $author['biography'];
										echo "</div>";
									}
								}
								
							?>			
					</div>
				</div>
				<div style="clear:both"></div>
			</div>
			 <?php if ($bookRating['rating'] > 0) {
				echo "<script>$('form#".$_GET['ISBN']." .star').prop('disabled', true);</script>";
			} ?>
		

<?php 
	require_once ("footers/footer.php");
?>