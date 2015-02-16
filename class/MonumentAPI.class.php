<?php

include_once('API.class.php');
include_once('MonumentDAO.class.php');

class MonumentAPI extends API {
	public function __construct($args, $method, $returnType = 0) {
		parent::__construct($args, $method, $returnType);
	}

	public function getAll() {
		$dao = new MonumentDAO();
		return $dao->findAll();
	}

	public function searchByName($name) {
		$dao = new MonumentDAO();
		$monuments = $dao->searchByName($name);

		return json_encode(array("Search" => $monuments));
	}

	public function searchByLocalization($latitude, $longitude, $offset) {
		$dao = new MonumentDAO();
		$monuments = $dao->findAll();
		$returnArray = array();

		foreach($monuments as $monument) {
			$localization = $monument->getLocalization();

			if($localization->getLatitude() < ($latitude + $offset) && $localization->getLatitude() > ($latitude - $offset) && $localization->getLongitude() < ($longitude + $offset) && $localization->getLongitude() > ($longitude - $offset)) {
				$returnArray[] = $monument;
			}
		}


		return json_encode(array("Search" => $returnArray));
	}

	public function processAPI() {
		$return = '{}';
		$args = $this->getArgs();

		if($this->getMethod() === 'GET') {
			if(isset($args['n'])) {
				$return = $this->searchByName($args['n']);
			}
			else if(isset($args['la']) && isset($args['lo'])) {
				if(isset($args['o'])) {
					$offset = $args['o'];
				}
				else {
					$offset = 1;
				}
				$return = $this->searchByLocalization($args['la'], $args['lo'], $offset);
			}
		}
		else if($this->getMethod() === 'POST') {
			$localization = new Localization(array('latitude' => $args['latitude'], 'longitude' => $args['longitude']));
			$languageDAO = new LanguageDAO();
			$language = $languageDAO->find($args['language']);
			$countryDAO = new CountryDAO($languageDAO->getConnection());
			$country = $countryDAO->find($args['country']);
			$city = new City(array('name' => $args['city'], 'country' => $country));
			$address = new Address(array('number' => $args['number'], 'street' => $args['street'], 'city' => $city));
			$monument = new Monument(array('photopath' => $args['photoPath'], 'year' => $args['year'], 'nbvisitors' => 0, 'nblikes' => 0, 'address' => $address, 'localization' => $localization));
			$characteristic = new MonumentCharacteristics(array('name' => $args['name'], 'description' => $args['description'], 'language' => $language));
			$monument->setCharacteristics(array($characteristic));
			echo 'monument dao';
			$monumentDAO = new MonumentDAO($languageDAO->getConnection());
			$monumentDAO->save($monument);
		}

		return $return;
	}
}

?>