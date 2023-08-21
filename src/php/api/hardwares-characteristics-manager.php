<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class HardwaresCharacteristicsManager {
	public static function CreateHardwareCharacteristic($hardware_id, $characteristic_id, $value) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			INSERT INTO hardwares_characteristics ("hardware_id", "characteristic_id", "value")
			VALUES (:hardware_id, :characteristic_id, :value)
			RETURNING id, hardware_id, characteristic_id, value
		');
		$req->execute(array('hardware_id' => $hardware_id, 'characteristic_id' => $characteristic_id, 'value' => $value));
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function ReadHardwareCharacteristic($id) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			select id, hardware_id, characteristic_id, value from hardwares_characteristics where id = :id
		');
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function UpdateHardwareCharacteristic($id, $hardware_id, $characteristic_id, $value) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from hardwares_characteristics where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		('
			update hardwares_characteristics
			set
			hardware_id = :hardware_id,
			characteristic_id = :characteristic_id,
			value = :value
			where id = :id
		');
		$req->execute
		(
			array
			(
				'id' => $id,
				'hardware_id' => $hardware_id,
				'characteristic_id' => $characteristic_id,
				'value' => $value
			)
		);

		return true;
	}

	public static function DeleteHardwareCharacteristic($id) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from hardwares_characteristics where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		("
			delete from hardwares_characteristics where id = :id
		");
		$req->execute(array('id' => $id));

		return true;
	}
}
