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
//Exibir a listagem de alunos;
$alunos = new Alunos();
$alunos->setNome('Moises')
		->setNota(8)
;

//Teste ServiceDb
$servicedb = new ServiceDB($conexao, $alunos);
//Listagem de todos os alunos
echo '<h3>Todos os alunos cadastrados </h3>';
foreach ($servicedb->listar() as $resultado){
	echo 'Nome.: '.$resultado['nome'].' -- Nota.: '.$resultado['nota'].'</br>';
}

//Listagem das tres melhores notas
echo '<h3>Melhores notas</h3>';
foreach ($servicedb->listar('nota desc limit 3') as $resultado){
	echo 'Nome.: '.$resultado['nome'].' -- Nota.: '.$resultado['nota'].'</br>';
}

/*//Teste classe aluno
echo 'Aluno.: '.$alunos->getNome().' --> Nota.: '.$alunos->getNota().'</br>';*/