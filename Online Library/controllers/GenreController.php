<?php
	class GenreController {
		
		public function getGenres() {
			$genreModel = new GenresModel();
			return $genreModel->getGenres();
		}
		
		public function getGenreByName($genreName) {
			
			if (empty($genreName)) {
				$genreName = "Social Science";
			}
			
			$genreModel = new GenresModel();
			
			$genre = $genreModel->getGenreByName($genreName);
			
			return $genre;
		}
	}
	
?>