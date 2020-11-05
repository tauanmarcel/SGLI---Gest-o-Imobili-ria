<?php

include_once "../../model/ContratoTable.php";
include_once "../../model/ImovelTable.php";
include_once "../../model/LocadorTable.php";
include_once "../../model/LocatarioTable.php";
include_once "../../model/MensalidadeTable.php";
include_once "../../model/RepasseTable.php";
include_once "../../controller/MensalidadeController.php";
include_once "../../controller/RepasseController.php";
include_once "Validator.php";

class ContratoController extends ContratoTable {

	private $validator;

	public function __construct() {

		$this->validator = new Validator();

	}

	public function index($data = []) {

		$data = $this->validator->clearData($data);

		$id            = isset($data['id'])             ? $data['id']             : '';
		$dataInicio    = isset($data['data_inicio'])    ? $data['data_inicio']    : '';
		$dataFim       = isset($data['data_fim'])       ? $data['data_fim']       : '';
		$bairro        = isset($data['bairro'])         ? $data['bairro']         : '';
		$cidade        = isset($data['cidade'])         ? $data['cidade']         : '';
		$locadorId     = isset($data['locador_id'])     ? $data['locador_id']     : '';
		$nmeLocador    = isset($data['nme_locador'])    ? $data['nme_locador']    : '';
		$locatarioId   = isset($data['locatario_id'])   ? $data['locatario_id']   : '';
		$nmeLocatario  = isset($data['nme_locatario'])  ? $data['nme_locatario']  : '';

		$data = [
			'id'             => $id,
			'data_inicio'    => $dataInicio,
			'data_fim'       => $dataFim,
			'bairro'         => $bairro,
			'cidade'         => $cidade,
			'locador_id'     => $locadorId,
			'nme_locador'    => $nmeLocador,
			'locatario_id'   => $locatarioId,
			'nme_locatario'  => $nmeLocatario,
		];

		return $this->find($data);
	}

	public function create($data) {

		$data = $this->validator->clearData($data);

		$dataInicio    = isset($data['data_inicio'])    ? $data['data_inicio']    : '';
		$dataFim       = isset($data['data_fim'])       ? $data['data_fim']       : '';
		$taxaAdmin     = isset($data['taxa_admin'])     ? $data['taxa_admin']     : '';
		$vlrAluguel    = isset($data['vlr_aluguel'])    ? $data['vlr_aluguel']    : '';
		$vlrCondominio = isset($data['vlr_condominio']) ? $data['vlr_condominio'] : '';
		$vlrIptu       = isset($data['vlr_iptu'])       ? $data['vlr_iptu']       : '';
		$imovelId      = isset($data['imovel_id'])      ? $data['imovel_id']      : '';
		$locadorId     = isset($data['locador_id'])     ? $data['locador_id']     : '';
		$locatarioId   = isset($data['locatario_id'])   ? $data['locatario_id']   : '';

		$this->conn()->beginTransaction();

		try {

			if(empty($dataInicio)) { throw new Exception("A data de início deve ser informada!"); }
			if(empty($dataFim)) { throw new Exception("A data final deve ser informada!"); }
			if(empty($taxaAdmin)) {	throw new Exception("A taxa administrativa deve ser informada!"); }
			if(empty($vlrAluguel)) { throw new Exception("O valor do aluguel deve ser informado!");	}
			if(empty($vlrCondominio)) {	throw new Exception("O valor da taxa de condomínio deve ser informada!"); }
			if(empty($vlrIptu)) { throw new Exception("O valor do IPTU deve ser informado!"); }
			if(empty($imovelId)) { throw new Exception("O imóvel deve ser informado!");	}
			if(empty($locadorId)) { throw new Exception("O locador deve ser informado!"); }
			if(empty($locatarioId)) { throw new Exception("O locatário deve ser informado!"); }

			if(!$this->validator->validateDate($dataInicio, "Y-m-d")) {

				throw new Exception("A data de início é inválida");
			}

			if(!$this->validator->validateDate($dataFim, "Y-m-d")) {

				throw new Exception("A data final é inválida");
			}

			if(strtotime($dataInicio) > strtotime($dataFim)) {
				
				throw new Exception("A data final não pode ser maior que a inicial");
			}

			$response = (new ImovelTable())->find(['id' => $imovelId]);
				
			if(count($response) === 0) {

				throw new Exception("Imóvel não cadastrado!");
			}

			$response = (new LocadorTable())->find(['id' => $locadorId]);
				
			if(count($response) === 0) {

				throw new Exception("Locador não cadastrado!");
			}

			$response = (new LocatarioTable())->find(['id' => $locatarioId]);
				
			if(count($response) === 0) {

				throw new Exception("Locatário não cadastrado!");
			}

			$data = [
				'data_inicio'    => $dataInicio,
				'data_fim'       => $dataFim,
				'taxa_admin'     => $taxaAdmin,
				'vlr_aluguel'    => $vlrAluguel,
				'vlr_condominio' => $vlrCondominio,
				'vlr_iptu'       => $vlrIptu,
				'imovel_id'      => $imovelId,
				'locador_id'     => $locadorId ,
				'locatario_id'   => $locatarioId,
			];

			if(!$this->store($data)) {

				throw new Exception("Erro ao gerar o contrato!", 1002);
			}

			$contrato = $this->find(['id' => '(SELECT MAX(id) FROM contrato)']);

			$vlrMensalidade = $vlrAluguel + $vlrIptu + $vlrCondominio;

			$vlrRepasse = $vlrAluguel + $vlrIptu;

			/* Gera as mensalidades */
			$dataMensalidade = [
				'start_date'      => $dataInicio,
				'end_date'        => $dataFim,
				'vlr_mensalidade' => $vlrMensalidade,
				'contrato_id'     => $contrato[0]['id']
			];
			
			$mensalidade = (new MensalidadeController())->create($dataMensalidade);
			
			if($mensalidade['status'] != 200) {
				throw new Exception($mensalidade['error']);
			}

			/* Gera os repasses */
			$dataRepasse = [
				'start_date'  => $dataInicio,
				'end_date'    => $dataFim,
				'vlr_repasse' => $vlrRepasse,
				'contrato_id' => $contrato[0]['id'],
				'locador_id'  => $locadorId,
			];

			$repasse = (new RepasseController())->create($dataRepasse);

			if($repasse['status'] != 200) {
				throw new Exception($repasse['error']);
			}

			return [
				'status' => 200,
				'message' => 'Contrato gerado com sucesso!'
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
		
		$id 		   = isset($data['id'])             ? $data['id']    : '';
		$dataInicio    = isset($data['data_inicio'])    ? $data['data_inicio']    : '';
		$dataFim       = isset($data['data_fim'])       ? $data['data_fim']       : '';
		$taxaAdmin     = isset($data['taxa_admin'])     ? $data['taxa_admin']     : '';
		$vlrAluguel    = isset($data['vlr_aluguel'])    ? $data['vlr_aluguel']    : '';
		$vlrCondominio = isset($data['vlr_condominio']) ? $data['vlr_condominio'] : '';
		$vlrIptu       = isset($data['vlr_iptu'])       ? $data['vlr_iptu']       : '';
		$imovelId      = isset($data['imovel_id'])      ? $data['imovel_id']      : '';
		$locadorId     = isset($data['locador_id'])     ? $data['locador_id']     : '';
		$locatarioId   = isset($data['locatario_id'])   ? $data['locatario_id']   : '';

		try {
			if(empty($id)) {

				throw new Exception("Contrato não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Contrato não encontrado!");
			}

			if(!empty($imovelId)) {

				$response = (new LocadorTable())->find(['id' => $imovelId]);
				
				if(count($response) === 0) {

					throw new Exception("Imóvel não cadastrado!");
				}
			}

			if(!empty($locadorId)) {

				$response = (new LocadorTable())->find(['id' => $locadorId]);
				
				if(count($response) === 0) {

					throw new Exception("Locador não cadastrado!");
				}
			}

			if(!empty($locatarioId)) {

				$response = (new LocatarioTable())->find(['id' => $locatarioId]);
				
				if(count($response) === 0) {

					throw new Exception("Locatario não cadastrado!");
				}
			}

			$data = [
				'id'             => $id,
				'data_inicio'    => $dataInicio,
				'data_fim'       => $dataFim,
				'taxa_admin'     => $taxaAdmin,
				'vlr_aluguel'    => $vlrAluguel,
				'vlr_condominio' => $vlrCondominio,
				'vlr_iptu'       => $vlrIptu,
				'imovel_id'      => $imovelId,
				'locador_id'     => $locadorId ,
				'locatario_id'   => $locatarioId,
			];

			if(!empty($data) && !$this->update($data)) {

				throw new Exception("Erro ao editar contrato!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Contrato atualizado com sucesso!'
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

				throw new Exception("Contrato não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Contrato não encontrado!");
			}

			if(!(new MensalidadeTable())->delete(0, $id)) {
				throw new Exception("Erro ao excluir mensalidades");
			}

			if(!(new RepasseTable())->delete(0, $id)) {
				throw new Exception("Erro ao excluir repasses");
			}

			if(!$this->delete($id)) {

				throw new Exception("Erro ao excluir contrato!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Contrato excluído com sucesso!'
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