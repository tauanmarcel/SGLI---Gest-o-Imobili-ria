<?php

class Validator {

	public function validateEmail($email) {

		return filter_var($email, FILTER_VALIDATE_EMAIL);

	}

	public function validatePhone($phone) {
	    
	    $phone = preg_replace('/[()]/', '', $phone);

	    $regexPhone = "/^(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))$/";

	    return preg_match($regexPhone, $phone);
	}

	public function clearData($data = []) {

		$parseData = [];

		foreach($data as $k => $d) {
			$parseData[$k] = preg_replace("/(\-\-)|\_|\<|\>|\/\|\\/|\"|script|truncate|from|select|insert|delete|where|drop|table|grant|[()]|\'/", "", $d);

			if($k == 'phone' || $k == 'fone' || $k == 'tel' || $k == 'telefone') {
				$parseData[$k] = str_replace("-", "", str_replace(" ", "", $parseData[$k]));
			}
		}

		return $parseData;
	}

	public function validateDate($date, $format = 'Y-m-d H:i:s') {
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
}