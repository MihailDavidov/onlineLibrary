<?php
try {
	require_once("init.php");
	
	
	if ((!empty($_SESSION['user'])) && (!empty($_POST['saveSettings']))) {
		$userController = new UserController();
		$isUpdated = $userController->editUserInfo($_POST);
		
		if ($isUpdated == true) {
			header('Location: myprofile.php');
			exit;
		}
	}

	if ((!empty($_SESSION['user'])) && (!empty($_POST['uploadButton']))) {
		$userController = new UserController();
		$isUploaded = $userController->uploadUserImage($_FILES,$_SESSION['user']['user_id']);
	
	}
	
	
} catch (Exception $e) {

}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
		<div id="content">
			<div class="topContent userProfileContent">
				<span class="usersName">My Account</span>
			</div>
			<h1><?php if (isset($isUploaded) && $isUploaded !== true) { echo $isUploaded;}?></h1>
			<div id="containerHolder">
				<form action="editprofile.php" method="post" enctype="multipart/form-data">
					<div id="leftCont" class="leftContainer container">
						<label for="firstName">First Name <span id="requiredField">*</span></label><br/>
						<input type="text" name="firstName" id="firstName" class="fieldSize removeOutline"><br/><br/>
						
						<label for="middleName">Middle Name</label><br/>
						<input type="text" name="secondName" id="middleName" class="fieldSize removeOutline"><br/><br/>
						
						<label for="lastName">Last Name</label><br/>
						<input type="text" name="lastName" id="lastName" class="fieldSize removeOutline"><br/><br/>
						
						<label for="email">Email</label><br/>
						<input type="email" name="email" id="email" class="fieldSize removeOutline"><br/><br/>
						
						<label for="selectGender">Gender</label><br/>
						<select name="selectGender" id="selectGender" class="removeOutline">
							<option value="select">select</option>
							<option value="0">male</option>
							<option value="1">female</option>
						</select><br/><br/>
						
						<label for="city">City</label><br/>
						<input type="text" name="city" id="city" class="fieldSize removeOutline"><br/><br/>
						
						<label for="country">Country</label><br/>
						<select name="selectCountry" id="country" class="removeOutline">
							<option value="select">select</option>
							<?php 
								foreach ($countries as $country) {
									echo "<option value='" . $country . "'>" . $country . "</option>";
								}
							?>
						</select><br/><br/>
						<label for="birthday">Date of Birth</label><br/>
						<input type="date" name="birthday" id="birthday" class="removeOutline"><br/><br/>
						
						<label for="whatYouLike">What Kind of Books Do You Like to Read?</label><br/>
						<textarea name="whatYouLike" id="whatYouLike"></textarea><br/><br/>
						
						<label for="aboutMe">About Me</label><br/>
						<textarea name="aboutMe" id="aboutMe"></textarea>
					</div>
					<div class="changeProfilePicture">
						<div id="profilePictureCont">
						<?php if (empty($_SESSION['user']['picture'])) {
								$_SESSION['user']['picture'] = "images/profileIcon.png";
							}
						?>
							<img id="profileImageIcon" src="<?php echo $_SESSION['user']['picture'];?>" width="110px" height="150px"><br/>
							<input type="file" name="fileToUpload" id="fileToUpload"><br/>
							<input id="uploadPhotoButton" class="editProfileButtons" type="submit" name="uploadButton" value="Upload Photo">
						</div>
					</div>
					<div style="clear: both"></div>
					<div>
						<input id="saveProfileSettingsButton" class="editProfileButtons" 
						type="submit" name="saveSettings" value="Save Profile Settings">
					</div>
				</form>
			</div>

				
		</div>

<?php 
	require_once ("footers/footer.php");
?>