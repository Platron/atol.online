<?php

namespace Platron\Atol\data_objects;

abstract class BaseDataObject {
    /**
	 * Получить параметры, сгенерированные командой
	 * @return array
	 */
	public function getParameters() {
		$filledvars = array();
		foreach (get_object_vars($this) as $name => $value) {
			if ($value) {
				$filledvars[$name] = $value;
			}
		}

		return $filledvars;
	}
}
