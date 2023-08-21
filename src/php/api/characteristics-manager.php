<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class CharacteristicsManager {
	public static function CreateCharacteristic($name) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			INSERT INTO characteristics ("name")
			VALUES (:name)
		');
		$req->execute(array('name' => $name));
		$reqResult = $req->fetchAll();
		return true;
	}

	public static function ReadCharacteristic($id) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			select id, "name" from characteristics where id = :id
		');
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function UpdateCharacteristic($id, $name) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from characteristics where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		('
			update characteristics
			set
			"name" = :name
			where id = :id
		');
		$req->execute
		(
			array
			(
				'id' => $id,
				'name' => $name
			)
		);

		return true;
	}

	public static function DeleteCharacteristic($id) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from characteristics where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		("
			delete from characteristics where id = :id
		");
		$req->execute(array('id' => $id));

		return true;
	}
}
