<?php 

include_once "Connection.php";

class MensalidadeTable extends Connection{

	public function find($data = []) {

		$mensalidades = [];

		$id             = isset($data['id'])              ? $data['id']              : '';
		$dataVencimento = isset($data['data_vencimento']) ? $data['data_vencimento'] : '';
		$status         = isset($data['status'])          ? $data['status']          : '';
		$contratoId     = isset($data['contrato_id'])     ? $data['contrato_id']     : '';

		$query = "
		SELECT 
			m.id,
			m.nro_mensalidade,
            m.data_vencimento,
            date_format(m.data_vencimento, '%d/%m/%Y') as parse_data_vencimento,
            m.status,
			c.data_inicio,
			date_format(c.data_inicio, '%d/%m/%Y') as parse_data_inicio,
			c.data_fim,
			date_format(c.data_fim, '%d/%m/%Y') as parse_data_fim,
			m.vlr_mensalidade,
		    c.locador_id,
		    p.nome as nme_locador,
		    c.locatario_id,
		    l.nome as nme_locatario,
		    m.contrato_id
		FROM mensalidade m
		INNER JOIN contrato c
			ON c.id = m.contrato_id
		INNER JOIN locador p
			ON p.id = c.locador_id
		INNER JOIN locatario l
			ON l.id = c.locatario_id
		WHERE 1 = 1";


		if(!empty($id)) {
			$query .= " AND m.id = $id";
		}

		if(!empty($contratoId)) {
			$query .= " AND m.contrato_id = $contratoId";
		}

		if(!empty($dataVencimento)) {
			$query .= " AND m.data_vencimento BETWEEN '$dataVencimento' AND '$dataVencimento'";
		}

		if(!empty($status)) {
			$query .= " AND m.status = '$status'";
		}

		$stmt = $this->conn()->prepare($query);

		$stmt->execute();

		while($fetch = $stmt->fetch(PDO::FETCH_ASSOC)) {

			array_push($mensalidades, [
				'id'                    => $fetch['id'],
				'nro_mensalidade'       => $fetch['nro_mensalidade'],
				'data_vencimento'       => $fetch['data_vencimento'],
				'parse_data_vencimento' => $fetch['parse_data_vencimento'],
				'status'                => $fetch['status'],
				'data_inicio'           => $fetch['data_inicio'],
				'parse_data_inicio'     => $fetch['parse_data_inicio'],
				'data_fim'              => $fetch['data_fim'],
				'parse_data_fim'        => $fetch['parse_data_fim'],
				'vlr_mensalidade'       => $fetch['vlr_mensalidade'],
				'locador_id'            => $fetch['locador_id'],
				'nme_locador'           => $fetch['nme_locador'],
				'locatario_id'          => $fetch['locatario_id'],
				'nme_locatario'         => $fetch['nme_locatario'],
				'contrato_id'           => $fetch['contrato_id'],
			]);
		}

		return $mensalidades;
	}

	public function store($data) {

		$contratoId         = $data['contrato_id'];
		$qtdMensalidade     = $data['qtd_mensalidade'];
		$vlrMensalidade     = $data['vlr_mensalidade'];
		$vlrPrimeiraParcela = $data['vlr_primeira_parcela'];
		$startDate          = $data['start_date'];

		try {

			$ano = date('Y', strtotime($startDate));
			$mes = date('m', strtotime($startDate)) + 1;

			$query = "
			INSERT INTO mensalidade (
				nro_mensalidade, 
				data_vencimento, 
				vlr_mensalidade, 
				contrato_id
			)
			VALUES\n";

			for($i = 0; $i < $qtdMensalidade; $i++) {

				$valor = $i > 0 ? $vlrMensalidade : $vlrPrimeiraParcela;

				if($mes > 12) {
					$mes = 1;
					++$ano;
				}

				$vencimento = ($ano) . "-" . str_pad($mes++, 2, 0, STR_PAD_LEFT) . "-" . '01';

				$query .= "(" . ($i+1) . ", '$vencimento', $valor, $contratoId),\n";

			}

			$query = rtrim($query, ",\n");

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {

				throw new Exception("Erro ao gerar mensalidade!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function update($data) {

		$id    = $data['id'];
		$dataVencimento = !empty($data['data_vencimento']) ? "'" . $data['data_vencimento'] . "'" : "data_vencimento";
		$vlrMensalidade = !empty($data['vlr_mensalidade']) ? "'" . $data['vlr_mensalidade'] . "'" : "vlr_mensalidade";
		$status         = !empty($data['status'])          ? "'" . $data['status']          . "'" : "status";
		$contratoId     = $data['contrato_id'];

		$date  = date('Y-m-d H:i:s');

		try {
			$query = "
			UPDATE  mensalidade SET 
				data_vencimento = $dataVencimento, 
				vlr_mensalidade = $vlrMensalidade, 
				status = $status, 
				updated_at = '$date'
			WHERE 1 = 1";

			if(!empty($contratoId)) {
				$query .= " AND contrato_id = $contratoId";
			}else{
				$query .= " AND id = $id";
			}

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao atualizar mensalidade!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function delete($id, $contratoId) {

		try {
			$query = "DELETE FROM mensalidade WHERE 1 = 1";

			if(!empty($contratoId)) {
				$query .= " AND contrato_id = $contratoId";
			}else{
				$query .= " AND id = $id";
			}

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao excluir a mensalidade!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}
}