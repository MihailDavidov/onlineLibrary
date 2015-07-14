<?php
class UserModel extends Database
{
	private $db;
	private $shelves = array("read" => 2, "toread" => 0, "reading" => 1);
	
	public function __construct() {
		$this->db = $this->getInstance();
	}
	
	public function registerUser($params) {
		$query = "INSERT INTO user (username,email,password)
					VALUES (:username,:email,:password)";
		
		$bindParams = array(
			'username' => $params['username'],
			'email' => $params['email'],
			'password' => md5($params['password']),
		);
		return $this->db->Execute($query, $bindParams);
	}
	
	public function loginUser($params) {
		$query = "SELECT * 
				FROM user 
				WHERE email = :email
				AND password = :password";
		
		$bindParams = array('email' => $params['email'],
			'password' => md5($params['password']),

		);
		return $this->db->ExecuteSelectRow($query, $bindParams);
	}
	
	public function getUserAllBooksCount($userId) {
		$query = "SELECT count(*) 
				FROM `user_book` 
				WHERE user_id = :userId";
		$bindParams = array("userId" => $userId);
		
		return $this->db->getSqlValue($query, $bindParams);
	}
	
	public function getReadBooksCount($userId) {
		$query = "SELECT count(*)
				FROM `user_book`
				WHERE user_id = :userId
				AND status = 2";
		
		$bindParams = array("userId" => $userId);
	
		return $this->db->getSqlValue($query, $bindParams);
	}
	
	public function getToReadBooksCount($userId) {
		$query = "SELECT count(*)
				FROM `user_book`
				WHERE user_id = :userId
				AND status = 0";
	
		$bindParams = array("userId" => $userId);
	
		return $this->db->getSqlValue($query, $bindParams);
	}
	
	public function getReadingBooksCount($userId) {
		$query = "SELECT count(*)
				FROM `user_book`
				WHERE user_id = :userId
				AND status = 1";
	
		$bindParams = array("userId" => $userId);
	
		return $this->db->getSqlValue($query, $bindParams);
	}
	
	public function getShelveBooks($shelve, $userId) {
		$query = "SELECT *
				FROM `user_book` u_b
				JOIN book b ON (b.book_ISBN = u_b.book_ISBN)
				WHERE user_id = :userId";
		
		$bindParams = array("userId" => $userId);
		
		if (isset($this->shelves[$shelve])) {
			$query .= " and status = :status";
			$bindParams['status'] = $this->shelves[$shelve];
		}
		
		return $this->db->ExecuteSelectArray($query, $bindParams);
	}
	
	public function removeBookFromUser($ISBN, $userId) {
		$query = "DELETE 
				FROM `user_book` 
				WHERE `user_id` = :user_id 
				AND `book_ISBN` = :ISBN";
		$bindParams = array("ISBN" => $ISBN, "user_id" => $userId);
		
		return $this->db->Execute($query, $bindParams);
	}
	
	public function changeBookStatus($ISBN, $userId, $status) {
		$query = "INSERT INTO user_book (user_id,book_ISBN,status) 
				VALUES (:user_id,:ISBN,:status)
				ON DUPLICATE KEY UPDATE status=:status";
		
		$bindParams = array("ISBN" => $ISBN, 
							"user_id" => $userId,
							"status" => $this->shelves[$status]
					);
	
		return $this->db->Execute($query, $bindParams);
	}
	
	public function addComment($comment, $ISBN, $userId) {
		$query = "INSERT INTO user_comment (book_ISBN, user_id, comment, date) 
				VALUES (:ISBN, :userId, :comment, NOW());";
		$bindParams = array("comment" => $comment, "ISBN" => $ISBN, "userId" => $userId);
		
		$this->db->Execute($query, $bindParams);
	}
	
	public function editUserInfo($params) {
		$query = "INSERT INTO city (name) 
				VALUES (:name)
				ON DUPLICATE KEY UPDATE name=:name";
		$bindParams = array("name" => $params['city']);
		$this->db->Execute($query, $bindParams);
		$cityId = $this->db->last_insert_id;
		
		$query = "INSERT INTO country (name)
				VALUES (:name)
				ON DUPLICATE KEY UPDATE name=:name";
		$bindParams = array("name" => $params['selectCountry']);
		$this->db->Execute($query, $bindParams);
		$countryId = $this->db->last_insert_id;
		
		$query = "INSERT INTO names (first_name, second_name, last_name)
				VALUES (:first_name, :second_name, :last_name)
				ON DUPLICATE KEY UPDATE first_name=:first_name, 
					second_name=:second_name, 
					last_name=:last_name";
		$bindParams = array("first_name" => $params['firstName'],
					"second_name" => $params['secondName'],
					"last_name" => $params["lastName"],
		);
		$this->db->Execute($query, $bindParams);
		$nameId = $this->db->last_insert_id;
		
		$query = "UPDATE user 
				SET email = :email, 
					gender = :gender, 
					city_id = :city_id, 
					country_id = :country_id, 
					name_id = :name_id, 
					date_of_birth = :date_of_birth, 
					preference_deskcription = :preference_deskcription, 
					about_me = :about_me 
				WHERE user_id = :user_id";
		$bindParams = array("email" => $params['email'],
					"gender" => $params['selectGender'],
					"city_id" => $cityId,
					"country_id" => $countryId,
					"name_id" => $nameId,
					"date_of_birth" => $params['birthday'],
					"preference_deskcription" => $params['whatYouLike'],
					"about_me" => $params['aboutMe'],
					"user_id" => $_SESSION['user']['user_id'],				
		);
		
		$this->db->Execute($query, $bindParams);
	}
	
	public function uploadUserImage($files, $userId) {
		$query = "UPDATE user 
				SET picture = :files 
				WHERE user_id = :userId";
		
		$bindParams = array("files" => $files, "userId" => $userId);
		
		$this->db->Execute($query, $bindParams);
	}
	
	public function showUserName($username) {
		$query = "SELECT *, c_y.name AS country_name, c.name AS city_name
				FROM `user` u
				LEFT JOIN city c ON (u.city_id = c.city_id)
				LEFT JOIN country c_y ON (u.country_id = c_y.country_id)
				LEFT JOIN names n ON (u.name_id = n.name_id)
				WHERE username = :username";
		$bindParams = array("username" => $username);
		
		return $this->db->ExecuteSelectRow($query, $bindParams);
	}
	public function rateBook($isbn, $userId, $rating) {
		$query = "INSERT INTO user_book (user_id,book_ISBN,status,rating) 
				VALUES (:user_id,:ISBN,0,rating)
				ON DUPLICATE KEY UPDATE rating=:rating";
		$bindParams = array('ISBN' => $isbn, 
				'user_id' => $userId, 
				'rating' => $rating
		);
		$this->db->Execute($query, $bindParams);
		
		$query = "UPDATE book 
				SET raiting_points = raiting_points + :rating,
					raiting_votes = raiting_votes + 1 
				WHERE book_ISBN = :ISBN";
		$bindParams = array('ISBN' => $isbn,
				'rating' => $rating
		);
		$this->db->Execute($query, $bindParams);
	}
	
	public function getBookRating($isbn, $userId) {
		$query = "SELECT *
					FROM user_book
					WHERE book_ISBN = :ISBN 
						AND user_id = :userId";
		$bindParams = array(
				'ISBN' => $isbn,
				'userId' => $userId,
		);
		return $this->db->ExecuteSelectRow($query, $bindParams);
	}
}