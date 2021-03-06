<?php

include_once('Country.class.php');

class City implements JsonSerializable {
	private $id;
	private $name;
	private $country;

	public function __construct($data = null) {
		if(is_array($data)) {
			if(isset($data['id'])) {
				$this->setId($data['id']);
			}

			$this->setName($data['name']);

			if(isset($data['country'])) {
				if(is_array($data['country'])) {
					$country = new Country($data['country']);
				}
				else {
					$country = $data['country'];
				}
			}
			else {
				if(isset($data['co_id'])) {
					$country = new Country(array('id' => $data['co_id'], 'name' => $data['co_name']));
				}
				else {
					$country = new Country();
				}
			}
			$this->setCountry($country);
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

 	public function getCountry() {
 		return $this->country;
 	}

 	public function setCountry($country) {
 		if(is_a($country, 'Country')) {
 			$this->country = $country;
 		}
 	}

 	public function jsonSerialize() {
        return [
            'name' => $this->getName(),
            'country' => $this->getCountry()
        ];
    }
}

?>