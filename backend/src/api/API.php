<?php

namespace api;

use conf\Configuration;

class API
{
	private $connection;

	private static $instance = null;

	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new API();
		}

		return self::$instance;
	}

	private function __construct()
	{
		/*
					$host = "database";
					$db = "app_db";
					$user = "app_user";
					$password = "app_password";
					*/
		//$dsn = "mysql:dbname={$db};host={$host}";
		$this->getConnection();
	}

	public function getConnection()
	{
		if ($this->connection == null)
		{
			$conf = Configuration::getConfiguration()["db"];
			$host = $conf["host"];
			$db = $conf["dbname"];
			$user = $conf["user"];
			$password = $conf["password"];

			$dsn = "mysql:dbname={$db};host={$host}";
			$this->connection = new \PDO($dsn, $user, $password);
		}

		return $this->connection;
	}

	public function getProducts()
	{
		return $this->connection->query("SELECT * FROM product")->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getProduct($id)
	{
		return $this->connection->query("SELECT * FROM product WHERE id = {$id}")->fetch(\PDO::FETCH_ASSOC);
	}

	public function addProduct($name)
	{
		$quotedName = $this->connection->quote($name);
		$this->connection->exec("INSERT INTO product (name) VALUES ({$quotedName})");
		
		$row = $this->connection->query("SELECT id,name FROM product WHERE name = {$quotedName}")->fetch(\PDO::FETCH_ASSOC);

		return $row;
	}
	
	public function updateProduct($id, $name)
	{
		$quotedName = $this->connection->quote($name);
		$this->connection->exec("UPDATE product SET name = {$quotedName} WHERE id = {$id}");
		
		$row = $this->connection->query("SELECT id,name FROM product WHERE id = {$id}")->fetch(\PDO::FETCH_ASSOC);

		return $row;
	}
}

?>