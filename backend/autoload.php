<?php

require __DIR__."/vendor/autoload.php";

spl_autoload_register(function ($className)
{
	$completeClassName = ((substr($className, 0, 1) == "\\") ? "" : __NAMESPACE__ . "\\") . $className;

	$directories = array("/src");
	foreach ($directories as $dirPath)
	{
		$path = __DIR__ . $dirPath . str_replace("\\", "/", $completeClassName) . ".php";

		clearstatcache();
		$path = realpath($path);

		if (file_exists($path))
		{
			require_once $path;
		}
	}
});

?>