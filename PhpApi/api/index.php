<?php

use Persistance\DbContext;
use Services\ItemService;
use Services\GetHelp;

require "Services/ItemService.php";
require "Services/GetHelp.php";
require "Persistance/DbContext.php";
require "Validators/ItemValidator.php";
require "Model/Item.php";
require "Model/Enums/ItemQuality.php";

header("Content-type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *"); //If it not present then it will whine at me that I cannot call endpoints from my Vue App
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$method = $_SERVER['REQUEST_METHOD'];
if($method === 'OPTIONS'){
    http_response_code(200);
    exit();
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $requestUri);

// Initialize DB and Service
$dbName = getenv('DB_DATABASE') ?: 'bobbydb';
$dbUser = getenv('DB_USERNAME') ?: 'bobby';
$dbPass = getenv('DB_PASSWORD') ?: 'some_pass';

if ($dbSocket = getenv('DB_SOCKET')) {
    // App Engine deployment: connect via Unix socket using a full DSN string.
    $dsn = sprintf('mysql:unix_socket=%s;dbname=%s;charset=utf8mb4', $dbSocket, $dbName);
    $dbContext = new DbContext($dsn, null, $dbUser, $dbPass);
} else {
    // Local docker-compose: connect via hostname.
    $dbHost = getenv('DB_HOST') ?: 'mariadb';
    $dbContext = new DbContext($dbHost, $dbName, $dbUser, $dbPass);
}

$getHelp = new GetHelp();
$itemService = new ItemService($dbContext);

$itemId = $uri_segments[3] ?? null;

switch ($method) {
    case 'GET':
        if($requestUri === "/api/help"){
            $getHelp->getHelp();
        }
        elseif ($requestUri === "/api/items"){
            $itemService->getItems();
        }
        elseif ($requestUri === "/api/items/" . $itemId) {
            $itemService->getItemById($itemId);
        }
        elseif ($requestUri === "/api/purchases") {
            $itemService->getPurchases();
        }
        else{
            $response = [
                "success" => false,
                "method" => $method,
                "status" => 405,
                "message" => "Method Not Allowed"
            ];
            http_response_code(405);
            echo(json_encode($response));
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if($requestUri === "/api/items" && $data) {
            $itemService->addItem($data);
        }
        elseif($requestUri === "/api/purchase/". $itemId){
            $itemService->purchaseItem($itemId, $data);
        }
        else {
            $response = [
                "success" => false,
                "method" => $method,
                "status" => 405,
                "message" => "Method Not Allowed"
            ];
            http_response_code(405);
            echo(json_encode($response));
        }
        break;
    case 'DELETE':
        if($requestUri === "/api/items")
        {
            $itemService->clearRotten();
        }
        elseif($requestUri === "/api/items/". $itemId){
            $itemService->deleteItem($itemId);
        }
        else {
            $response = [
                "success" => false,
                "requestUri" => $requestUri,
                "method" => $method,
                "status" => 405,
                "message" => "Method Not Allowed"
            ];
            http_response_code(405);
            echo(json_encode($response));
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if($requestUri === "/api/items/". $itemId){
            $itemService->updateItem($itemId, $data);
        }
        elseif($requestUri === "/api/items/". $itemId ."/rotten")
        {
            $itemService->markedAsRotten($itemId);
        }
        else {
            $response = [
                "success" => false,
                "method" => $method,
                "status" => 405,
                "message" => "Method Not Allowed"
            ];
            http_response_code(405);
            echo(json_encode($response));
        }
        break;

    default:
        $response = [
            "success" => false,
            "method" => $method,
            "status" => 405,
            "message" => "Method not allowed",
        ];
        http_response_code(405);
        echo(json_encode($response));
        break;
}
