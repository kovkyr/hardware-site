<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class AuthManager {
	public static function IsAuthenticated() {
		session_start();
		return
			isset($_SESSION["authenticated"]) &&
			$_SESSION["authenticated"] == "true";
	}

	public static function GetAuthenticatedUserName() {
		session_start();
		return isset($_SESSION["user"]) ? $_SESSION["user"] : "";
	}

	public static function IsUserExists($user) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare('SELECT "user" FROM users WHERE "user" = :user');
		$req->execute(array('user' => $user));
		$reqResult = $req->fetchAll();
		$isUserExists = count($reqResult) > 0 ? true : false;
		return $isUserExists;
	}

	public static function SignUp($user, $password) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare('INSERT INTO users ("user", password) VALUES (:user, :password)');
		$req->execute(array('user' => $user, 'password' => md5($password)));
		$reqResult = $req->fetchAll();
		return true;
	}

	public static function SignIn($user, $password) {
		session_start();
		session_unset();
		session_destroy();
		session_start();

		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare('SELECT "user", password FROM users WHERE "user" = :user AND password = :password');
		$req->execute(array('user' => $user, 'password' => md5($password)));
		$reqResult = $req->fetchAll();
		$isSuccess = count($reqResult) > 0 ? true : false;

		$_SESSION["authenticated"] = $isSuccess ? "true" : "false";
		$_SESSION["user"] = $isSuccess ? $user : "Пользователь";

		return $isSuccess;
	}

	public static function SignOut() {
		$_SESSION["authenticated"] = "false";
		$_SESSION["user"] = "";
		session_start();
		session_unset();
		session_destroy();
		return true;
	}

	public static function ChangePassword($user, $newpassword) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare('UPDATE users SET password=:newpassword WHERE "user" = :user');
		$req->execute(array('user' => $user, 'newpassword' => md5($newpassword)));
		$reqResult = $req->fetchAll();
		return true;
	}
}
