<?php
    session_start();

    include 'index.php';  

    //connect to db
    $conn = new mysqli($dbServer, $dbUser, $dbPass, $dbName) 
    or die($conn);

    //get values
    if ((isset($_POST['user'])) && (isset($_POST['user']))){
        $username = $_POST['user'];
        $password = $_POST['pass'];
    } else {
        $username = null;
        $password = null;
    }   

    //prevent mysql injection
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    //encrypt pass
    $encrypted = hash('sha256', $password);

    //search
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$encrypted'";
    $result = mysqli_query($conn, $sql) or die("Failed to query database ".mysqli_error($conn));

    //compare
    $row = mysqli_fetch_array($result);
    if (($row['username'] != $username) || ($row['password'] != $encrypted)){
        if ((isset($_POST['user'])) && (isset($_POST['pass']))){
        $_SESSION['msg'] = 'Credentials mismatch';}
    } else {
        $_SESSION['id'] = $row['id'];
        $_SESSION['user'] = $row['username'];
    }
    mysqli_close($conn);


?>