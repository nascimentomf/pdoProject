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
if(!isset($_REQUEST['acao'])){
	$acao = 'listar'; 
}else{
	$acao = $_REQUEST['acao'];
}


// id
if(!isset($_REQUEST['id'])){
	$id = false; 
}else{
	$id = $_REQUEST['id'];
}

// id
if(!isset($_GET['search'])){
	$search = false; 
}else{
	$search = $_GET['search'];
}

//Entidade Aluno
$alunos = new Alunos();

//Instancia ServiceDB
$servicedb = new ServiceDB($conexao, $alunos);

// Acoes
if($acao == 'alterar'){
	$registros = $servicedb->find((int)$id);
	$nome = $registros['nome'];
	$nota = $registros['nota'];

}
if($acao == 'gravar_inserir'){
	//Inserir novo registro
	$alunos->setNome($_POST['nome_inserir'])
			->setNota($_POST['nota_inserir'])
	;
	
	//Insere no banco
	$servicedb->inserir();
	$registros = $servicedb->listar('nome');	
}
if($acao == 'gravar_alterar'){

	//Alterar cadastro 
	$alunos->setId($id);
	
	$alunos->setNome($_POST['nome_alterar'])
		->setNota($_POST['nota_alterar'])
	;
     
	//Altera no banco
	$servicedb->alterar();
	$registros = $servicedb->listar('nome');
}
if($acao == 'excluir' && $id){
	//deletar um registro
	$servicedb->deletar($id);
	$registros = $servicedb->listar('nome');
}
if($acao=='pesquisar' && $search != ''){
	$registros = $servicedb->find($search);
}else{
	$registros = $servicedb->listar('nome');
}


include 'alunos.html';


/*//Teste classe aluno
echo 'Aluno.: '.$alunos->getNome().' --> Nota.: '.$alunos->getNota().'</br>';*/