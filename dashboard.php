<?php 
ini_set('display_errors', 0);

session_start(); 
$loggedInUsername = "Guest"; 

if (isset($_SESSION['username'])) {
    $loggedInUsername = $_SESSION['username'];


    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $database = "user"; 

    $conn = new mysqli($servername, $username, $password, $database);


    $query = "SELECT username FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $loggedInUsername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $resultUsername);

        if (mysqli_stmt_fetch($stmt)) {
            $loggedInUsername = $resultUsername;
        } else {
            die("Unable to retrieve user data: " . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Errors in preparing the statement: " . mysqli_error($conn));
    }

    // mysqli_close($conn);
}


?>






<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Home</title>

    <style>
    .jumbotron {
        position: relative;
        background-image: url('wangan_wiki.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        color: #fff;
        text-align: center;
        height: 100vh;
        margin-bottom: 0;
        overflow: hidden;

    }


    .jumbotron::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }


    .jumbotron h1,
    a {
        position: relative;
        z-index: 999;
    }




    .navbar-nav .nav-item {
        margin-right: 20px;
    }

    .navbar-nav .hi-message {
        margin-right: 10px;
    }

    .btn.btn-light {
        background-color: #f8f9fa;
        color: #007bff;
        border-radius: 5px;
        padding: 10px 20px;
        text-decoration: none;
        transition: background-color 0.3s, color 0.3s;
        font-weight: bold;
    }

    .btn.btn-light:hover {
        background-color: #007bff;
        /* Warna latar belakang saat hover */
        color: #fff;
        /* Warna teks saat hover */
    }

    .card {
        color: black;
    }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-info ">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="6RR_Logo.webp" alt="" style="width:140px;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">News </a>
                    </li>

                    <li class="nav-item active">
                        <a class="nav-link" href="#">Subscribe</a>
                    </li>


                    <?php if ($loggedInUsername === "Guest") { ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>

                    <li class="nav-item active">
                        <a class="nav-link" href="registration.php">Register</a>
                    </li>

                    <?php }else { ?>

                    <li class="nav-item active">
                        <a href="#" class="nav-link">Hi, <?php echo $loggedInUsername; ?></a>
                    </li>
                    <li class="nav-item active">
                        <a href="logout.php" class="nav-link">Logout</a>
                    </li>

                    <?php } ?>

                </ul>

            </div>
        </div>
    </nav>

    <div class="jumbotron">
        <div class="container">
            <br><br><br>
            <?php if ($loggedInUsername === "Guest") { ?>
            <h1 class="display-4 font-weight-bold">Welcome to our website </h1>
            <br>
            <a href="registration.php" class="btn btn-light">Register</a>
            <a href="login.php" class="btn btn-light">Login</a>

            <?php }else {?>

            <h1 class="display-4 font-weight-bold">Welcome to our website </h1>
            <h1 class="display-4 font-weight-bold">Hi, <?php echo $loggedInUsername; ?></h1>
            <?php } ?>

        </div>

        <br>
        <div class="card">
            <div class="container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Rank</th>
                            <th scope="col">Timing</th>
                            <th scope="col">Model</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 

                            $query = "SELECT * FROM user WHERE id_role = 1";
                            $result = $conn->query($query);

                       
                       
                       if ($result->num_rows > 0) {
                        $rowNumber = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<th scope='row'>" . $rowNumber . "</th>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["rank"] . "</td>";
                            echo "<td>" . $row["timing"] . "</td>";
                            echo "<td>" . $row["model"] . "</td>";
                            echo "</tr>";
                            $rowNumber++;
                        }
                    } else {
                        echo "<tr><td colspan='6'>No data found</td></tr>";
                    }
            
                    $conn->close();
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>