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
	
	public function listar($ordem=null){
		if($ordem){
			$sql = "select * from {$this->entidade->getTable()} order by {$ordem}";
		} else{
			$sql = "select * from {$this->entidade->getTable()}";
		}
		
		$stmt = $this->db->getDb()->query($sql);
		
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}