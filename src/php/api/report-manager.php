<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class ReportManager {
	public static function GetReport() {
		$ret = array();
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		("
			select
				ungrouped_hardware.hardware_id hardware_id,
				coalesce(string_agg(ungrouped_hardware.characteristic_name, ';'), '') characteristics_names,
				coalesce(string_agg(ungrouped_hardware.characteristic_value, ';'), '') characteristics_values
			from
			(
				select
					h.id hardware_id,
					c.name characteristic_name,
					string_agg(hc.value, ', ' order by hc.value) characteristic_value
				from hardwares h
				left join hardwares_characteristics hc on h.id = hc.hardware_id
				left join characteristics c on hc.characteristic_id = c.id
				group by h.id, c.name
				order by h.id, c.name
			) ungrouped_hardware
			group by ungrouped_hardware.hardware_id
		");
		$req->execute();
		$reqResult = $req->fetchAll();
		foreach($reqResult as $result) {
			array_push
			(
				$ret,
				array
				(
					"hardware_id" => $result["hardware_id"],
					"characteristics_names" => $result["characteristics_names"],
					"characteristics_values" => $result["characteristics_values"]
				)
			);
		}
		return $ret;
	}
}
