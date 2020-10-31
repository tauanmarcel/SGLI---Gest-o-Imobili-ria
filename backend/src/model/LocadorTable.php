<?php 

include_once "Connection.php";

class LocadorTable extends Connection{

	public function find($data = []) {

		$locadores = [];

		$id       = isset($data['id'])          ? $data['id']          : '';
		$nome     = isset($data['nome'])        ? $data['nome']        : '';
		$email    = isset($data['email'])       ? $data['email']       : '';
		$fone     = isset($data['fone'])        ? $data['fone']        : '';
		$dtRepasse = isset($data['data_repasse']) ? $data['data_repasse'] : '';

		$query = "SELECT id, nome, email, fone, data_repasse FROM locador WHERE 1 = 1";

		if(!empty($id)) {
			$query .= " AND id = $id";
		}

		if(!empty($nome)) {
			$query .= " AND nome LIKE '%$nome%'";
		}

		if(!empty($email)) {
			$query .= " AND email = '$email'";
		}

		if(!empty($fone)) {
			$query .= " AND fone = '$fone'";
		}

		if(!empty($dtRepasse)) {
			$query .= " AND dt_repass BETWEEN '$dtRepasse' AND '$dtRepasse'";
		}

		$stmt = $this->conn()->prepare($query);

		$stmt->execute();

		while($fetch = $stmt->fetch(PDO::FETCH_ASSOC)) {

			array_push($locadores, [
				'id'           => $fetch['id'],
				'nome'         => $fetch['nome'],
				'email'        => $fetch['email'],
				'fone'         => $fetch['fone'],
				'data_repasse' => $fetch['data_repasse'],
			]);
		}

		return $locadores;
	}

	public function store($data) {

		$nome      = $data['nome'];
		$email     = $data['email'];
		$fone      = $data['fone'];
		$dtRepasse = $data['data_repasse'];

		try {
			$query = "INSERT INTO locador(nome, email, fone, data_repasse) VALUES('$nome', '$email', '$fone', '$dtRepasse')";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao cadastrar locador!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function update($data) {

		$id        = $data['id'];
		$nome      = !empty($data['nome'])         ? "'" . $data['nome']         . "'" : "nome";
		$email     = !empty($data['email'])        ? "'" . $data['email']        . "'" : "email";
		$fone      = !empty($data['fone'])         ? "'" . $data['fone']         . "'" : "fone";
		$dtRepasse = !empty($data['data_repasse']) ? "'" . $data['data_repasse'] . "'" : "data_repasse";
		$date      = date('Y-m-d H:i:s');

		try {
			$query = "UPDATE  locador SET nome = $nome, email = $email, fone = $fone, data_repasse = $dtRepasse, updated_at = '$date' WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao editar locador!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function delete($id) {

		try {
			$query = "DELETE FROM locador WHERE id = $id";

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