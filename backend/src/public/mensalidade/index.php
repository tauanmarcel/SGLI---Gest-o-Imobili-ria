<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include "../../controller/MensalidadeController.php";

$method =  $_SERVER["REQUEST_METHOD"];


/* Busca */
if($method === "GET") {

	$data = $_GET;

	$imoveis = (new MensalidadeController())->index($data);

	echo json_encode($imoveis, JSON_UNESCAPED_SLASHES);
}


/* Edição */
if($method === "PUT") {

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$contratoId = isset($_GET['contrato_id']) ? $_GET['contrato_id'] : ''; 
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new MensalidadeController())->edit($id, $contratoId, $data);

	echo json_encode($response, JSON_UNESCAPED_SLASHES);
}

/* Exclusão */
if($method === "DELETE") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$id = $_GET['id'];

	$response = (new MensalidadeController())->remove($id);

	echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
