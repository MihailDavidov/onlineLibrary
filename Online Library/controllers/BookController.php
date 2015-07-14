<?php
class BookController {	
	private $model;
	
	public function  __construct() {
		$this->model = new BookModel();
	}
	
	public function getRecomendedBooks() {	
		$allRecommendedBooks = $this->model->getRecomendedBooks();
		$randomRecomendedBooks = array();
		for ($i = 0; $i < 5; $i++) {
			$randIndex = rand(0, count($allRecommendedBooks)-1);
			while (!empty($randomRecomendedBooks[$randIndex])) {
				$randIndex = rand(0, count($allRecommendedBooks)-1);
			}
			$randomRecomendedBooks[$randIndex] = $allRecommendedBooks[$randIndex];
		}
		return $randomRecomendedBooks;
	}
		
	public function getMostRatedBooks() {
		
		$topMostRatedBooks = $this->model->getMostRatedBooks();
		$mostRatedBooks = array();
		for ($i = 0; $i < 5; $i++) {
			$randIndex = rand(0, count($topMostRatedBooks)-1);
			while (!empty($mostRatedBooks[$randIndex])) {
				$randIndex = rand(0, count($topMostRatedBooks)-1);
			}
			$mostRatedBooks[$randIndex] = $topMostRatedBooks[$randIndex];
		}
		return $mostRatedBooks;
	}
	
	public function getBookInfo($ISBN) {
		if (empty($ISBN)) {
			$ISBN = "10737315";
		}
		
		if (empty($_SESSION['user'])) {
			$userId = 0;
		} else {
			$userId = $_SESSION['user']['user_id'];
		}
		
		$book = $this->model->getBookInfo($ISBN, $userId); 
		$authors = $this->model->getBookAuthors($ISBN);
		
		foreach ($authors as $index => $author) {
			$name = $author['first_name'];
			if (!empty($author['second_name'])) {
				$name .= " " . $author['second_name'];
			}
			
			if (!empty($author['last_name'])) {
				$name .= " " . $author['last_name'];
			}
			$authors[$index]['name'] = $name;
		}
		$book['authors'] = $authors;
		
		return $book;
	}
	
	public function getSimilarBooks($ISBN) {

		$genres = $this->model->getBookGenres($ISBN);
		$similarBooks = array();
		foreach ($genres as $genre) {
			$booksByGenre = $this->model->getBooksByGenre($genre['genres_id'], $ISBN);
			$similarBooks = array_merge($similarBooks, $booksByGenre);
		}
		return $similarBooks;
	}
	
	public function getBooksByGenre($genreName) {
		$books = $this->model->getBooksByGenreName($genreName);
		$finalBooks = array();
		foreach ($books as $book) {
			$finalBooks[$book['book_ISBN']] = $book;
		}
		
		return array_values($finalBooks);
	}
	
	public function searchBook($search) {
		if (empty($_SESSION['user'])) {
			$userId = 0;
		} else {
			$userId = $_SESSION['user']['user_id'];
		}
		$books = $this->model->searchBook($search, $userId);
		$finalBooks = array();
		
		foreach ($books as $book) {
			if (empty($finalBooks[$book['book_ISBN']])) {
				$finalBooks[$book['book_ISBN']] = $book;
				$finalBooks[$book['book_ISBN']]['authors'] = array();
			}
			
			$name = $book['first_name'];
			if (!empty($book['second_name'])) {
				$name .= " " . $book['second_name'];
			}
				
			if (!empty($book['last_name'])) {
				$name .= " " . $book['last_name'];
			}
			
			$finalBooks[$book['book_ISBN']]['authors'][] = array('name' => $name);
			
		}
		return $finalBooks;
	}
	
	public function getBookComments($ISBN) {
		return $this->model->getBookComments($ISBN);
	}
}