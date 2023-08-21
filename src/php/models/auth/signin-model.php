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
	case "signin":
		SignInAction();
		break;
	default:
		DefaultAction();
		break;
}

function SignInAction() {
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$user = $data->user;
	$password = $data->password;

	$response = array();
	$response["action"] = "signin";
	$response["status"] = "";
	$response["message"] = "";

	try {
		DatabaseManager::BeginTransaction();
		$isSuccess = AuthManager::SignIn($user, $password);
		if($isSuccess) {
			$response["status"] = "success";
			$response["message"] = "Пользователь вошел в систему.";
		} else {
			$response["status"] = "error";
			$response["message"] = "Не верный логин или пароль";
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
