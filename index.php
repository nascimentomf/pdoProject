<?php
//Requires
require_once 'Conexao.php';
require_once 'Alunos.php';
require_once 'ServiceDb.php';
//Teste de conexao
try{
	$conexao = new Conexao();
}catch (PDOException $e){
	echo 'Erro ao tentar realizar conexao com banco de dados. Erro.: '.$e->getCode().'</br>';
}

//atribuicoes de variaveis
// acao
if(!isset($_GET['acao'])){
	$acao = 'listar'; 
}else{
	$acao = $_GET['acao'];
}

// id
if(!isset($_GET['id'])){
	$id = false; 
}else{
	$id = $_GET['id'];
}

//Entidade Aluno
$alunos = new Alunos();

//Instancia ServiceDB
$servicedb = new ServiceDB($conexao, $alunos);

// Acoes
if($acao == 'inserir'){
	//Inserir novo registro
	$alunos->setNome('Caetano')
			->setNota(5)
	;
	
	//Insere no banco
	$servicedb->inserir();	
}

if($acao == 'excluir' && $id){
	//deletar um registro
	$servicedb->deletar($id);
}

if($acao == 'alterar' && $id){
	//Alterar cadastro 
	$alunos->setId($id);
	
	$alunos->setNome('Moises')
		->setNota(7)
	;

	//Altera no banco
	$servicedb->alterar();
}

//Listagem de todos os alunos
echo '<h3>Todos os alunos cadastrados | <a href="?acao=inserir">Cadastrar aluno</a></h3>';
foreach ($servicedb->listar('nome') as $resultado){
	echo 'Nome.: '.$resultado['nome'].' -- Nota.: '.$resultado['nota'].'-- <a href="?id='.$resultado['id'].'&acao=alterar">Alterar</a> | <a href="?id='.$resultado['id'].'&acao=excluir">Excluir</a></br>';
}

//Listagem das tres melhores notas
echo '<h3>Tres maiores notas</h3>';
foreach ($servicedb->listar('nota desc limit 3') as $resultado){
	echo 'Nome.: '.$resultado['nome'].' -- Nota.: '.$resultado['nota'].'</br>';
}



/*//Teste classe aluno
echo 'Aluno.: '.$alunos->getNome().' --> Nota.: '.$alunos->getNota().'</br>';*/