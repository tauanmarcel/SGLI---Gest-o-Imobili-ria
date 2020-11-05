<?php 

include_once "Connection.php";

class ContratoTable extends Connection{

	public function find($data = []) {

		$contratos = [];

		$id           = isset($data['id'])            ? $data['id']            : '';
		$dataInicio   = isset($data['data_inicio'])   ? $data['data_inicio']   : '';
		$dataFim      = isset($data['data_fim'])      ? $data['data_fim']      : '';
		$bairro       = isset($data['bairro'])        ? $data['bairro']        : '';
		$cidade       = isset($data['cidade'])        ? $data['cidade']        : '';
		$locadorId    = isset($data['locador_id'])    ? $data['locador_id']    : '';
		$nmeLocador   = isset($data['nme_locador'])   ? $data['nme_locador']   : '';
		$locatarioId  = isset($data['locatario_id'])  ? $data['locatario_id']  : '';
		$nmeLocatario = isset($data['nme_locatario']) ? $data['nme_locatario'] : '';

		$query = "
		SELECT 
			c.id,
			c.data_inicio,
			date_format(c.data_inicio, '%d/%m/%Y') as parse_data_inicio,
			c.data_fim,
			date_format(c.data_fim, '%d/%m/%Y') as parse_data_fim,
			c.taxa_admin,
			c.vlr_aluguel,
			c.vlr_condominio,
			c.vlr_iptu,
			i.codigo_api,
		    concat(i.bairro, ', ', i.cidade) as imovel,
		    c.locador_id,
		    p.nome as nme_locador,
		    c.locatario_id,
		    l.nome as nme_locatario
		FROM contrato c
		INNER JOIN imovel i
			ON i.id = c.imovel_id
		INNER JOIN locador p
			ON p.id = c.locador_id
		INNER JOIN locatario l
			ON l.id = c.locatario_id
		WHERE 1 = 1";

		if(!empty($id)) {
			$query .= " AND c.id = $id";
		}

		if(!empty($dataInicio)) {
			$query .= " AND c.data_inicio BETWEEN '$dataInicio' AND '$dataInicio'";
		}

		if(!empty($dataFim)) {
			$query .= " AND c.data_inicio BETWEEN '$dataFim' AND '$dataFim'";
		}

		if(!empty($bairro)) {
			$query .= " AND i.bairro LIKE '%$bairro%'";
		}

		if(!empty($cidade)) {
			$query .= " AND i.cidade LIKE '%$cidade%'";
		}

		if(!empty($locadorId)) {
			$query .= " AND c.locador_id = $locadorId";
		}

		if(!empty($nmeLocador)) {
			$query .= " AND p.nome LIKE '%$nmeLocador%'";
		}

		if(!empty($locatarioId)) {
			$query .= " AND c.locatario_id = $locatarioId";
		}

		if(!empty($nmeLocatario)) {
			$query .= " AND l.nome LIKE '%$nmeLocatario%'";
		}

		$stmt = $this->conn()->prepare($query);

		$stmt->execute();

		while($fetch = $stmt->fetch(PDO::FETCH_ASSOC)) {

			array_push($contratos, [
				'id'                => $fetch['id'],
				'data_inicio'       => $fetch['data_inicio'],
				'parse_data_inicio' => $fetch['parse_data_inicio'],
				'data_fim'          => $fetch['data_fim'],
				'parse_data_fim'    => $fetch['parse_data_fim'],
				'taxa_admin'        => $fetch['taxa_admin'],
				'vlr_aluguel'       => $fetch['vlr_aluguel'],
				'vlr_condominio'    => $fetch['vlr_condominio'],
				'vlr_iptu'          => $fetch['vlr_iptu'],
				'codigo_api'        => $fetch['codigo_api'],
				'imovel'            => $fetch['imovel'],
				'locador_id'        => $fetch['locador_id'],
				'nme_locador'       => $fetch['nme_locador'],
				'locatario_id'      => $fetch['locatario_id'],
				'nme_locatario'     => $fetch['nme_locatario'],
			]);
		}

		return $contratos;
	}

	public function store($data) {

		$dataInicio    = $data['data_inicio'];
		$dataFim       = $data['data_fim'];
		$taxaAdmin     = $data['taxa_admin'];
		$vlrAluguel    = $data['vlr_aluguel'];
		$vlrCondominio = $data['vlr_condominio'];
		$vlrIptu       = $data['vlr_iptu'];
		$imovelId      = $data['imovel_id'];
		$locadorId     = $data['locador_id'];
		$locatarioId   = $data['locatario_id'];

		try {
			$query = "
			INSERT INTO contrato (
				data_inicio, 
				data_fim, 
				taxa_admin, 
				vlr_aluguel,
				vlr_condominio,
				vlr_iptu,
				imovel_id,
				locador_id,
				locatario_id
			) VALUES (
				'$dataInicio', 
				'$dataFim', 
				$taxaAdmin,
				$vlrAluguel,
				$vlrCondominio,
				$vlrIptu,
				$imovelId,
				$locadorId,
				$locatarioId
			)";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {

				throw new Exception("Erro ao adicionar contrato!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function update($data) {

		$id            = $data['id'];
		$dataInicio    = !empty($data['data_inicio'])    ? "'" . $data['data_inicio']    . "'" : "data_inicio";
		$dataFim       = !empty($data['data_fim'])       ? "'" . $data['data_fim']       . "'" : "data_fim";
		$taxaAdmin     = !empty($data['taxa_admin'])     ? "'" . $data['taxa_admin']     . "'" : "taxa_admin";
		$vlrAluguel    = !empty($data['vlr_aluguel'])    ? "'" . $data['vlr_aluguel']    . "'" : "vlr_aluguel";
		$vlrCondominio = !empty($data['vlr_condominio']) ? "'" . $data['vlr_condominio'] . "'" : "vlr_condominio";
		$vlrIptu       = !empty($data['vlr_iptu'])       ? "'" . $data['vlr_iptu']       . "'" : "vlr_iptu";
		$imovelId      = !empty($data['imovel_id'])      ? "'" . $data['imovel_id']      . "'" : "imovel_id";
		$locadorId     = !empty($data['locador_id'])     ? "'" . $data['locador_id']     . "'" : "locador_id";
		$locatarioId   = !empty($data['locatario_id'])   ? "'" . $data['locatario_id']   . "'" : "locatario_id";

		$date      = date('Y-m-d H:i:s');

		try {
			$query = "
			UPDATE contrato SET 
				data_inicio    = $dataInicio,
				data_fim       = $dataFim,
				taxa_admin     = $taxaAdmin,
				vlr_aluguel    = $vlrAluguel,
				vlr_condominio = $vlrCondominio,
				vlr_iptu       = $vlrIptu,
				imovel_id      = $imovelId,
				locador_id     = $locadorId,
				locatario_id   = $locatarioId,
				updated_at     = '$date'
			WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao editar contrato!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function delete($id) {

		try {
			$query = "DELETE FROM contrato WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao excluir contrato!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}
}