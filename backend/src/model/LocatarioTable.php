<?php 

include_once "Connection.php";

class LocatarioTable extends Connection{

	public function find($data = []) {

		$locatarios = [];

		$id    = isset($data['id'])    ? $data['id']    : '';
		$nome  = isset($data['nome'])  ? $data['nome']  : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$fone  = isset($data['fone'])  ? $data['fone']  : '';

		$query = "SELECT id, nome, email, fone FROM locatario WHERE 1 = 1";

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

		$stmt = $this->conn()->prepare($query);

		$stmt->execute();

		while($fetch = $stmt->fetch(PDO::FETCH_ASSOC)) {

			array_push($locatarios, [
				
				'id'    => $fetch['id'],
				'nome'  => $fetch['nome'],
				'email' => $fetch['email'],
				'fone'  => $fetch['fone']

			]);
		}

		return $locatarios;
	}

	public function store($data) {

		$nome  = $data['nome'];
		$email = $data['email'];
		$fone  = $data['fone'];

		try {
			$query = "INSERT INTO locatario(nome, email, fone) VALUES('$nome', '$email', '$fone')";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao cadastrar locatário!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function update($data) {

		$id    = $data['id'];
		$nome  = !empty($data['nome'])  ? "'" . $data['nome']  . "'" : "nome";
		$email = !empty($data['email']) ? "'" . $data['email'] . "'" : "email";
		$fone  = !empty($data['fone'])  ? "'" . $data['fone']  . "'" : "fone";
		
		$date  = date('Y-m-d H:i:s');

		try {
			$query = "UPDATE  locatario SET nome = $nome, email = $email, fone = $fone, updated_at = '$date' WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao editar locatário!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}

	public function delete($id) {

		try {
			$query = "DELETE FROM locatario WHERE id = $id";

			$stmt = $this->conn()->prepare($query);

			if(!$stmt->execute()) {
	
				throw new Exception("Erro ao excluir locatário!", 1001);
			}

			return true;

		} catch(PDOException $e) {

			return false;
		}
	}
}