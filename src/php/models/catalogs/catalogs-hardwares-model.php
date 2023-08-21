<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";
require_once "$rootpath/php/api/auth-manager.php";
require_once "$rootpath/php/api/tables-manager.php";
require_once "$rootpath/php/api/users-manager.php";
require_once "$rootpath/php/api/hardwares-manager.php";
require_once "$rootpath/php/api/characteristics-manager.php";
require_once "$rootpath/php/api/hardwares-characteristics-manager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

switch ($action) {
	case "read":
		ReadAction();
		break;
	case "create":
		CreateAction();
		break;
	case "update":
		UpdateAction();
		break;
	case "delete":
		DeleteAction();
		break;
	case "create_hardware_characteristic":
		CreateHardwareCharacteristicAction();
		break;
	case "update_hardware_characteristic":
		UpdateHardwareCharacteristicAction();
		break;
	case "delete_hardware_characteristic":
		DeleteHardwareCharacteristicAction();
		break;
	default:
		DefaultAction();
		break;
}

function ReadAction() {
	$data = json_decode(file_get_contents("php://input"));

	$response = array();
	$response["action"] = "read";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
			$response["status"] = "success";
			$response["message"] = 'Получен список оборудования.';
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function CreateAction() {
	$data = json_decode(file_get_contents("php://input"));

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();
	$response["hardware"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$hardware = HardwaresManager::CreateHardware();

			$response["status"] = "success";
			$response["message"] = 'Добавлена новая единица оборудования.';
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
			$response["hardware"] = $hardware;
			
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function UpdateAction() {
	$data = json_decode(file_get_contents("php://input"));

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$response["status"] = "success";
			$response["message"] = 'Оборудование обновлено.';
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function DeleteAction() {
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->hardware->id;

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$result = HardwaresManager::DeleteHardware($id);
			if ($result) {
				$response["status"] = "success";
				$response["message"] = 'Оборудование удалено.';
			} else {
				$response["status"] = "error";
				$response["message"] = 'Оборудование не удалено.';
			}
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function CreateHardwareCharacteristicAction() {
	$data = json_decode(file_get_contents("php://input"));
	$hardware_id = $data->hardware_characteristic->hardware_id;
	$characteristic_id = $data->hardware_characteristic->characteristic_id;
	$value = $data->hardware_characteristic->value;

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();
	$response["hardware_characteristic"] = array();

	if($characteristic_id == "") {
		$response["status"] = "error";
		$response["message"] = "Необходимо выбрать характеристику.";
		echo json_encode($response);
		return;
	}

	if (strpos($value, ';') !== false) {
		$response["status"] = "error";
		$response["message"] = "Невозможно сохранить значение характеристики, содержащее зарезервированный символ ';'.";
		echo json_encode($response);
		return;
	}

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$hardware_characteristic = HardwaresCharacteristicsManager::CreateHardwareCharacteristic($hardware_id, $characteristic_id, $value);

			$response["status"] = "success";
			$response["message"] = 'Добавлена характеристика оборудования.';
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
			$response["hardware_characteristic"] = $hardware_characteristic;
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function UpdateHardwareCharacteristicAction() {
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->hardware_characteristic->id;
	$hardware_id = $data->hardware_characteristic->hardware_id;
	$characteristic_id = $data->hardware_characteristic->characteristic_id;
	$value = $data->hardware_characteristic->value;

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();

	if (strpos($value, ';') !== false) {
		$response["status"] = "error";
		$response["message"] = "Невозможно сохранить значение характеристики, содержащее зарезервированный символ ';'.";
		echo json_encode($response);
		return;
	}

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			HardwaresCharacteristicsManager::UpdateHardwareCharacteristic($id, $hardware_id, $characteristic_id, $value);
			$response["status"] = "success";
			$response["message"] = 'Характеристика обновлена.';
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function DeleteHardwareCharacteristicAction() {
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->hardware_characteristic->id;

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["hardwares"] = array();
	$response["characteristics"] = array();
	$response["hardwares_characteristics"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$result = HardwaresCharacteristicsManager::DeleteHardwareCharacteristic($id);
			if ($result) {
				$response["status"] = "success";
				$response["message"] = 'Характеристика удалена.';
			} else {
				$response["status"] = "error";
				$response["message"] = 'Характеристика не удалена.';
			}
			$response["hardwares"] = TablesManager::GetHardwares();
			$response["characteristics"] = TablesManager::GetCharacteristics();
			$response["hardwares_characteristics"] = TablesManager::GetHardwaresCharacteristics();
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не авторизован.";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена. " . $e->getMessage();
		DatabaseManager::Rollback();
	}

	echo json_encode($response);
}

function DefaultAction() {
	$response = array();
	$response["action"] = "default";
	$response["status"] = "error";
	$response["message"] = "Действие не найдено.";
	echo json_encode($response);
}
