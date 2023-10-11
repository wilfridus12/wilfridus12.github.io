<?php
$u = $_POST['username'];
$p = $_POST['password'];


// Database connection
$conn = new mysqli('localhost','root','','gabriel');
if($conn->connect_error){
   echo "$conn->connect_error";
   die("Connection Failed : ". $conn->connect_error);
} else {
   $sql = "SELECT * FROM user WHERE username=? and password=?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("ss", $u, $p);
   $stmt->execute();
   $result = $stmt->get_result();
   if($result->num_rows === 0) {
      echo "Login failed";

   }
   else {
      $stmt->close();
      $conn->close();
      echo "Login Successful";
   }

}

?>