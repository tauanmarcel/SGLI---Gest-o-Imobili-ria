<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include "../../controller/RepasseController.php";

$method =  $_SERVER["REQUEST_METHOD"];


/* Busca */
if($method === "GET") {

	$data = $_GET;

	$imoveis = (new RepasseController())->index($data);

	echo json_encode($imoveis, JSON_UNESCAPED_SLASHES);
}


/* Edição */
if($method === "PUT") {

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$contratoId = isset($_GET['contrato_id']) ? $_GET['contrato_id'] : ''; 
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new RepasseController())->edit($id, $contratoId, $data);

	echo json_encode($response, JSON_UNESCAPED_SLASHES);
}

/* Exclusão */
if($method === "DELETE") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$contratoId = isset($_GET['contrato_id']) ? $_GET['contrato_id'] : '';

	$response = (new RepasseController())->remove($id, $contratoId);

	echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
