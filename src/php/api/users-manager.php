<?php

$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$rootpath/php/api/database-manager.php";

class UsersManager {
	public static function CreateUser($user, $password) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare('INSERT INTO users ("user", password) VALUES (:user, :password)');
		$req->execute(array('user' => $user, 'password' => md5($password)));
		$reqResult = $req->fetchAll();
		return true;
	}

	public static function ReadUser($id) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			select id, "user", password from users where id = :id
		');
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function ReadUserByName($user) {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		('
			select id, "user", password from users where "user" = :user
		');
		$req->execute(array('user' => $user));
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? NULL : $reqResult[0];
	}

	public static function UpdateUser($id, $user, $password) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from users where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		('
			update users
			set
			"user" = :user,
			password = :password
			where id = :id
		');
		$req->execute
		(
			array
			(
				'id' => $id,
				'user' => $user,
				'password' => md5($password)
			)
		);

		return true;
	}

	public static function UpdateUserName($id, $user) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from users where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		('
			update users
			set
			"user" = :user
			where id = :id
		');
		$req->execute
		(
			array
			(
				'id' => $id,
				'user' => $user
			)
		);

		return true;
	}

	public static function DeleteUser($id) {
		$db = DatabaseManager::GetDatabaseInstance();

		$req = $db->prepare
		("
			select id from users where id = :id
		");
		$req->execute(array('id' => $id));
		$reqResult = $req->fetchAll();
		if(count($reqResult) == 0) return false;

		$req = $db->prepare
		("
			delete from users where id = :id
		");
		$req->execute(array('id' => $id));

		return true;
	}

	public static function GetUsersCount() {
		$db = DatabaseManager::GetDatabaseInstance();
		$req = $db->prepare
		("
			select count(*) as count from users
		");
		$req->execute(array());
		$reqResult = $req->fetchAll();
		return count($reqResult) == 0 ? 0 : $reqResult[0]["count"];
	}
}
