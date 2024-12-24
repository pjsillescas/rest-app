<?php

require __DIR__ . "/autoload.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;


$corsMiddleware = function (Request $request, RequestHandler $handler)
{
	// Proceed with the next middleware
	$response = $handler->handle($request);

	$response = $response->withHeader("Access-Control-Allow-Origin", "*")
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');

	return $response;
};

$jsonMiddleware = function (Request $request, RequestHandler $handler)
{
	// Proceed with the next middleware
	$response = $handler->handle($request);

	// Modify the response after the application has processed the request
	$response = $response->withHeader("Content-Type", "application/json");

	return $response;
};


$app = AppFactory::create();
$app->add($jsonMiddleware);
$app->add($corsMiddleware);

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

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

// preflight request
$app->options("/products", function(Request $request, Response $response, array $args)
{
	$response = $response
		->withHeader("Connection", "keep-alive")
		->withHeader("Access-Control-Allow-Origin", "*")
		->withHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS, DELETE")
		->withHeader("Access-Control-Allow-Headers", "X-Requested-With")
		->withHeader("Access-Control-Max-Age", "86400");
	return $response;
});

$app->run();

?>