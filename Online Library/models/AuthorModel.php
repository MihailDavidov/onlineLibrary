<?php
class AuthorModel extends Database
{
	private $db;
	
	public function __construct() {
		$this->db = $this->getInstance();
	}
}