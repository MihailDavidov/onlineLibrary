<?php
try {
	require_once("init.php");

	
	$username = "";
	if (!empty($_GET['username'])) {
		$username = $_GET['username'];
	}
	if (!empty($_SESSION['user']) && empty($username)) {
		$username = $_SESSION['user']['username'];
	}
	if (empty($username)) {
		header('Location: index.php');
		exit;
	}

	$userController = new UserController();
	$user = $userController->showUserName($username);

	$readBooksCount = $userController->getReadBooksCount($user['user_id']);
	$toReadBooksCount = $userController->getToReadBooksCount($user['user_id']);
	$readingBooksCount = $userController->getReadingBooksCount($user['user_id']);
	
	$bookList = $userController->getShelveBooks("all", $user['user_id']);
	
	if (empty($book['status'])) {
		$book['status'] = 0;
	}
	$status = array("Want To Read", "Currently Reading", "Read");
} catch (Exception $e) {

}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
		<div id="content">
			<div class="topContent userProfileContent">
				<span class="usersName"><?php echo $user['username'];?></span>
				<?php if ((!empty($_SESSION['user'])) && $_SESSION['user']['username'] === $user['username']) {?>
					<a href="editprofile.php" class="editProfileLink"><span>(edit profile)</span></a>
				<?php }?>
			</div>
			<div id="containerHolder">
				<div id="leftCont"class="leftContainer container" >
					<div id="leftTopContent">
						<div id="myProfileLeftContent" >
						<?php if (empty($user['picture'])) {
								$user['picture'] = "images/profileIcon.png";
							}?>
							<div id="myProfilePicture"><img src="<?php echo $user['picture'];?>" width="110px" height="150px"></div>
							<div id="myProfileBoxInfo">
								<table>
									<tbody>
										<tr>
											<td>country</td>
											<td class="infoUser"><?php echo $user['country_name']?></td>
										</tr>
										<tr>
											<td>city</td>
											<td class="infoUser"><?php echo $user['city_name']?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div style="clear:both"></div>
						</div>
					</div>
					<div id="leftDownContent">
						<div class="userBoockshelves textContent">User's Bookshelves</div>
						<div>
							<table class="userBoockshelvesContent">
								<tbody>
									<tr>
										<td id="read"><a href="mybooks.php?shelve=read">Read (<?php echo $readBooksCount;?>)</a></td>
										<td id="reading"><a href="mybooks.php?shelve=reading">Currently Reading (<?php echo $readingBooksCount;?>)</a></td>
										<td id="wantToRead"><a href="mybooks.php?shelve=toread">Want To Read (<?php echo $toReadBooksCount;?>)</a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="userBoockshelves textContent">User's recently added books</div>
						<?php 
							foreach ($bookList as $book) {?>
						<div>
							<div class="userBoockshelves">
								<div class="recentlyAddedBooksContent">
									<?php 
										if (empty($book['image'])) {
											$book['image'] = "bookIcon.png";
										}
									?>
									<div class="booksContentImage"><img src="images/books/<?php echo $book['image'];?>" width="50" height="75"></div>
									<div>
										<div><a href="preview.php?ISBN=<?php echo $book['book_ISBN'];?>"><span class="textColors"><?php echo $book['title']?></span></a></div>
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
										<div><span class="textColors byClass">by &nbsp;</span><span class="textColors"><?php echo $authorsString;?></span></div>
									</div>
								</div>
							</div>
							<div class="statusButton"> 
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
								<div class="fiveStars starsInMyProfile">
											<?php if (empty($book['rating'])) {
												$book['rating'] = 0;
											}?>				
									<div class="stars">
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
								</div>
							</div>
						</div>
						<div style="clear: both; border-bottom: 1px solid #EBEBEB; margin-right: 10px;"></div>
						<?php }?>
					</div>
				</div>
			</div>
			<div id="myProfileRightContent" class="rightContainer container">
			<?php 
				require_once ("genresButtons.php");
			?>
		</div>
		<div style="clear: both"></div>
		</div>
		
<?php 
	require_once ("footers/footer.php");
?>