<?php
//Realiza conexao com o SGDB MySQL
class Conexao{
	private $db;
	
	public function __construct(){
		$this->db = new \PDO('mysql:hostname=localhost;dbname=sonpdo', "webadmin", "admin");
		
	}
	
	public function getDb(){
		return $this->db;
	}
	
}