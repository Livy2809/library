<?php
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = $_SERVER["REQUEST_URI"];
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$requestPath = explode('/', trim($requestPath, '/'));

if (count($requestPath) < 2 || $requestPath[0] !== 'api' || $requestPath[1] !== 'books') {
    http_response_code(404);
    echo json_encode(array("message" => "Not Found"));
    exit();
}

include_once '../config/database.php';

switch ($requestPath[2]) {
    case 'list':
        if ($requestMethod == 'GET') {
            include 'index.php';
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        break;

    case 'create':
        if ($requestMethod == 'POST') {
            include 'create.php';
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        break;

    case 'show':
        if ($requestMethod == 'GET' && isset($requestPath[3]) && is_numeric($requestPath[3])) {
            include 'show.php';
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid ID"));
        }
        break;

    case 'update':
        if (($requestMethod == 'PUT' || $requestMethod == 'POST') && isset($requestPath[3]) && is_numeric($requestPath[3])) {
            include 'update.php';
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid ID"));
        }
        break;

    case 'delete':
        if ($requestMethod == 'DELETE' && isset($requestPath[3]) && is_numeric($requestPath[3])) {
            include 'delete.php';
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid ID"));
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(array("message" => "Not Found"));
        break;
}
?>
