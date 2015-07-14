<?php
 class UserController {
 	private $model;
 	
 	public function __construct() {	
 		$this->model = new UserModel();
 	}
 	
 	public function getUserAllBooksCount($userId) {		
 		return $this->model->getUserAllBooksCount($userId);
 	}
 	
 	public function getReadBooksCount($userId) {
 		return $this->model->getReadBooksCount($userId);
 	}
 	
 	public function getToReadBooksCount($userId) {	
 		return $this->model->getToReadBooksCount($userId);
 	}
 	

 	public function getReadingBooksCount($userId) {
 		return $this->model->getReadingBooksCount($userId);
 	}
 	
 	public function getShelveBooks($shelve, $userId) {
 		
 		$bookModel = new BookModel();
 		$books = $this->model->getShelveBooks($shelve, $userId);
 		
 		foreach ($books as $index => $book) {
 			$authors = $bookModel->getBookAuthors($book['book_ISBN']);
 			
 			foreach ($authors as $ind => $author) {
 				$name = $author['first_name'];
 				if (!empty($author['second_name'])) {
 					$name .= " " . $author['second_name'];
 				}
 					
 				if (!empty($author['last_name'])) {
 					$name .= " " . $author['last_name'];
 				}
 				$authors[$ind]['name'] = $name;
 			}
 			$books[$index]['authors'] = $authors;
 		}
 		return $books;
 	}
 	
 	public function removeBookFromUser($ISBN, $userId) {
 		$result = $this->model->removeBookFromUser($ISBN, $userId);
 		
 		return (!empty($result)) ? true : false;
 	}
 	
 	public function changeBookStatus($ISBN, $userId, $status) {
 		$result = $this->model->changeBookStatus($ISBN, $userId, $status);
 		
 		return true;
 	}
 	
 	public function addComment($comment, $ISBN, $userId) {
 		$this->model->addComment($comment, $ISBN, $userId);
 	}
 	
 	public function editUserInfo($params) {
 		$errors = array();
 		
 		if (empty($params['firstName'])) {
 			$errors[] = "Empty name";
 		}
 		
 		if (!empty($errors)) {
 			return $errors;
 		}
 		$this->model->editUserInfo($params);
 		if (!empty($files['fileToUpload'])) {
 			
 		}
 		return true;
 	}
 	
 	public function uploadUserImage($files, $userId) {
	 		$target_dir = "uploads/". rand(100000,4354354) . "_";
	 		$target_file = $target_dir . basename($files["fileToUpload"]["name"]);
	 		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	 		if(empty($files["fileToUpload"]["tmp_name"])) {
	 			return "No file.";
	 		}
 			$check = getimagesize($files["fileToUpload"]["tmp_name"]);
 			//check image size if no size => no image
 			if($check === false) {
 				return "File is not an image.";
 			}

	 		// Check file size (how big is the image)
	 		if ($files["fileToUpload"]["size"] > 5000000) {
	 			return "Sorry, your file is too large.";
	 		}
	 		// Allow certain file formats
	 		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
 				&& $imageFileType != "gif" ) {
 				return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
 			}

 			if (!move_uploaded_file($files["fileToUpload"]["tmp_name"], $target_file)) {						
 				return "Sorry, there was an error uploading your file.";
 			}
 			
			$this->model->uploadUserImage($target_file, $userId);
			$_SESSION['user']['picture'] = $target_file;
 			
 			return true;
 	}
 	
 	public function showUserName($username) {
 		return $this->model->showUserName($username);
 	}

 	public function registerUser($params) {
 //da potursia validatsii za username, email, password.
 		$errors = array();
 		$result = $this->model->registerUser($params);
 		if (empty($result)) {
 			$errors = "Invalid query";
 		}
 		return $errors;
 	}
 	
 	public function loginUser($params) {
 		//validatsii
 		
 		$user = $this->model->loginUser($params);
 		
 		if (!empty($user)) {
 			$_SESSION['user'] = $user;
 			return true;
 		} else  {
 			return false;
 		}
 	}
 	
 	public function rateBook($isbn, $userId, $rating) {
 		//tuk moje da se wzeme dali potrbitelq e prochel knigata ili ako q chete togawa da moje da q oceni
 		
 		 $this->model->rateBook($isbn, $userId, $rating);
 		 return true;
 	}
 	
 	public function getBookRating($isbn, $userId) {
 		return  $this->model->getBookRating($isbn, $userId);
 	}
 }