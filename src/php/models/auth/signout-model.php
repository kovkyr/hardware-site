<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";
require_once "$rootpath/php/api/auth-manager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

switch ($action) {
	case "signout":
		SignOutAction();
		break;
	default:
		DefaultAction();
		break;
}

function SignOutAction() {
	$response = array();
	$response["action"] = "signout";
	$response["status"] = "";
	$response["message"] = "";

	try {
		DatabaseManager::BeginTransaction();
		$isSuccess = AuthManager::SignOut();
		if($isSuccess) {
			$response["status"] = "success";
			$response["message"] = "Пользователь вышел из системы.";
		} else {
			$response["status"] = "error";
			$response["message"] = "Пользователь не вышел из системы.";
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
