<?php

include_once "../../model/LocadorTable.php";
include_once "../../model/ContratoTable.php";
include_once "../../model/ImovelTable.php";
include_once "Validator.php";

class LocadorController extends LocadorTable {

	private $validator;

	public function __construct() {

		$this->validator = new Validator();

	}

	public function index($data = []) {

		$data = $this->validator->clearData($data);

		$id        = isset($data['id'])           ? $data['id']           : '';
		$nome      = isset($data['nome'])         ? $data['nome']         : '';
		$email     = isset($data['email'])        ? $data['email']        : '';
		$fone      = isset($data['fone'])         ? $data['fone']         : '';
		$dtRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';

		$data = [
			'id'           => $id,
			'nome'         => $nome,
			'email'        => $email,
			'fone'         => $fone,
			'data_repasse' => $dtRepasse
		];

		return $this->find($data);
	}

	public function create($data) {

		$data = $this->validator->clearData($data);

		$nome      = isset($data['nome'])         ? $data['nome']         : '';
		$email     = isset($data['email'])        ? $data['email']        : '';
		$fone      = isset($data['fone'])         ? $data['fone']         : '';
		$dtRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';

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

			if(empty($dtRepasse)) {

				throw new Exception("A data do repasse deve ser informada!");
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
				'nome'         => $nome,
				'email'        => $email,
				'fone'         => $fone,
				'data_repasse' => $dtRepasse
			];

			if(!$this->store($data)) {

				throw new Exception("Erro ao cadastrar locador!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Locador cadastrado com sucesso!'
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'error' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	public function edit($id, $data) {

		$data['id'] = $id;

		$data = $this->validator->clearData($data);
		
		$id        = isset($data['id'])           ? $data['id']           : '';
		$nome      = isset($data['nome'])         ? $data['nome']         : '';
		$email     = isset($data['email'])        ? $data['email']        : '';
		$fone      = isset($data['fone'])         ? $data['fone']         : '';
		$dtRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';

		try {
			if(empty($id)) {

				throw new Exception("Id do locador não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Locador não encontrado!");
			}

			if(!empty($email) && !$this->validator->validateEmail($email)) {

				throw new Exception("E-mail inválido");
			}

			if(!empty($fone) && !$this->validator->validatePhone($fone)) {

				throw new Exception("Telefone inválido");
			}

			$data = [
				'id'           => $id,
				'nome'         => $nome,
				'email'        => $email,
				'fone'         => $fone,
				'data_repasse' => $dtRepasse
			];

			if(!empty($data) && !$this->update($data)) {

				throw new Exception("Erro ao cadastrar locador!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Locador editado com sucesso!'
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'error' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	public function remove($id) {

		$data = $this->validator->clearData(['id' => $id]);

		$id = $data['id'];

		try {
			if(empty($id)) {

				throw new Exception("Id do locador não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Locador não encontrado!");
			}

			$response = (new ContratoTable())->find(['locador_id' => $id]);

			if(count($response) > 0) {

				throw new Exception("O locador não pode ser excluído porque possui contrato!");
			}

			$response = (new ImovelTable())->find(['locador_id' => $id]);

			if(count($response) > 0) {

				throw new Exception("O locador não pode ser excluído porque possui um imóvel cadastrado!");
			}

			if(!$this->delete($id)) {

				throw new Exception("Erro ao excluir locador!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Locador excluído com sucesso!'
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'error' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}
}