<?php
require_once ("init.php");

	if (empty($_SESSION['user'])) {
		return false;
	}
	
	
	if (($_POST['action'] == 'removeBookFromShelve') && (!empty($_POST['ISBN']))) {
		$userController = new UserController();
		$result = $userController->removeBookFromUser($_POST['ISBN'], $_SESSION['user']['user_id']);
		echo json_encode(array('result' => $result)); 
		die();
	}
	
	
	if (($_POST['action'] == "changeBookStatus") && (!empty($_POST['ISBN'])) && (!empty($_POST['status']))) {
		$userController = new UserController();
		$result = $userController->changeBookStatus($_POST['ISBN'], $_SESSION['user']['user_id'], $_POST['status']);
		echo json_encode(array('result' => $result));
		die();
	}
	

	if (($_POST['action'] == "rateBook") && (!empty($_POST['ISBN'])) && (!empty($_POST['rating']))) {
		$userController = new UserController();
		$result = $userController->rateBook($_POST['ISBN'], $_SESSION['user']['user_id'], $_POST['rating']);
		echo json_encode(array('result' => $result));
		die();
	}
?>