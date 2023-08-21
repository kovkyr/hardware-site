<?php

class DatabaseManager {
	private static $instance = NULL;
	private function __construct() {}
	private function __clone() {}
	public static function GetDatabaseInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new PDO('pgsql:host=localhost;port=5432;dbname=app;user=app;password=123456');
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$instance;
	}

	public static function BeginTransaction() {
		$db = DatabaseManager::GetDatabaseInstance();
		$db->beginTransaction();
	}

	public static function Commit() {
		$db = DatabaseManager::GetDatabaseInstance();
		$db->commit();
	}

	public static function Rollback() {
		$db = DatabaseManager::GetDatabaseInstance();
		$db->rollback();
	}
}
