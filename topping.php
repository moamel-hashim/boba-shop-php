<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
include 'connection.php';

$objDb = new DbConnect;
$conn = $objDb->connect();

$uploadDir = 'tmp/images/topping-images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    echo 'No file uploaded. <br/>';
  }
}
$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
  case "POST":
    $topping = $_POST;
    $sql = "INSERT INTO topping(topping_id, price, topping_name, topping_image_path) VALUES(null, :price, :toppingTitle, :image)";
    $statement = $conn->prepare($sql);
    $statement->bindParam(':price', $topping['price']);
    $statement->bindParam(':toppingTitle', $topping['toppingTitle']);
    $statement->bindParam(':image', $uploadFile);
    if($statement->execute()) {
      $response = ['status' => 1, 'message' => 'Record created successfully'];
    } else {
      $errorInfo = $statement->errorInfo();
      error_log("Database Error:" . $errorInfo[2]);
      $response = ['status' => 1, 'message' => 'Failed to create'];
    }
    echo json_encode($response);
    break;
    case "GET":
      $getTopping = $_GET;
      $sql = "SELECT * FROM topping";
      $stmt = $conn->prepare($sql);
      if($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
      } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['status' => 0, 'message' => 'Failed to execute query. ' . $errorInfo[2]]);
      }
      break;
    default:
    http_response_code(405);
    echo json_encode(['status' => 0, 'message' => 'Method Not Allowed']);
  }
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
?>
