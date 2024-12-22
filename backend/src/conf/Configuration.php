<?php

namespace conf;

class Configuration
{
	public static function getConfiguration()
	{
		return array(
			"db" => array(
				"host" => "database",
				"dbname" => "app_db",
				"user" => "app_user",
				"password" => "app_password",
				"port" => 3306,

			),
		);
	}
}
?>