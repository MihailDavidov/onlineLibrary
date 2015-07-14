<?php 
	try {	
		$userController = new UserController();
		
		if (!empty($_POST['signUp'])) {
			if ((empty($_POST['username'])) ||  (empty($_POST['email'])) || (empty($_POST['password']))) {
				header("Location: register.php");
				exit();
			}
			$error = $userController->registerUser($_POST);
			
			if (empty($error)) {
				$user = $userController->loginUser($_POST);
				
				if ($user == false) {
					header("Location: register.php");
					exit();
				} 
			} else  {
				header("Location: register.php");
				exit();
			}
		}
		
		if (!empty($_POST['login'])) {
			if ((empty($_POST['userEmail'])) || (empty($_POST['userPassword']))) {
				header("Location: signin.php");
				exit();
			}
			$params = array("email" => $_POST['userEmail'], "password" => $_POST['userPassword']);
			$user = $userController->loginUser($params);
			
			if ($user == false) {
				header("Location: signin.php");
				exit();
			}
		}
	} catch (Exception $e) {
		
	}
?>

<div id="backgroundHeader">
			<div id="headerTop">
				<div id="logo" class="siteLogo"><a class="siteLogo" href="index.php">your<span id="secondWord">library</span></a></div>
				<div id="loginForm">
					<?php 
						if (empty($_SESSION['user'])) {
					?>
					<form action="index.php" method="post">
						<div id="userEmail">
							<input class="loginField" id="userLoginEmailForm" type="email" name="userEmail" placeholder=" Email adress">
						    <br/>
						    <input type="checkbox" id="rememberMe" name="rememberMe"> 
							<label for="rememberMe" class="greyText">Remember Me</label>
						</div>
						<div id="userPassword">
							<input class="loginField" type="password" name="userPassword" placeholder=" Password">
							<input type="submit" name="login" id="loginButton" value="Login">
							<br/>
						</div>
					</form>
					<?php } else {?>
						<div class="indexMenuContainer">
							<a href='mybooks.php' class='userName'><?php echo $_SESSION['user']['username'] ?></a>
							<div class="indexMenu">
								<ul>
									<li><a href="myprofile.php">My Profile</a></li>
									<li><a href="mybooks.php">My Books</a></li>
									<li><hr></li>
									<li><a href="index.php?logout=true">Sign Out</a></li>
								</ul>
							</div>
						</div>
					
					<?php }?>
					
				</div>
			</div>
			<div id="headerBottom">
					<?php 
						if (empty($_SESSION['user'])) {
					?>
				<h2 id="registrationFormText">New here? Create a free account!</h2>
				<div id="registrationForm">
					<form action="index.php" method="post">
						<input class="registrationFormInput" type="text" name="username" placeholder="Name">
						<br/>
						<input class="registrationFormInput" type="email" name="email" placeholder="Email Adress">
						<br/>
						<input class="registrationFormInput" type="password" name="password" placeholder="Password">
						<br/>
						<input id="registrationFormButton" type="submit" name="signUp" value="Sign up">
					</form>
					
				</div>
				<div id="socialNetworkButtonsBox">
					<div id="signInSocialNetwork">
						<span id="firstWord">or</span><span id="usingNetwork"> sign in using</span>
						<ul>
							<li>
							<a class="fbLogin" data-redirect="/" href="#">
								<img alt="Sign in with Facebook" src="images/facebook.png" title="Sign in with your Facebook account">
							</a>
							</li>
							<li>
							<a class="fbLogin" data-redirect="/" href="#">
								<img alt="Sign in with Twitter" src="images/twitter.png" title="Sign in with your Twitter account">
							</a>
							<li>
							<a class="fbLogin" data-redirect="/" href="#">
								<img alt="Sign in with Google" src="images/google.png" title="Sign in with your Google account">
							</a>
							</li>
						</ul>
					</div>
				</div>
				<?php }?>
			</div>
		</div>