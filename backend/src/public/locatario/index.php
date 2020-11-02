<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include "../../controller/LocatarioController.php";


$method =  $_SERVER["REQUEST_METHOD"];

/* Busca de locatários */
if($method === "GET") {

	$data = $_GET;

	$locatarios = (new LocatarioController())->index($data);

	echo json_encode($locatarios, JSON_UNESCAPED_SLASHES);
}

/* Cadastro de novo locatário */
if($method === "POST") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new LocatarioController())->create($data);

	echo json_encode($response);
}

/* Edição de locatário */
if($method === "PUT") {

	$id = $_GET['id'];
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new LocatarioController())->edit($id, $data);

	echo json_encode($response, JSON_UNESCAPED_SLASHES);
}

/* Exclui um locatário */
if($method === "DELETE") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$id = $_GET['id'];

	$response = (new LocatarioController())->remove($id);

	echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
