<?php 
ini_set('display_errors', 0);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Verification Code</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>

<body style="background-image: url('wangan_wiki.png')">
    <div class="container vh-100">
        <div class="row justify-content-center h-100">
            <div class="card w-50 my-auto shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h2>Verification Code</h2>
                </div>
                <div class="card-body">
                    <form name="verificationForm" action="verficationcode.php" method="post">
                        <div class="mb-3">
                            <label for="verification_code" class="form-label">Kode Verifikasi</label>
                            <input type="text" id="verification_code" class="form-control" name="verification_code"
                                placeholder="Masukkan Kode Verifikasi" required>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary w-100" value="Verifikasi" name="verify">
                        </div>
                    </form>
                </div>
                <footer class="text-center">
                    Already a member? <a href="login.php">Login here</a>
                </footer>
                <div class="card-footer text-right">
                    <small>&copy; Gabriel_600IT</small>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php 
            
            $conn = mysqli_connect("localhost", "root", "", "user");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        
            if (isset($_POST["verification_code"])) {
                $verification_code = $_POST["verification_code"];
                $sql = "SELECT * FROM user WHERE verificationcode = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $verification_code);
                $stmt->execute();
                $result = $stmt->get_result();
        
                if ($result->num_rows === 1) {
                    $update_sql = "UPDATE user SET is_active = 1, emailverifiedat = NOW() WHERE verificationcode = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("s", $verification_code);
                    $delete_sql = "DELETE FROM user WHERE verificationcode = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("s", $verification_code);
                    if ($update_stmt->execute()) {
                        echo "Swal.fire('Verification Successful', 'Your account has been activated.', 'success');";
                    } else {
                        echo "Swal.fire('Verification Failed', 'Failed to activate account.', 'error');";
                    }
                } else {
                    echo "Swal.fire('Invalid Verification Code', 'The verification code you entered is invalid.', 'error');";
                }
            }
        
            $conn->close();
            ?>
    });
    </script>

</body>

</html>