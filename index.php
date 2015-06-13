<?php
session_start();
//Requires
require_once 'Conexao.php';
require_once 'EntidadeInterface.php';
require_once 'Alunos.php';
require_once 'Usuarios.php';
require_once 'ServiceDb.php';

//Teste de conexao
try{
	$conexao = new Conexao();
}catch (PDOException $e){
	echo 'Erro ao tentar realizar conexao com banco de dados. Erro.: '.$e->getCode().'</br>';
}
//atribuicoes de variaveis

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$pg = filter_input(INPUT_GET, 'pg');
	$acao = filter_input(INPUT_GET, 'acao');
	$id = filter_input(INPUT_GET, 'id');
	$search = filter_input(INPUT_GET, 'search');
}else{
	$pg = filter_input(INPUT_POST, 'pg');
	$acao = filter_input(INPUT_POST, 'acao');
	$id = filter_input(INPUT_POST, 'id');
	$search = filter_input(INPUT_POST, 'search');
}

if($acao == 'login'){
	$pagina = new Usuarios();
	$servicedb = new ServiceDB($conexao,$pagina);
	
	$pagina->setLogin($_POST['login'])
		->setSenha($_POST['senha'])
	;	
	
	if(!$registros = $servicedb->login()){
		$display_error = 'Erro ao tentar realizar o login.';
		$render = 'principal';
		include_once 'layout.html';
	}else{
		$_SESSION['id_usuario'] = $registros['id'];
		$_SESSION['login_usuario'] = $registros['login'];
		header('Location: index.php');
		exit;
	}
	
	$render =  'principal';
	include_once 'layout.html';
}

if($acao == 'logout'){
	session_destroy();
	header('Location: index.php');
	exit;
		
}

if(!$_SESSION['id_usuario'] or !$_SESSION['login_usuario']){
	$render = 'principal';
	include_once 'layout.html';
	
}else{

	if($pg == 'alunos'){
		//Entidade Aluno
		$pagina = new Alunos();
	}else if($pg == 'usuarios'){
		$pagina = new Usuarios();
	}else{
		$render = 'principal';
		include_once 'layout.html';
	}		
		
	//Instancia ServiceDB
	$servicedb = new ServiceDB($conexao, $pagina);
	
	// Acoes
	if($acao == 'alterar'){
		//busca no banco de dados os dados do alunos
		$registros = $servicedb->find((int)$id);
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$nome = $registros['nome'];
			$nota = $registros['nota'];		
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$login = $registros['login'];	
		}
	
	}
	if($acao == 'gravar_inserir'){
		//Inserir novo registro
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$pagina->setNome($_POST['nome_inserir'])
					->setNota($_POST['nota_inserir'])
			;
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$pagina->setLogin($_POST['login_inserir'])
					->setSenha($_POST['senha_inserir'])
			;	
		}	
		
		//Insere no banco
		$servicedb->inserir();
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$registros = $servicedb->listar('nome');
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$registros = $servicedb->listar('login');
		}		
	}
	if($acao == 'gravar_alterar'){
	
		//Alterar cadastro 
		$pagina->setId($id);
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$pagina->setNome($_POST['nome_alterar'])
				->setNota($_POST['nota_alterar'])
			;
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$pagina->setLogin($_POST['login_alterar'])
					->setSenha($_POST['senha_alterar'])
			;	
		}	
	     
		//Altera no banco
		$servicedb->alterar();
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$registros = $servicedb->listar('nome');
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$registros = $servicedb->listar('login');
		}
	}
	if($acao == 'excluir' && $id){
		//deletar um registro
		$servicedb->deletar($id);
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$registros = $servicedb->listar('nome');
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$registros = $servicedb->listar('login');
		}
	}
	
	if($acao=='pesquisar' && $search != ''){
		$registros = $servicedb->find($search);
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$maiores_registros = $servicedb->listar('nota desc limit 3');
		}
	}else{
		if($pg == strtolower(get_class($pagina)) && $pg == 'alunos'){
			$registros = $servicedb->listar('nome');
			$maiores_registros = $servicedb->listar('nota desc limit 3');
		}
		if($pg == strtolower(get_class($pagina)) && $pg == 'usuarios'){
			$registros = $servicedb->listar('login');
		}
	}
	
	
	$render = strtolower(get_class($pagina));
	include_once 'layout.html';
}	


/*//Teste classe aluno
echo 'Aluno.: '.$alunos->getNome().' --> Nota.: '.$alunos->getNota().'</br>';*/