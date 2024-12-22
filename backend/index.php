<?php

require __DIR__ . "/autoload.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;


$jsonMiddleware = function (Request $request, RequestHandler $handler)
{
	// Proceed with the next middleware
	$response = $handler->handle($request);

	// Modify the response after the application has processed the request
	$response = $response->withHeader('Content-Type', 'application/json');

	return $response;
};


$app = AppFactory::create();
$app->add($jsonMiddleware);
$app->get("/", function (Request $request, Response $response, array $args)
{
	$data = array("data" => "todo");
	$response->getBody()->write(json_encode($data));
	return $response;
});

$app->get("/products", function (Request $request, Response $response, array $args)
{
	$data = api\API::getInstance()->getProducts();
	$response->getBody()->write(json_encode($data));
	return $response;
});

$app->get("/products/{id}", function (Request $request, Response $response, array $args)
{
	$id = $args["id"];
	$data = api\API::getInstance()->getProduct($id);
	$response->getBody()->write(json_encode($data));
	return $response;
});

$app->post("/products", function (Request $request, Response $response, array $args)
{
	$requestData = json_decode($request->getBody()->getContents(), true);
	$data = api\API::getInstance()->addProduct($requestData["name"]);
	$response->getBody()->write(json_encode($data));
	return $response;
});

$app->put("/products", function (Request $request, Response $response, array $args)
{
	$requestData = json_decode($request->getBody()->getContents(), true);
	$id = $requestData["id"];
	$name = $requestData["name"];
	$data = api\API::getInstance()->updateProduct($id, $name);
	$response->getBody()->write(json_encode($data));
	return $response;
});

$app->run();

?>