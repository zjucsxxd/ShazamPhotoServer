<?php

class Language implements JsonSerializable {
	private $id;
	private $name;
	private $value;

	public function __construct($data = null) {
		if(is_array($data)) {
			if(isset($data['id'])) {
				$this->setId($data['id']);
			}

			$this->setName($data['name']);
			$this->setValue($data['value']);
		}
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		if(is_numeric($id)) {
			$this->id = $id;
		}
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		if(is_string($name)) {
			$this->name = $name;
		}
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		if(is_string($value)) {
			$this->value = $value;
		}
	}

	public function jsonSerialize() {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue()
        ];
    }
}

?>