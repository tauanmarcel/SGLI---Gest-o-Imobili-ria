<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include "../../controller/ImovelController.php";

$method =  $_SERVER["REQUEST_METHOD"];


/* Busca */
if($method === "GET") {

	$data = $_GET;

	$imoveis = (new ImovelController())->index($data);

	echo json_encode($imoveis);
}

/* Novo Registro */
if($method === "POST") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new ImovelController())->create($data);

	echo json_encode($response);
}

/* Edição */
if($method === "PUT") {

	$id = $_GET['id'];
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new ImovelController())->edit($id, $data);

	echo json_encode($response);
}

/* Exclusão */
if($method === "DELETE") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$id = $_GET['id'];

	$response = (new ImovelController())->remove($id);

	echo json_encode($response);
}
