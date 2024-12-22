<?php

namespace sqlutils;

use conf\Configuration;
use src\classes\InmConstants;
use src\classes\InmConfiguration;
use src\classes\InmobaApp;
use src\classes\Utils;

/**
 * SQL management utility functions.
 *
 */
class SqlUtils
{
	/**
	 * Get PDO connection of the database.
	 * 
	 * @return \PDO
	 */
	public static function getDBConnection()
	{
		$dbData = Configuration::getConfiguration()["db"];
		
		$host = $dbData["host"];
		$port = $dbData["port"];
		$user = $dbData["user"];
		$password = $dbData["password"];
		$database = $dbData["dbname"];
		
		$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";
		$dbConnection = new \PDO($dsn,$user,$password);
		
		return $dbConnection;
	}

	private static function startsWith($haystack, $needle)
	{
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== FALSE;
	}
	
	/**
	 * Runs a SQL script prefixing every table name with the configured prefix to keep coherence in the database.
	 *
	 * @param string $sqlfile
	 * @return string[]
	 */
	public static function runSqlFile($sqlFile)
	{
		$t0 = microtime(true);
		
		$output = array();
		
		$config = Configuration::getConfiguration()["db"];
		
		$dbConnection = self::getDBConnection();
		
		$f = fopen($sqlFile,"r");
		
		$line = trim(\fgets($f));
		$sentence = "";
		while(!feof($f) || !empty($line))
		{
			if(!empty($line) && substr($line,0,1) != "#")
			{
				$sentence .= $line."\n";
			}
			
			if(strpos($line,";") !== false && !empty($sentence))
			{
				if(strpos($sentence, "CREATE INDEX") !== false)
				{
					$regex = "/CREATE INDEX `(.*)` ON `(.*)`(.*)/";
					$replaceExpression = "CREATE INDEX `\${1}` ON `\${2}`\${3}";
					$sentence2 = preg_replace($regex,$replaceExpression,$sentence);
				}
				elseif(strpos($sentence, "CREATE FULLTEXT INDEX") !== false)
				{
					$regex = "/CREATE FULLTEXT INDEX `(.*)` ON `(.*)`(.*)/";
					$replaceExpression = "CREATE FULLTEXT INDEX `\${1}` ON `\${2}`\${3}";
					$sentence2 = preg_replace($regex,$replaceExpression,$sentence);
				}
				else
				{
					$regex = "/(DROP TABLE IF EXISTS|CREATE TABLE|ALTER TABLE|CREATE TABLE IF NOT EXISTS) `(.*)`(.*)/";
					$replaceExpression = "\${1} `\${2}`\${3}";
					$sentence2 = preg_replace($regex,$replaceExpression,$sentence);
					
					$sentenceArray = explode("\n",$sentence2);
					
					// Check foreign key relations
					
					$sentenceArray = array_map(function($lineSlug)
					{
						$regex = "/(.*) REFERENCES `(.*)`(.*)/";
						$replaceExpression = "\${1} REFERENCES `\${2}`\${3}";
						$output = preg_replace($regex,$replaceExpression,$lineSlug);
						//print "'$lineSlug' => '$output'\n";
						return $output;
						
					},$sentenceArray);
					
					$sentence2 = implode("\n",$sentenceArray);
				}
			}
			elseif(self::startsWith($line, "DELIMITER"))
			{
				$matches = array();
				preg_match("/DELIMITER (?P<delimiter>.*)/", $line,$matches);
				$delimiter = trim($matches["delimiter"]);
				
				$line = "";
				$script = array();
				
				do
				{
					$script[] = $line;
					$line = trim(\fgets($f));
				}while(!feof($f) && !self::startsWith($line, "DELIMITER"));
				
				$script[] = $line;
				
				$sentence2 = explode($delimiter,implode("\n",$script));
				unset($sentence2[count($sentence2) - 1]);
			}
			else
			{
				$sentence2 = "";
			}
			
			if(!empty($sentence2))
			{
				try
				{
					$sentence = "";
					if(is_array($sentence2))
					{
						array_map(array($dbConnection,"exec"),$sentence2);
					}
					else
					{
						$dbConnection->exec($sentence2);
					}
				}
				catch(\Exception $ex)
				{
					print "exception: ".$ex->getMessage()."\n";
				}
			}
			
			$line = trim(\fgets($f));
		}
		
		$t1 = microtime(true);
		
		$output[] = "time: ".($t1 - $t0)." s";
		
		return $output;
	}
	
}

?>