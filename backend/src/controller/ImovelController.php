<?php

include_once "../../model/ImovelTable.php";
include_once "../../model/LocadorTable.php";
include_once "Validator.php";

class ImovelController extends ImovelTable {

	private $validator;

	public function __construct() {

		$this->validator = new Validator();

	}

	public function index($data = []) {

		$data = $this->validator->clearData($data);

		$id         = isset($data['id'])          ? $data['id']          : '';
		$codigoApi  = isset($data['codigo_api'])  ? $data['codigo_api']  : '';
		$bairro     = isset($data['bairro'])      ? $data['bairro']      : '';
		$cidade     = isset($data['cidade'])      ? $data['cidade']      : '';
		$locadorId  = isset($data['locador_id'])  ? $data['locador_id'] : '';
		$nmeLocador = isset($data['nme_locador']) ? $data['nme_locador'] : '';

		$data = [
			'id'          => $id,
			'codigo_api'  => $codigoApi,
			'bairro'      => $bairro,
			'cidade'      => $cidade,
			'locador_id'  => $locadorId,
			'nme_locador' => $nmeLocador
		];

		return $this->find($data);
	}

	public function create($data) {

		$data = $this->validator->clearData($data);

		$codigoApi = isset($data['codigo_api']) ? $data['codigo_api'] : null;
		$bairro    = isset($data['bairro'])     ? $data['bairro']     : '';
		$cidade    = isset($data['cidade'])     ? $data['cidade']     : '';
		$locadorId = isset($data['locador_id']) ? $data['locador_id'] : '';

		try {
			
			if(empty($bairro)) {

				throw new Exception("O bairro deve ser informado!");
			}

			if(empty($cidade)) {

				throw new Exception("A cidade deve ser informada!");
			}

			if(empty($locadorId)) {

				throw new Exception("O locador deve ser informado!");
			}

			$response = (new LocadorTable())->find(['id' => $locadorId]);
				
			if(count($response) === 0) {

				throw new Exception("Locador não cadastrado!");
			}

			$data = [
				'codigo_api' => $codigoApi,
				'bairro'     => $bairro,
				'cidade'     => $cidade,
				'locador_id' => $locadorId
			];

			if(!$this->store($data)) {

				throw new Exception("Erro ao cadastrar imóvel!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Imóvel cadastrado com sucesso!'
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
		
		$id        = isset($data['id'])         ? $data['id']         : '';
		$codigoApi = isset($data['codigo_api']) ? $data['codigo_api'] : '';
		$bairro    = isset($data['bairro'])     ? $data['bairro']     : '';
		$cidade    = isset($data['cidade'])     ? $data['cidade']     : '';
		$locadorId = isset($data['locador_id']) ? $data['locador_id'] : '';

		try {
			if(empty($id)) {

				throw new Exception("Imóvel não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Imóvel não encontrado!");
			}

			if(!empty($locadorId)) {

				$response = (new LocadorTable())->find(['id' => $locadorId]);
				
				if(count($response) === 0) {

					throw new Exception("Locador não cadastrado!");
				}
			}

			$data = [
				'id' => $id, 
				'codigo_api' => $codigoApi, 
				'bairro' => $bairro, 
				'cidade' => $cidade, 
				'locador_id' => $locadorId
			];

			if(!empty($data) && !$this->update($data)) {

				throw new Exception("Erro ao editar imóvel!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Imóvel editado com sucesso!'
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

				throw new Exception("Imóvel não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Imóvel não encontrado!");
			}

			if(!$this->delete($id)) {

				throw new Exception("Erro ao excluir imóvel!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Imóvel excluído com sucesso!'
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