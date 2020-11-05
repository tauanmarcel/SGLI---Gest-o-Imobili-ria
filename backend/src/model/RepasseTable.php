<?php 

include_once "Connection.php";

class RepasseTable extends Connection{

	public function find($data = []) {

		$repasses = [];

		$id          = isset($data['id'])           ? $data['id']           : '';
		$dataRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';
		$status      = isset($data['status'])       ? $data['status']       : '';
		$contratoId  = isset($data['contrato_id'])  ? $data['contrato_id']  : '';
		$ateDias     = isset($data['ate_dias'])     ? $data['ate_dias'] : '';

		$query = "
		SELECT 
			r.id,
			r.nro_repasse,
            r.data_repasse,
            date_format(r.data_repasse, '%d/%m/%Y') as parse_data_repasse,
            r.status,
			c.data_inicio,
			date_format(c.data_inicio, '%d/%m/%Y') as parse_data_inicio,
			c.data_fim,
			date_format(c.data_fim, '%d/%m/%Y') as parse_data_fim,
			r.vlr_repasse,
		    c.locador_id,
		    p.nome as nme_locador,
		    c.locatario_id,
		    l.nome as nme_locatario,
		    r.contrato_id,
		    concat(i.bairro, ',', i.cidade) as imovel
		FROM repasse r
		INNER JOIN contrato c
			ON c.id = r.contrato_id
		INNER JOIN locador p
			ON p.id = c.locador_id
		INNER JOIN locatario l
			ON l.id = c.locatario_id
		INNER JOIN imovel i
			ON i.id = c.imovel_id
		WHERE 1 = 1";

		if(!empty($id)) {
			$query .= " AND r.id = $id";
		}

		if(!empty($contratoId)) {
			$query .= " AND r.contrato_id = $contratoId";
		}

		if(!empty($dataRepasse)) {
			$query .= " AND r.data_repasse BETWEEN '$dataRepasse' AND '$dataRepasse'";
		}

		if(!empty($ateDias)) {
			$query .= " AND r.data_repasse BETWEEN '". date('Y-m-d') ."' AND '". date('Y-m-d', strtotime("+$ateDias days")) ."'";
		}

		if(!empty($status)) {
			$query .= " AND r.status = '$status'";
		}

		$stmt = $this->conn()->prepare($query);

		$stmt->execute();

		while($fetch = $stmt->fetch(PDO::FETCH_ASSOC)) {

			array_push($repasses, [
				'id'                 => $fetch['id'],
				'nro_repasse'        => $fetch['nro_repasse'],
				'data_repasse'       => $fetch['data_repasse'],
				'parse_data_repasse' => $fetch['parse_data_repasse'],
				'status'             => $fetch['status'],
				'data_inicio'        => $fetch['data_inicio'],
				'parse_data_inicio'  => $fetch['parse_data_inicio'],
				'data_fim'           => $fetch['data_fim'],
				'parse_data_fim'     => $fetch['parse_data_fim'],
				'vlr_repasse'        => $fetch['vlr_repasse'],
				'locador_id'         => $fetch['locador_id'],
				'nme_locador'        => $fetch['nme_locador'],
				'locatario_id'       => $fetch['locatario_id'],
				'nme_locatario'      => $fetch['nme_locatario'],
				'contrato_id'        => $fetch['contrato_id'],
				'imovel'             => $fetch['imovel'],
			]);
		}

		return $repasses;
	}

	public function store($data) {

		$contratoId         = $data['contrato_id'];
		$qtdRepasse         = $data['qtd_repasse'];
		$vlrRepasse         = $data['vlr_repasse'];
		$vlrPrimeiroRepasse = $data['vlr_repasse_um'];
		$startDate          = $data['start_date'];
		$diaRepasse         = $data['dia_repasse'];

		try {

			$ano = date('Y', strtotime($startDate));
			$mes = date('m', strtotime($startDate)) + 1;

			$query = "
			INSERT INTO repasse (
				nro_repasse, 
				data_repasse, 
				vlr_repasse, 
				contrato_id
			)
			VALUES\n";

			for($i = 0; $i < $qtdRepasse; $i++) {

				$valor = $i > 0 ? $vlrRepasse : $vlrPrimeiroRepasse;

				if($mes > 12) {
					$mes = 1;
					++$ano;
				}

				$vencimento = ($ano) . "-" . str_pad($mes++, 2, 0, STR_PAD_LEFT) . "-" . str_pad($diaRepasse, 2, 0, STR_PAD_LEFT);

				$query .= "(" . ($i+1) . ", '$vencimento', $valor, $contratoId),\n";
			}

			$query = rtrim($query, ",\n");

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {

				throw new Exception("Erro ao gerar repasse!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function update($data) {

		$id          = $data['id'];
		$dataRepasse = !empty($data['data_repasse']) ? "'" . $data['data_repasse'] . "'" : "data_repasse";
		$vlrRepasse  = !empty($data['vlr_repasse'])  ? "'" . $data['vlr_repasse']  . "'" : "vlr_repasse";
		$status      = !empty($data['status'])       ? "'" . $data['status']       . "'" : "status";
		$contratoId  = $data['contrato_id'];

		$date  = date('Y-m-d H:i:s');

		try {
			$query = "
			UPDATE repasse SET 
				data_repasse = $dataRepasse, 
				vlr_repasse = $vlrRepasse, 
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
			$query = "DELETE FROM repasse WHERE 1 = 1";

			if(!empty($contratoId)) {
				$query .= " AND contrato_id = $contratoId";
			}else{
				$query .= " AND id = $id";
			}

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao excluir a repasse!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}
}