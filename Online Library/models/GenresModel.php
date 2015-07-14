<?php
class GenresModel extends Database {
	private $db;
	
	public function __construct() {
		$this->db = $this->getInstance();
	}
	
	public function getGenres() {
		$query = "SELECT name, genres_id FROM `genres` WHERE length(name)< 18 LIMIT 40";

		return $this->db->ExecuteSelectArray($query, array());
	}
	
	public function getGenreByName($genreName) {		
		$query = "SELECT * 
				FROM `genres` 
				WHERE name = :genreName";
		$bindParams = array("genreName" => $genreName);
				
		return $this->db->ExecuteSelectRow($query, $bindParams);
	}
}