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
	case "isauthenticated":
		IsAuthenticatedAction();
		break;
	default:
		DefaultAction();
		break;
}

function IsAuthenticatedAction() {
	$response = array();
	$response["action"] = "isauthenticated";

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		$response["status"] = "success";
		if($isAutenticated) {
			$response["message"] = "Пользователь авторизован.";
			$response["authenticated"] = "true";
			$response["user"] = AuthManager::GetAuthenticatedUserName();
		} else {
			$response["message"] = "Пользователь не авторизован.";
			$response["authenticated"] = "false";
			$response["user"] = "Пользователь";
		}
		DatabaseManager::Commit();
	} catch(PDOException $e) {
		$response["status"] = "error";
		$response["message"] = "Операция не выполнена." . $e->getMessage();
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
