<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once 'Book.php';

$database = new Database();
$db = $database->getConnection();

$book = new Book($db);

$book->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $book->readOne();
$num = $stmt->rowCount();

if ($num > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    $book_item = array(
        "id" => $id,
        "title" => $title,
        "author" => $author,
        "description" => html_entity_decode($description),
        "published_date" => $published_date
    );
    http_response_code(200);
    echo json_encode($book_item);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Book does not exist."));
}
?>
