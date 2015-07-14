	<div id="smallHeader">
		<div id="logoSmall" class="siteLogo"><a class="siteLogo" href="index.php">your<span id="secondWord">library</span></a></div>
		<div id="searchBooks" class="left">
			<div class="siteSearch">
				<form accept-charset="UTF-8" action="search.php" method="get">
					<div class="searchField">
						<input id="searchSmall" class="searchF" type="search" name="search" placeholder="Title/ Author/ ISBN">
						<button type="submit"><img src="images/magnifyingGlass.png"></button>
					</div>
				</form>
			</div>
		</div>
		<div id="buttonsMenu">
<!-- 			<a class="menuButtons" href="index.php">Home</a> -->
				<?php 
					if (!empty($_SESSION['user'])) {
						echo '<a class="menuButtons" href="mybooks.php?shelve=mybooks">My Books</a>';
						echo '<a class="menuButtons" href="mybooks.php?shelve=read">Read</a>';
						echo '<a class="menuButtons" href="mybooks.php?shelve=reading">Reading</a>';
						echo '<a class="menuButtons" href="mybooks.php?shelve=toread">Want To Read</a>';
						echo '<a class="menuButtons" href="myprofile.php">My Profile</a>';
						echo '<a class="menuButtons signOutButton" href="index.php?logout=true">Sign Out</a>';
					} else {
						echo '<a class="menuButtons" href="signin.php">My Books</a>';
						echo '<a class="menuButtons" href="signin.php">Read</a>';
						echo '<a class="menuButtons" href="signin.php">Reading</a>';
						echo '<a class="menuButtons" href="signin.php">Want To Read</a>';
					}
				?>
		</div>
	</div>