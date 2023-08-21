<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";
require_once "$rootpath/php/api/auth-manager.php";
require_once "$rootpath/php/api/tables-manager.php";
require_once "$rootpath/php/api/users-manager.php";
require_once "$rootpath/php/api/hardwares-manager.php";
require_once "$rootpath/php/api/characteristics-manager.php";
require_once "$rootpath/php/api/hardwares-characteristics-manager.php";
require_once "$rootpath/php/api/report-manager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

switch ($action) {
	case "read":
		ReadAction();
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
			$response["report"] = ReportManager::GetReport();
			$response["status"] = "success";
			$response["message"] = 'Получен отчет.';
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
