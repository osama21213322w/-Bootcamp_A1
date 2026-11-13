<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$headers = function_exists('getallheaders') ? getallheaders() : [];
$auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

$input = file_get_contents("php://input");
$data = null;

if (trim($input)) {
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON"], JSON_PRETTY_PRINT);
        exit;
    }
}

$res = [
    "status" => "success",
    "method" => $method,
    "auth" => $auth ? "Present" : "Not provided",
    "message" => "",
    "data" => null
];

switch ($method) {
    case "GET":
        http_response_code(200);
        $res["message"] = "GET request OK";
        $res["data"] = $_GET;
        break;
    case "POST":
        http_response_code(201);
        $res["message"] = "POST request OK";
        $res["data"] = $data;
        break;
    case "PUT":
        http_response_code(200);
        $res["message"] = "PUT request OK";
        $res["data"] = $data;
        break;
    case "DELETE":
        http_response_code(200);
        $res["message"] = "DELETE request OK";
        $res["data"] = $data;
        break;
    default:
        http_response_code(405);
        header("Allow: GET, POST, PUT, DELETE");
        $res["status"] = "error";
        $res["message"] = "Method not allowed";
        $res["allowed"] = ["GET", "POST", "PUT", "DELETE"];
        break;
}

echo json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
