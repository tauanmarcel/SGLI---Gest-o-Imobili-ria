<?php

include_once "../../model/LocatarioTable.php";
include_once "../../model/ContratoTable.php";
include_once "Validator.php";

class LocatarioController extends LocatarioTable {

	private $validator;

	public function __construct() {

		$this->validator = new Validator();

	}

	public function index($data = []) {

		$data = $this->validator->clearData($data);

		$id    = isset($data['id'])    ? $data['id']    : '';
		$nome  = isset($data['nome'])  ? $data['nome']  : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$fone  = isset($data['fone'])  ? $data['fone']  : '';

		$data = [
			'id'    => $id,
			'nome'  => $nome,
			'email' => $email,
			'fone'  => $fone
		];

		return $this->find($data);
	}

	public function create($data) {

		$data = $this->validator->clearData($data);

		$nome  = isset($data['nome'])  ? $data['nome']  : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$fone  = isset($data['fone'])  ? $data['fone']  : '';

		try {

			if(empty($nome)) {

				throw new Exception("O nome deve ser informado!");
			}

			if(empty($email)) {

				throw new Exception("O e-mail deve ser informado!");
			}

			if(empty($fone)) {

				throw new Exception("O telefone deve ser informado!");
			}

			if(!$this->validator->validateEmail($email)) {

				throw new Exception("E-mail inválido");
			}

			if(!$this->validator->validatePhone($fone)) {

				throw new Exception("Telefone inválido");
			}

			$response = $this->find(['email' => $email]);

			if(count($response) > 0) {

				throw new Exception("O e-mail informado já está cadastrado!");
			}

			$data = [
				'nome'  => $nome, 
				'email' => $email, 
				'fone'  => $fone
			];

			if(!$this->store($data)) {

				throw new Exception("Erro ao cadastrar locatário!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Locatário cadastrado com sucesso!'
			];

		} catch(Exception $e) {

			header("HTTP/1.1 400 Bad Request");

			return [
				'status' => 400,
				'mensagem' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	public function edit($id, $data) {

		$data['id'] = $id;

		$data = $this->validator->clearData($data);
		
		$id    = isset($data['id'])    ? $data['id']    : '';
		$nome  = isset($data['nome'])  ? $data['nome']  : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$fone  = isset($data['fone'])  ? $data['fone']  : '';

		try {
			
			if(empty($id)) {

				throw new Exception("Id do locatário não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Locatário não encontrado!");
			}

			if(!empty($email) && !$this->validator->validateEmail($email)) {

				throw new Exception("E-mail inválido");
			}

			if(!empty($fone) && !$this->validator->validatePhone($fone)) {

				throw new Exception("Telefone inválido");
			}

			$data = [
				'id'    => $id,
				'nome'  => $nome,
				'email' => $email,
				'fone'  => $fone
			];

			if(!empty($data) && !$this->update($data)) {

				throw new Exception("Erro ao cadastrar locatário!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Locatário editado com sucesso!'
			];

		} catch(Exception $e) {

			header("HTTP/1.1 400 Bad Request");

			return [
				'status' => 400,
				'mensagem' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	public function remove($id) {

		$data = $this->validator->clearData(['id' => $id]);

		$id = $data['id'];

		try {
			if(empty($id)) {

				throw new Exception("Id do locatário não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Locatário não encontrado!");
			}

			$response = (new ContratoTable())->find(['locatario_id' => $id]);

			if(count($response) > 0) {

				throw new Exception("O locatário não pode ser excluído porque possui contrato!");
			}

			if(!$this->delete($id)) {

				throw new Exception("Erro ao excluir locatário!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Locatário excluído com sucesso!'
			];

		} catch(Exception $e) {

			header("HTTP/1.1 400 Bad Request");

			return [
				'status' => 400,
				'mensagem' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}
}