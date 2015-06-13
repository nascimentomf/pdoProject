<?php
//Realizar operacoes no banco de dados
class ServiceDB{
	private $db;
	private $entidade;
	//Estabelecer conexao com o banco
	public function __construct(Conexao $db, EntidadeInterface $entidade){
		$this->db = $db;
		$this->entidade = $entidade;
	}
	
	public function login(){
		$sql = "select * from {$this->entidade->getTable()} where {$this->getAtributos(1)} = :login and {$this->getAtributos(2)} = sha1(:senha)";
		
		//prepare do sql
		$stmt = $this->db->getDb()->prepare($sql);
		$stmt->bindValue(":login", $this->entidade->getLogin(),\PDO::PARAM_STR);
		$stmt->bindValue(":senha", $this->entidade->getSenha(),\PDO::PARAM_STR);
		
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}else{
			return false;
		}
		
	}
	
	public function find($parametro){
		    if(!is_int($parametro)){
		    	$sql = "select * from {$this->entidade->getTable()} where {$this->getAtributos(1)} like :id '%'";
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
		if(get_class($this->entidade) == 'Alunos'){
			$sql = "insert into {$this->entidade->getTable()} ({$this->getAtributos(1)}, {$this->getAtributos(2)}) values (:nome, :nota)";
			
			//prepare do sql
			$stmt = $this->db->getDb()->prepare($sql);
			$stmt->bindValue(":nome", $this->entidade->getNome(),\PDO::PARAM_STR);
			$stmt->bindValue(":nota", $this->entidade->getNota(),\PDO::PARAM_INT);			
		}
		if(get_class($this->entidade) == 'Usuarios'){
			$sql = "insert into {$this->entidade->getTable()} ({$this->getAtributos(1)}, {$this->getAtributos(2)}) values (:login, sha1(:senha))";
			
			//prepare do sql
			$stmt = $this->db->getDb()->prepare($sql);
			$stmt->bindValue(":login", $this->entidade->getLogin(),\PDO::PARAM_STR);
			$stmt->bindValue(":senha", $this->entidade->getSenha(),\PDO::PARAM_STR);	
		}
		
		
		//realiza cadastro de novo aluno e nota
		if($stmt->execute()){
			return true;
		}
	}
	
	//alterar registro existente
	public function alterar(){
		if(get_class($this->entidade) == 'Alunos'){
			$sql = "update {$this->entidade->getTable()} set {$this->getAtributos(1)} = :nome, {$this->getAtributos(2)} = :nota where id = :id";
			
			//prepare do sql
			$stmt = $this->db->getDb()->prepare($sql);
			$stmt->bindValue(":id", $this->entidade->getId(), \PDO::PARAM_INT);
			$stmt->bindValue(":nome", $this->entidade->getNome(), \PDO::PARAM_STR);
			$stmt->bindValue(":nota", $this->entidade->getNota(), \PDO::PARAM_INT);
		}
		
		if(get_class($this->entidade) == 'Usuarios'){
			$sql = "update {$this->entidade->getTable()} set {$this->getAtributos(1)} = :login, {$this->getAtributos(2)} = sha1(:senha) where id = :id";
			
			//prepare do sql
			$stmt = $this->db->getDb()->prepare($sql);
			$stmt->bindValue(":id", $this->entidade->getId(), \PDO::PARAM_INT);
			$stmt->bindValue(":login", $this->entidade->getLogin(), \PDO::PARAM_STR);
			$stmt->bindValue(":senha", $this->entidade->getSenha(), \PDO::PARAM_STR);
		}

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
	
	//obter colunas da tabela
	public function getAtributos($atributo){
		$sql = "describe {$this->entidade->getTable()}";
		$stmt = $this->db->getDb()->query($sql);
		
		$resultado = $stmt->fetchAll(\PDO::FETCH_COLUMN);
		return $resultado[$atributo];
	}
	
}