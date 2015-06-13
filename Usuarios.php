<?php
//Implementar a classe Alunos
class Usuarios implements EntidadeInterface{
	private $table="usuarios";
	private $id;
	private $login;
	private $senha;
	
	//getrs e setrs
	
	public function setId($id){
		$this->id = $id;
		return $this;
	}

	public function setLogin($login){
		$this->login = $login;
		return $this;
	}

	public function setSenha($senha){
		$this->senha = $senha;
		return $this;
	}
	
	public function getTable(){
		return $this->table;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getLogin(){
		return $this->login;
	}
	
	public function getSenha(){
		return $this->senha;
	}
	
}

?>