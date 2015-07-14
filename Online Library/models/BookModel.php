<?php
class BookModel extends Database
{
	private $db;
	
	public function __construct() {
		$this->db = $this->getInstance();
	}
	
	public function getRecomendedBooks() {
		$query = "SELECT *
				  FROM book
				  WHERE is_recommended = 1";
		return $this->db->ExecuteSelectArray($query,array());
	}
		
	public function getMostRatedBooks() {
		$query = "SELECT *,
						(raiting_points / raiting_votes) as rate
				  FROM book
				  ORDER BY rate DESC
				  LIMIT 10";
		
		return $this->db->ExecuteSelectArray($query,array());
	}
	
	public function getBookInfo($ISBN, $userId) {
		$query = "SELECT *, 
						(SELECT u_b.status 
						FROM user_book u_b 
						WHERE u_b.book_ISBN = :ISBN 
							AND u_b.user_id = :userId) as status 
					FROM `book` b 
					WHERE b.book_ISBN = :ISBN";
		$bindParams = array("ISBN" => $ISBN, "userId" => $userId);
		
		return $this->db->ExecuteSelectRow($query, $bindParams);
	}
	
	public function getBookAuthors($ISBN) {
		$query = "SELECT a.*, n.first_name, n.second_name, n.last_name
				FROM `book_author` b_a
				JOIN author a ON (a.author_id = b_a.author_id)
				JOIN names n ON (a.name_id = n.name_id)
				WHERE book_ISBN = :ISBN";
		$bindParams = array("ISBN" => $ISBN);
	
		return $this->db->ExecuteSelectArray($query, $bindParams);
	}
	
	public function getBookGenres($ISBN) {
		$query = "SELECT * 
				FROM `book_genres` b_g
				JOIN genres g ON (g.genres_id = b_g.genres_id)
				WHERE book_ISBN = :ISBN";
		$bindParams = array("ISBN" => $ISBN);
	
		return $this->db->ExecuteSelectArray($query, $bindParams);
	}
	
	public function getBooksByGenre($genreId, $ISBN) {
		$query = "SELECT b.book_ISBN, b.image
				FROM `book_genres` b_g
				JOIN book b ON (b.book_ISBN = b_g.book_ISBN)
				WHERE genres_id = :genre_id 
					AND b.book_ISBN <> :ISBN";
		
		$bindParams = array("genre_id" => $genreId, "ISBN" => $ISBN);
		return $this->db->ExecuteSelectArray($query, $bindParams);
	}
	
	public function getBooksByGenreName($genreName) {
		$query = "SELECT b.book_ISBN, b.image
				FROM `book` b
				JOIN book_genres b_g ON (b.book_ISBN = b_g.book_ISBN)
				JOIN genres g ON (g.genres_id = b_g.genres_id)
				WHERE g.name = :genreName";
	
		$bindParams = array("genreName" => $genreName);
		return $this->db->ExecuteSelectArray($query, $bindParams);	
	}
	public function searchBook($search, $userId) {
		$query = 'SELECT b.*, n.first_name, n.second_name, n.last_name, u_b.rating, u_b.status
				FROM book b
				LEFT JOIN book_author b_a ON (b.book_ISBN = b_a.book_ISBN)
				LEFT JOIN author a ON (b_a.author_id = a.author_id)
				LEFT JOIN names n ON (a.name_id = n.name_id)
				LEFT JOIN user_book u_b ON (b.book_ISBN = u_b.book_ISBN AND u_b.user_id = :userId) 
					
				WHERE b.book_ISBN LIKE :search 
					OR b.title LIKE :search
					OR n.first_name LIKE :search 
				    OR n.second_name LIKE :search
				    OR n.last_name LIKE :search
				LIMIT 50';
		$bindParams = array("search" => "%".$search."%", "userId" => $userId);
		return $this->db->ExecuteSelectArray($query, $bindParams);
	}
	
	public function getBookComments($ISBN) {
		$query ="SELECT * FROM `user_comment` u_c
				JOIN user u ON (u_c.user_id = u.user_id)
				WHERE u_c.book_ISBN = :ISBN
				ORDER BY u_c.comment_id DESC
				LIMIT 10";
		$bindParams = array("ISBN" => $ISBN);
		
		return $this->db->ExecuteSelectArray($query, $bindParams);
	}
}