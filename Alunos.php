<?php
//Implementar a classe Alunos
class Alunos{
	private $table="alunos";
	private $id;
	private $nome;
	private $nota;
	
	//getrs e setrs
	public function setId($id){
		$this->id = $id;
		return $this;
	}

	public function setNome($nome){
		$this->nome = $nome;
		return $this;
	}

	public function setNota($nota){
		$this->nota = $nota;
		return $this;
	}
	
	public function getTable(){
		return $this->table;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getNome(){
		return $this->nome;
	}
	
	public function getNota(){
		return $this->nota;
	}
	
}

?>