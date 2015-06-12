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
if($_SERVER['REQUEST_METHOD'] == 'GET'){
	//acao
	$acao = filter_input(INPUT_GET, 'acao');
	
	//id
	$id = filter_input(INPUT_GET, 'id');
}else{
	//acao
	$acao = filter_input(INPUT_POST, 'acao');
	
	//id
	$id = filter_input(INPUT_POST, 'id');
	
	//search
	$search = filter_input(INPUT_POST, 'search');
}

//Entidade Aluno
$alunos = new Alunos();

//Instancia ServiceDB
$servicedb = new ServiceDB($conexao, $alunos);

// Acoes
if($acao == 'alterar'){
	//busca no banco de dados os dados do alunos
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
	$maiores_registros = $servicedb->listar('nota desc limit 3');
}


include 'alunos.html';


/*//Teste classe aluno
echo 'Aluno.: '.$alunos->getNome().' --> Nota.: '.$alunos->getNota().'</br>';*/