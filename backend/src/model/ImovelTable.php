<?php 

include_once "Connection.php";

class ImovelTable extends Connection{

	public function find($data = []) {

		$imoveis = [];

		$id         = isset($data['id'])         ? $data['id']          : '';
		$codigoApi  = isset($data['codigo_api']) ? $data['codigo_api']  : '';
		$bairro     = isset($data['bairro'])     ? $data['bairro']      : '';
		$cidade     = isset($data['cidade'])     ? $data['cidade']      : '';
		$locadorId  = isset($data['locador_id']) ? $data['locador_id']  : '';
		$nmeLocador = isset($data['nme_locador'])? $data['nme_locador'] : '';

		$query = "
		SELECT 
			i.id,
		    i.codigo_api,
		    i.bairro,
		    i.cidade,
		    i.locador_id,
		    l.nome as nme_locador 
		FROM imovel i 
		INNER JOIN locador l 
			ON l.id = i.locador_id
		WHERE 1 = 1";

		if(!empty($id)) {
			$query .= " AND i.id = $id";
		}

		if(!empty($bairro)) {
			$query .= " AND i.bairro = '$bairro'";
		}

		if(!empty($cidade)) {
			$query .= " AND i.cidade = '$cidade'";
		}

		if(!empty($locadorId)) {
			$query .= " AND i.locador_id = $locadorId";
		}

		if(!empty($nmeLocador)) {
			$query .= " AND l.nome LIKE '%$nmeLocador%'";
		}

		$stmt = $this->conn()->prepare($query);

		$stmt->execute();

		while($fetch = $stmt->fetch(PDO::FETCH_ASSOC)) {

			array_push($imoveis, [
				
				'id'          => $fetch['id'],
				'codigo_api'  => $fetch['codigo_api'],
				'bairro'      => $fetch['bairro'],
				'cidade'      => $fetch['cidade'],
				'locador_id'  => $fetch['locador_id'],
				'nme_locador' => $fetch['nme_locador'],
			]);
		}

		return $imoveis;
	}

	public function store($data) {

		$codigoApi = $data['codigo_api'];
		$bairro    = $data['bairro'];
		$cidade    = $data['cidade'];
		$locadorId = $data['locador_id'];

		try {
			$query = "INSERT INTO imovel(codigo_api, bairro, cidade, locador_id) VALUES($codigoApi, '$bairro', '$cidade', $locadorId)";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao cadastrar imóvel!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function update($data) {

		$id        = $data['id'];
		$codigoApi = !empty($data['codigo_api']) ? "'" . $data['codigo_api'] . "'" : "codigo_api";
		$bairro    = !empty($data['bairro'])     ? "'" . $data['bairro']     . "'" : "bairro";
		$cidade    = !empty($data['cidade'])     ? "'" . $data['cidade']     . "'" : "cidade";
		$locadorId = !empty($data['locador_id']) ? "'" . $data['locador_id'] . "'" : "locador_id";
		$date      = date('Y-m-d H:i:s');

		try {
			$query = "UPDATE imovel SET codigo_api = $codigoApi, bairro = $bairro, cidade = $cidade, locador_id = $locadorId, updated_at = '$date' WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao editar imóvel!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function delete($id) {

		try {
			$query = "DELETE FROM imovel WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao excluir locador!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}
}