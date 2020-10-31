<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: aplication/json");

include "../../controller/LocadorController.php";


$method =  $_SERVER["REQUEST_METHOD"];

/* Busca de locadores */
if($method === "GET") {

	$data = $_GET;

	$locadores = (new LocadorController())->index($data);

	echo json_encode($locadores);
}

/* Cadastro de novo locador */
if($method === "POST") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new LocadorController())->create($data);

	echo json_encode($response);
}

/* Edição de locador */
if($method === "PUT") {

	$id = $_GET['id'];
	
	$data = json_decode(file_get_contents('php://input'), true);

	$response = (new LocadorController())->edit($id, $data);

	echo json_encode($response);
}

/* Exclui um locdor */
if($method === "DELETE") {
	
	$data = json_decode(file_get_contents('php://input'), true);

	$id = $_GET['id'];

	$response = (new LocadorController())->remove($id);

	echo json_encode($response);
}
