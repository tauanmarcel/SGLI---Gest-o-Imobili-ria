<?php 
header("Access-Control-Allow-Origin: *");
// header("Content-Type: aplication/json");

include "../../controller/ContratoController.php";

$method =  $_SERVER["REQUEST_METHOD"];


/* Busca */
if($method === "GET") {

	$data = $_GET;

	$contratos = (new ContratoController())->index($data);

	echo json_encode($contratos);
}

/* Novo Registro */
if($method === "POST") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new ContratoController())->create($data);

	echo json_encode($response);
}

/* Edição */
if($method === "PUT") {

	$id = $_GET['id'];
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new ContratoController())->edit($id, $data);

	echo json_encode($response);
}

/* Exclusão */
if($method === "DELETE") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$id = $_GET['id'];

	$response = (new ContratoController())->remove($id);

	echo json_encode($response);
}
