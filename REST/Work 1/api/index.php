<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$books = [
    ["id" => 1, "title" => "1984", "author" => "George Orwell"],
    ["id" => 2, "title" => "Brave New World", "author" => "Aldous Huxley"],
    ["id" => 3, "title" => "Fahrenheit 451", "author" => "Ray Bradbury"]
];

function get_books() {
    global $books;
    echo json_encode($books);
}

function get_book($id) {
    global $books;
    foreach ($books as $book) {
        if ($book["id"] == $id) {
            echo json_encode($book);
            return;
        }
    }
    http_response_code(404);
    echo json_encode(["message" => "Book not found"]);
}

function create_book() {
    global $books;
    if (!isset($_POST['title']) || !isset($_POST['author'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
        return;
    }
    $new_book = [
        "id" => end($books)['id'] + 1,
        "title" => $_POST['title'],
        "author" => $_POST['author']
    ];
    $books[] = $new_book;
    echo json_encode($new_book);
}

function update_book($id) {
    global $books;
    $data = json_decode(file_get_contents("php://input"), true);
    foreach ($books as &$book) {
        if ($book["id"] == $id) {
            if (isset($data['title'])) {
                $book['title'] = $data['title'];
            }
            if (isset($data['author'])) {
                $book['author'] = $data['author'];
            }
            echo json_encode($book);
            return;
        }
    }
    http_response_code(404);
    echo json_encode(["message" => "Book not found"]);
}

function delete_book($id) {
    global $books;
    foreach ($books as $key => $book) {
        if ($book["id"] == $id) {
            unset($books[$key]);
            echo json_encode(["message" => "Book deleted"]);
            return;
        }
    }
    http_response_code(404);
    echo json_encode(["message" => "Book not found"]);
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

switch ($method) {
    case 'GET':
        if (isset($request[1])) {
            get_book((int)$request[1]);
        } else {
            get_books();
        }
        break;
    case 'POST':
        create_book();
        break;
    case 'PUT':
        if (isset($request[1])) {
            update_book((int)$request[1]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
        }
        break;
    case 'DELETE':
        if (isset($request[1])) {
            delete_book((int)$request[1]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>
