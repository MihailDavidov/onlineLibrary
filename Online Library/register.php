<?php
try {

	require_once("init.php");
	
	if (!empty($_SESSION['user'])) {
		header("Location: index.php");
		exit();
	}
	
	if (!empty($_POST['signUp'])) {
		$errors = array();
		$userController = new UserController();
		if ((empty($_POST['username'])) ||  (empty($_POST['email'])) || (empty($_POST['password']))) {
			$errors[] = "You have empty fields.";
		}
		$error = $userController->registerUser($_POST);
			
		if (empty($error)) {
			$user = $userController->loginUser($_POST);
	
			if ($user == false) {
				header("Location: signup.php");
				exit();
			} else {
				header("Location: index.php");
				exit();
			}
		} else  {
			$errors[] = $error;
		}
	}
	
} catch (Exception $e) {

}

require_once ("headers/head.php");
require_once ("headers/registerHeader.php");
?>
		<div id="content">
			<div class="contentBox">
				<div id="emailForm"> 
					<form action="register.php" method="post">
						<div class="messageTextBox">
							<span class="problemMessage">
								<?php 
								if (!empty($errors)) {
									foreach ($errors as $error) {
										echo $error . "<br/>";
									}
								} else {
									echo "Sign up to get book recommendations and join the world’s largest community of readers.";
								}
								
								?>
							</span>
						</div>
						<div>
							<div>
								<label class="loginText" for="name">Name</label><br/>
								<input class="loginFields" type="text" name="username" placeholder="Name" value="<?php if (!empty($_POST['username'])) {echo $_POST['username'];}?>">
								<br/>
								<label class="loginText" for="emailAdress">Email Adress</label><br/>
								<input class="loginFields" type="email" name="email" placeholder="some@email.com" value="<?php if (!empty($_POST['email'])) {echo $_POST['email'];}?>">
								<br/>
								<label class="loginText" for="password">Password</label><br/>
								<input class="loginFields" type="password" name="password">
								<br/>
							</div>
						</div>
						<div>
							<br>
						</div>
						<div>
							<input class="signInLoginButton userLoginButton" type="submit" name="signUp" value="Sing Up">
						</div>
					</form>
				</div>	
			</div>
		</div>

<?php 
	require_once ("footers/footerSignInPage.php");
?>
