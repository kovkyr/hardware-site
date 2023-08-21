<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";
require_once "$rootpath/php/api/auth-manager.php";
require_once "$rootpath/php/api/tables-manager.php";
require_once "$rootpath/php/api/users-manager.php";

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

	$response["users"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			$response["users"] = TablesManager::GetUsers();
			$response["status"] = "success";
			$response["message"] = 'Получен список пользователей.';
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
	$user = $data->user->user;
	$password = $data->user->password;

	$response = array();
	$response["action"] = "create";
	$response["status"] = "";
	$response["message"] = "";

	$response["users"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			if($user == null) {
				$response["status"] = "error";
				$response["message"] = "Необходимо задать непустое имя пользователя.";
			} elseif ($password == null) {
				$response["status"] = "error";
				$response["message"] = "Необходимо задать непустой пароль.";
			} else {
				$response["status"] = "success";
				$response["message"] = 'Добавлен новый пользователь.';
				UsersManager::CreateUser($user, $password);
			}
			$response["users"] = TablesManager::GetUsers();
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
	$id = $data->user->id;
	$user = $data->user->user;
	$password = $data->user->password;

	$response = array();
	$response["action"] = "update";
	$response["status"] = "";
	$response["message"] = "";

	$response["users"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			if($user == null) {
				$response["status"] = "error";
				$response["message"] = "Необходимо задать непустое имя пользователя.";
				echo json_encode($response);
				return;
			}
			if ($password == null) {
				$u = UsersManager::ReadUser($id);
				UsersManager::UpdateUserName($id, $user);
			} else {
				UsersManager::UpdateUser($id, $user, $password);
			}
			$response["users"] = TablesManager::GetUsers();
			$response["status"] = "success";
			$response["message"] = 'Информация о пользователе обновлена.';
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
	$id = $data->user->id;

	$response = array();
	$response["action"] = "delete";
	$response["status"] = "";
	$response["message"] = "";

	$response["users"] = array();

	try {
		DatabaseManager::BeginTransaction();
		$isAutenticated = AuthManager::IsAuthenticated();
		if($isAutenticated) {
			if(UsersManager::GetUsersCount() <= 1) {
				$response["status"] = "error";
				$response["message"] = 'Невозможно удалить последнего пользователя.';
			} else {
				UsersManager::DeleteUser($id);
				$response["status"] = "success";
				$response["message"] = 'Пользователь удален.';
			}
			$response["users"] = TablesManager::GetUsers();
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
