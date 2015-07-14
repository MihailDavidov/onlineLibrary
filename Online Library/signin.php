<?php
try {

	require_once("init.php");
	
	if (!empty($_SESSION['user'])) {
		header("Location: index.php");
		exit();
	}
	if (!empty($_POST['signin'])) {
		$errors = array();
		$userController = new UserController();
		if ((empty($_POST['email'])) || (empty($_POST['password']))) {
			$errors[] = "You have empty field.";
		}
		$user = $userController->loginUser($_POST);
			
		if ($user == false) {
			$errors[] = "Wrong username or password.";
		} else {
			header("Location: index.php");
			exit();
		}
	}
	
} catch (Exception $e) {

}

require_once ("headers/head.php");
require_once ("headers/signinHeader.php");
?>
			<div id="content">
			<div class="contentBox">
				<div id="emailForm"> 
					<form action="signin.php" method="post">
						<div class="messageTextBox">
							<?php 
								if ((!empty($_POST)) && (!empty($error))) {
									echo '<span class="problemMessage">';
									echo "Sorry, we didn't recognize that email/password combination. Please try again.";
									echo "</span>";
								} elseif (!empty($errors)) {
									foreach ($errors as $error) {
										echo $error . "<br/>";
									}
								}
							?>
						</div>
						<div>		
							<div>
								<label class="loginText" for="emailAdress">Email Adress</label><br/>
								<input class="loginFields" type="email" name="email" placeholder="some@email.com" value="<?php if (!empty($_POST['email'])) {echo $_POST['email'];}?>">
								<br/>
								<label class="loginText" for="password">Password</label><br/>
								<input class="loginFields" type="password" name="password">
								<br/>
							</div>
							<div class="fieldRememberMe">
								<input class="rememberMeCheckbox" checked="checked" id="rememberMe" name="rememberMe" type="checkbox">
								<label class="rememberMeText" for="remember_me">Keep me signed in</label>
							</div>
						</div>
						<div>
							<br>
							<br>
						</div>
						<div>
							<input class="signInLoginButton userLoginButton" type="submit" name="signin" value="Sign in">
						</div>
					</form>
				</div>	
			</div>
		</div>


<?php 
	require_once ("footers/footerSignInPage.php");
?>
