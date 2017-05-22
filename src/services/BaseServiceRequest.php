<?php

namespace Platron\Atol\services;

abstract class BaseServiceRequest {
    const REQUEST_URL = 'https://online.atol.ru/possystem/v3/';
    
    /**
	 * Получить url ждя запроса
	 * @return string
	 */
	abstract public function getRequestUrl();
    
    /**
	 * Получить параметры, сгенерированные командой
	 * @return array
	 */
	public function getParameters() {
		$filledvars = array();
		foreach (get_object_vars($this) as $name => $value) {
			if ($value) {
				$filledvars[$name] = (string)$value;
			}
		}

		return $filledvars;
	}
}
