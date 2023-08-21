<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class TablesManager {
	public static function GetHardwares() {
		$ret = array();
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		("
			select id, unique_number
			from hardwares
			order by unique_number
		");
		$req->execute();
		$reqResult = $req->fetchAll();
		foreach($reqResult as $result) {
			array_push
			(
				$ret,
				array
				(
					"id" => $result["id"],
					"unique_number" => $result["unique_number"]
				)
			);
		}
		return $ret;
	}

	public static function GetCharacteristics() {
		$ret = array();
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		("
			select id, name
			from characteristics
			order by name
		");
		$req->execute();
		$reqResult = $req->fetchAll();
		foreach($reqResult as $result) {
			array_push
			(
				$ret,
				array
				(
					"id" => $result["id"],
					"name" => $result["name"]
				)
			);
		}
		return $ret;
	}

	public static function GetHardwaresCharacteristics() {
		$ret = array();
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		("
			select hc.id id, hc.hardware_id hardware_id, hc.characteristic_id characteristic_id, hc.value \"value\"
			from hardwares_characteristics hc, hardwares h, characteristics c
			where hc.hardware_id = h.id
			and hc.characteristic_id = c.id
			order by h.id, c.name, hc.value
		");
		$req->execute();
		$reqResult = $req->fetchAll();
		foreach($reqResult as $result) {
			array_push
			(
				$ret,
				array
				(
					"id" => $result["id"],
					"hardware_id" => $result["hardware_id"],
					"characteristic_id" => $result["characteristic_id"],
					"value" => $result["value"]
				)
			);
		}
		return $ret;
	}

	public static function GetUsers() {
		$ret = array();
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		("
			select id, \"user\"
			from users
			order by \"user\"
		");
		$req->execute();
		$reqResult = $req->fetchAll();
		foreach($reqResult as $result) {
			array_push
			(
				$ret,
				array
				(
					"id" => $result["id"],
					"user" => $result["user"]
				)
			);
		}
		return $ret;
	}
}
