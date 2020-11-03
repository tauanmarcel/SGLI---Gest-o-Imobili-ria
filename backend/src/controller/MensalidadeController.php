<?php

include_once "../../model/MensalidadeTable.php";
include_once "../../model/ContratoTable.php";
include_once "Validator.php";

class MensalidadeController extends MensalidadeTable {

	private $validator;

	public function __construct() {

		$this->validator = new Validator();

	}

	public function index($data = []) {

		$data = $this->validator->clearData($data);

		$id             = isset($data['id'])              ? $data['id']              : '';
		$dataVencimento = isset($data['data_vencimento']) ? $data['data_vencimento'] : '';
		$status         = isset($data['status'])          ? $data['status']          : '';
		$contratoId     = isset($data['contrato_id'])     ? $data['contrato_id']     : '';

		$data = [
			'id'              => $id,
			'data_vencimento' => $dataVencimento,
			'status'          => $status,
			'contrato_id'     => $contratoId,
		];

		return $this->find($data);
	}

	public function create($startDate, $endDate, $vlrMensalidade, $contratoId) {

		try {

			$objStartDate = new DateTime($startDate);
			$objEndDate   = new DateTime($endDate);

			$vlrPrimeiraParcela = $vlrMensalidade;

			$diff = $objStartDate->diff($objEndDate);

			$qtdYear  = $diff->y;
			$qtdMonth = $diff->m;
			$qtdDays  = $diff->d;

			$qtdMensalidade = $qtdMonth;

			$qtdMensalidade += $qtdYear > 0 ? ($qtdYear * 12) : 0;
			$qtdMensalidade += $qtdDays > 0 ? 1 : 0;

			$diaInicial = date('d', strtotime($startDate));

			/* Estou considerando todos os meses como 30 dias para obter o valor diário da mensalidade*/
			
			if($diaInicial >= 28 && date('m', strtotime($startDate)) == 2) {
				$diaInicial = 30;
			}

			if($diaInicial != 1) {
				$vlrDiario = $vlrMensalidade / 30;
				$vlrPrimeiraParcela = $vlrDiario * (30 - ($diaInicial > 30 ? 30 : $diaInicial));
			}

			$data['qtd_mensalidade']      = $qtdMensalidade;
			$data['vlr_mensalidade']      = $vlrMensalidade;
			$data['vlr_primeira_parcela'] = $vlrPrimeiraParcela;
			$data['start_date']           = $startDate;
			$data['end_date']             = $endDate;
			$data['contrato_id']          = $contratoId;

			if(!$this->store($data)) {

				throw new Exception("Erro ao gerar mensalidades!", 1002);
			}

			return [
				'status' => 200,
				'message' => 'Mensalidade gerada com sucesso!'
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
		
		$id             = isset($data['id'])              ? $data['id']              : '';
		$dataVencimento = isset($data['data_vencimento']) ? $data['data_vencimento'] : '';
		$vlrMensalidade = isset($data['vlr_mensalidade']) ? $data['vlr_mensalidade'] : '';
		$status         = isset($data['status'])          ? trim($data['status'])    : '';
		$contratoId     = isset($data['contrato_id'])     ? $data['contrato_id']     : '';

		try {
			if(empty($id) && empty($contratoId)) {

				throw new Exception("Mensalidade não informada!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Mensalidade não encontrada!");
			}

			if(!empty($contratoId)) {

				$response = (new ContratoTable())->find(['id' => $contratoId]);
				
				if(count($response) === 0) {

					throw new Exception("Contrato não cadastrado!");
				}
			}

			if(!empty($status) && $status != 'NÃO PAGA' && $status != 'PAGA') {

				throw new Exception("O status da mensalidade deve ser PAGA ou NÃO PAGA!");
			}

			$data = [
				'id'              => $id,
				'data_vencimento' => $dataVencimento,
				'vlr_mensalidade' => $vlrMensalidade,
				'status'          => $status,
				'contrato_id'     => $contratoId,
			];

			if(!empty($data) && !$this->update($data)) {

				throw new Exception("Erro ao editar mensalidade!", 1002);
			}

			$mensagem = !empty($data) ? 'Mensalidade atualizada com sucesso!' : "Nenhuma alteração realizada!";

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

	public function remove($id) {

		$data = $this->validator->clearData(['id' => $id]);

		$id = $data['id'];

		try {
			if(empty($id)) {

				throw new Exception("Mensalidade não informada!");
			}

			$response = $this->find(['id' => $id]);

			if(count($response) === 0) {

				throw new Exception("Mensalidade não encontrada!");
			}

			if(!$this->delete($id)) {

				throw new Exception("Erro ao excluir mensalidade!", 1002);
			}

			return [
				'status' => 200,
				'mensagem' => 'Mensalidade excluída com sucesso!'
			];

		} catch(Exception $e) {

			return [
				'status' => 400,
				'mensagem' => $e->getMessage(),
				'cod_error' => $e->getCode()
			];
		}
	}
}