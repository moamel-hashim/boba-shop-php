<?php
class dbConnect {
  private $server = 'localhost';
  private $dbname = 'boba-shop';
  private $user = 'root';
  private $password = 'root';

  public function connect() {
    try {
      $conn = new PDO('mysql:host='.$this->server.';dbname='.$this->dbname, $this->user, $this->password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch(\Exception $err) {
      echo "Database Error: ". $err->getMessage();
    }
  }
}
?>
