<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
include 'connection.php';

$objDb = new DbConnect;
$conn = $objDb->connect();

$uploadDir = 'tmp/images/';


if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
    echo "File Details:\n";
    var_dump($file);

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        echo 'File uploaded successfully.';
    } else {
        echo 'Error uploading file.';
    }
} else {
    echo 'No file uploaded.';
}
$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
  case "POST":
    $drink = $_POST;
    $sql = "INSERT INTO drink(drink_id, price, drink_type, drink_title, drink_description, drink_image_path) VALUES(null, :price, :drinkType, :drinkTitle, :description, :image)";
    $statement = $conn->prepare($sql);
    $statement->bindParam(':price', $drink['price']);
    $statement->bindParam(':drinkType', $drink['drinkType']);
    $statement->bindParam(':drinkTitle', $drink['drinkTitle']);
    $statement->bindParam(':description', $drink['description']);
    $statement->bindParam(':image', $uploadFile);
    if($statement->execute()) {
      $response = ['status' => 1, 'message' => 'Record created successfully'];
    } else {
      $response = ['status' => 1, 'message' => 'Failed to create'];
    }
    echo json_encode($response);
    break;
    default:
    http_response_code(405);
    echo json_encode(['status' => 0, 'message' => 'Method Not Allowed']);
}
echo "\n\nForm Fields:\n";
print_r($_POST);
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
