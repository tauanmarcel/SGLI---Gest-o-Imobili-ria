<?php

abstract class Connection {

	protected function conn() {

		try {

			$conn = new PDO("mysql:host=localhost;dbname=sgli;", "root", "");

			return $conn;

		} catch(PDOException $e) {

			return $e->getMessage();

		}
	}
}