<?php
class API {
	private $connection;

	private static $instance = null;

	public static function getInstance() {
		if(self::$instance == null)
		{
			self::$instance = new API();
		}

		return self::$instance;
	}

	private function __construct() {
		$host = "database";
		$db = "app_db";
		$user = "app_user";
		$password = "app_password";
		$dsn = "mysql:dbname={$db};host={$host}";
		$this->connection = new PDO($dsn, $user, $password);
		;
	}

	public function getProducts()
	{
		return $this->connection->query("SELECT * FROM product")->fetchAll(\PDO::FETCH_ASSOC);
	}

	//print_r($rows);
}

require __DIR__."/vendor/autoload.php";

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;


$jsonMiddleware = function (Request $request, RequestHandler $handler) {
    // Proceed with the next middleware
    $response = $handler->handle($request);
    
    // Modify the response after the application has processed the request
    $response = $response->withHeader('Content-Type', 'application/json');
    
    return $response;
};


$app = AppFactory::create();
$app->add($jsonMiddleware);
$app->get("/", function ($request, $response, $args) {
	$data = array("data" => "todo");
    $response->getBody()->write(json_encode($data));
    return $response;
});

$app->get("/products", function ($request, $response, $args) {
	$data = API::getInstance()->getProducts();
    $response->getBody()->write(json_encode($data));
    return $response;
});

$app->run();

?>