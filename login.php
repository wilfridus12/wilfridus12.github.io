<?php 

ini_set('display_errors', 0);

session_start();

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $input_password = $_POST['password'];
    $conn = new mysqli("localhost", "root", "", "user");

    if ($conn->connect_error) {
        die("Koneksi ke database gagal: " . $conn->connect_error);
    }
    
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        if (password_verify($input_password, $row['password'])) {
            $id_role = $row['id_role'];
            if ($id_role == 1) {
                $_SESSION['username'] = $input_username; 
                header("Location: index.php");
                exit;
            } elseif ($id_role == 2) {
                $_SESSION['username'] = $input_username; 
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Role ID is invalid.";
            }
        } else {
            $error_message = "Incorrect username or password.";
        }
    } else {
        $error_message = "Incorrect username or password.";
    }

    $stmt->close();
    $conn->close();
}



?>



<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</head>

<body style="background-image: url('wangan_wiki.png')">
    <div class="container vh-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="card w-50 my-5 shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h2 class="mb-0">Login Form</h2>
                </div>
                <div class="card-body">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username" class="mb-0">Username</label>
                            <input type="text" id="username" placeholder="Enter Username" class="form-control"
                                name="username" required />
                        </div>
                        <div class="form-group">
                            <label for="password" class="mb-0">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Enter Password"
                                name="password" required />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>


                    </form>
                </div>
                <footer class="text-center">
                    Register as a member? <a href="registration.php">Register here</a>
                </footer>
                <div class="card-footer text-center">
                    <small>&copy; Gabriel_600IT</small>
                </div>
            </div>
        </div>
    </div>

    <script>
    <?php if (isset($error_message)) { ?>
    // Tampilkan pesan kesalahan dengan SweetAlert2
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo $error_message; ?>',
    });
    <?php } ?>
    </script>
</body>

</html>