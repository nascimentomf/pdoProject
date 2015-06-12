<?php
//Realizar operacoes no banco de dados
class ServiceDB{
	private $db;
	private $entidade;
	//Estabelecer conexao com o banco
	public function __construct(Conexao $db, Alunos $entidade){
		$this->db = $db;
		$this->entidade = $entidade;
	}
	
	public function find($parametro){
		    if(!is_int($parametro)){
		    	$sql = "select * from {$this->entidade->getTable()} where nome like :id '%'";
		    }else{
		    	$sql = "select * from {$this->entidade->getTable()} where id = :id";
		    }
			
			$stmt = $this->db->getDb()->prepare($sql);
			$stmt->bindValue(":id", $parametro);
			$stmt->execute();
			return $stmt->fetch(\PDO::FETCH_ASSOC);

	
		
	}
	//listagem
	public function listar($ordem=null){
		if($ordem){
			$sql = "select * from {$this->entidade->getTable()} order by {$ordem}";
		} else{
			$sql = "select * from {$this->entidade->getTable()}";
		}
		
		$stmt = $this->db->getDb()->query($sql);
		
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	//novo registro
	public function inserir(){
		$sql = "insert into {$this->entidade->getTable()} (nome, nota) values (:nome, :nota)";
		
		//prepare do sql
		$stmt = $this->db->getDb()->prepare($sql);
		$stmt->bindValue(":nome", $this->entidade->getNome(),\PDO::PARAM_STR);
		$stmt->bindValue(":nota", $this->entidade->getNota(),\PDO::PARAM_INT);
		
		//realiza cadastro de novo aluno e nota
		if($stmt->execute()){
			return true;
		}
	}
	
	//alterar registro existente
	public function alterar(){
		$sql = "update {$this->entidade->getTable()} set nome = :nome, nota = :nota where id = :id";
		
		//prepare do sql
		$stmt = $this->db->getDb()->prepare($sql);
		$stmt->bindValue(":id", $this->entidade->getid(), \PDO::PARAM_INT);
		$stmt->bindValue(":nome", $this->entidade->getNome(), \PDO::PARAM_STR);
		$stmt->bindValue(":nota", $this->entidade->getNota(), \PDO::PARAM_INT);

		//realiza alteracao no cadastro do aluno
		if($stmt->execute()){
			return true;
		}
		
		
	}
	
	//excluir um registro
	public function deletar($id){
		$sql = "delete from {$this->entidade->getTable()} where id = :id";
		
		//prepare do sql
		$stmt = $this->db->getDb()->prepare($sql);
		$stmt->bindValue(":id", $id, \PDO::PARAM_INT);
		
		//realiza a exclusao do registro
		if($stmt->execute()){
			return true;
		}
		
	}
	
}