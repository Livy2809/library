<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once 'Book.php';

$database = new Database();
$db = $database->getConnection();

$book = new Book($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id) &&
    !empty($data->title) &&
    !empty($data->author) &&
    !empty($data->description) &&
    !empty($data->published_date)
) {
    $book->id = $data->id;
    $book->title = $data->title;
    $book->author = $data->author;
    $book->description = $data->description;
    $book->published_date = $data->published_date;

    if ($book->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Book was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update book."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update book. Data is incomplete."));
}
?>
