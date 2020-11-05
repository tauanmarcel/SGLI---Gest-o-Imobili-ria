<?php

include_once "../../model/RepasseTable.php";
include_once "../../model/ContratoTable.php";
include_once "../../model/LocadorTable.php";
include_once "Validator.php";

class RepasseController extends RepasseTable {

	private $validator;

	public function __construct() {

		$this->validator = new Validator();

	}

	public function index($data = []) {

		$data = $this->validator->clearData($data);

		$id          = isset($data['id'])           ? $data['id']           : '';
		$dataRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';
		$status      = isset($data['status'])       ? $data['status']       : '';
		$contratoId  = isset($data['contrato_id'])  ? $data['contrato_id']  : '';
		$ateDias        = isset($data['ate_dias'])        ? $data['ate_dias'] : '';

		$data = [
			'id'           => $id,
			'data_repasse' => $dataRepasse,
			'status'       => $status,
			'contrato_id'  => $contratoId,
			'ate_dias'        => $ateDias,
		];

		return $this->find($data);
	}

	public function create($data) {

		$startDate  = $data['start_date'];
		$endDate    = $data['end_date'];
		$vlrRepasse = $data['vlr_repasse'];
		$contratoId = $data['contrato_id'];
		$locadorId  = $data['locador_id'];

		try {

			$parcelas = $this->gerarParcelas($startDate, $endDate, $vlrRepasse);

			$locador = (new LocadorTable())->find(['id' => $locadorId]);
			$diaRepasse = $locador[0]['dia_repasse'];

			$data = [
				'start_date'     => $startDate,
				'end_date'       => $endDate,
				'dia_repasse'    => $diaRepasse,
				'qtd_repasse'    => $parcelas['qtd_repasse'],
				'vlr_repasse'    => $vlrRepasse,
				'vlr_repasse_um' => $parcelas['vlr_repasse_um'],
				'contrato_id'    => $contratoId,
			];

			if(!$this->store($data)) {

				throw new Exception("Erro ao gerar repasse!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Repasse gerado com sucesso!'
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'error' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	public function edit($id, $contratoId, $data) {

		$data['id'] = $id;
		$data['contrato_id'] = $contratoId;

		$data = $this->validator->clearData($data);
		
		$id          = isset($data['id'])           ? $data['id']           : '';
		$dataRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';
		$vlrRepasse  = isset($data['vlr_repasse'])  ? $data['vlr_repasse']  : '';
		$status      = isset($data['status'])       ? trim($data['status']) : '';
		$contratoId  = isset($data['contrato_id'])  ? $data['contrato_id']  : '';

		try {
			if(empty($id) && empty($contratoId)) {

				throw new Exception("Repasse não informada!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Repasse não encontrado!");
			}

			if(!empty($contratoId)) {

				$response = (new ContratoTable())->find(['id' => $contratoId]);
				
				if(count($response) === 0) {

					throw new Exception("Contrato não cadastrado!");
				}
			}

			if(!empty($status) && $status != 'NÃO REALIZADO' && $status != 'REALIZADO') {

				throw new Exception("O status do repasse deve ser REALIZADO ou NÃO REALIZADO!");
			}

			$data = [
				'id'           => $id,
				'data_repasse' => $dataRepasse,
				'vlr_repasse'  => $vlrRepasse,
				'status'       => $status,
				'contrato_id'  => $contratoId,
			];

			if(!empty($data) && !$this->update($data)) {

				throw new Exception("Erro ao editar repasse!", 1002);
			}

			$mensagem = !empty($data) ? 'Repasse atualizado com sucesso!' : "Nenhuma alteração realizada!";

			return [
				'status' => 200,
				'message' => $mensagem
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'error' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	public function remove($id, $contratoId) {

		$data = $this->validator->clearData(['id' => $id, 'contrato_id' => $contratoId]);

		$id         = $data['id'];
		$contratoId = $data['contrato_id'];

		try {

			if(empty($id) && empty($contratoId)) {

				throw new Exception("Repasse não informado!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Repasse não encontrado!");
			}

			if(!$this->delete($id, $contratoId)) {

				throw new Exception("Erro ao excluir repasse!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Repasse excluído com sucesso!'
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'error' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}

	private function gerarParcelas($startDate, $endDate, $vlrRepasse) {

		$objStartDate = new DateTime($startDate);
		$objEndDate   = new DateTime($endDate);

		$vlrPrimeiroRepasse = $vlrRepasse;

		$diff = $objStartDate->diff($objEndDate);

		$qtdYear  = $diff->y;
		$qtdMonth = $diff->m;
		$qtdDays  = $diff->d;

		$qtdRepasse = $qtdMonth;

		$qtdRepasse += $qtdYear > 0 ? ($qtdYear * 12) : 0;
		$qtdRepasse += $qtdDays > 0 ? 1 : 0;

		$diaInicial = date('d', strtotime($startDate));

		/* Estou considerando todos os meses como 30 dias para obter o valor diário da mensalidade*/
		
		if($diaInicial >= 28 && date('m', strtotime($startDate)) == 2) {
			$diaInicial = 30;
		}

		if($diaInicial != 1) {
			$vlrDiario = $vlrRepasse / 30;
			$vlrPrimeiroRepasse = $vlrDiario * (30 - ($diaInicial > 30 ? 30 : $diaInicial));
		}

		return [
			'qtd_repasse' => $qtdRepasse,
			'vlr_repasse_um' => $vlrPrimeiroRepasse
		];
	}
}