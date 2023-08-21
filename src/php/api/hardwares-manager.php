<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class HardwaresManager {
	public static function CreateHardware() {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare('insert into hardwares default values returning id, unique_number');
		$req->execute(array());
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function ReadHardware($id) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			select id, unique_number from hardwares where id = :id
		');
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function DeleteHardware($id) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from hardwares where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		("
			delete from hardwares where id = :id
		");
		$req->execute(array('id' => $id));

		return true;
	}
}
