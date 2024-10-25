<?php

include 'Includes/dbcon.php';
ini_set('display_errors', '0');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $userType = trim($_POST['userType']);
        $username = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($userType == "Administrator") {
            // Prepare SQL statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM tbladmin WHERE emailAddress = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $rs = $stmt->get_result();

            if ($rs->num_rows > 0) {
                $rows = $rs->fetch_assoc();
                $hashedPassword = $rows['password'];

                // Compare MD5 hash of the password with the stored hashed password
                if (md5($password) === $hashedPassword) {
                    // Password is valid, set session variables
                    $_SESSION['userId'] = $rows['Id'];
                    $_SESSION['firstName'] = $rows['firstName'];
                    $_SESSION['lastName'] = $rows['lastName'];
                    $_SESSION['emailAddress'] = $rows['emailAddress'];

                    // Redirect to the admin page
                    header("Location: Admin/index.php");
                    exit();
                } else {
                    echo "<p>Password verification failed. Please try again.</p>";
                }
            } else {
                // No user found
                echo "<div class='alert alert-danger' role='alert'>
                        Invalid E-mail Or Password!
                      </div>";
            }
        } else {
            // Invalid user type
            echo "<div class='alert alert-danger' role='alert'>
                    Invalid E-mail Or Password!
                  </div>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/rcalogo.png" type="image/x-icon">
    <link href="img/rcalogo.png" rel="icon">
    <title>RCA Attendance</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login" style="background-image: url('img/rcalogo.png);">
    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <h3 align="center"><b>RCA Attendance</b></h3>
                                    <div class="text-center">
                                        <img src="img/rcalogo.png" style="width:100px;height:100px">
                                        <br><br>
                                        <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <select required name="userType" class="form-control mb-3">
                                                <!-- <option value="">--Select User Role--</option> -->
                                                <option value="Administrator">Instructor</option>
                                                <!-- <option value="ClassTeacher">Class&nbsp; Monitor</option> -->
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" required name="email"
                                                id="exampleInputEmail" placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" required class="form-control"
                                                id="exampleInputPassword" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small"
                                                style="line-height: 1.5rem;">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success btn-block" value="Login"
                                                name="login" />
                                        </div>
                                    </form>

                                    <div class="text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Content -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <div class="text-center mt-4">
        <a href="studregistration.php"
            class="text-decoration-none btn btn-primary btn-lg px-4 py-2 rounded-pill shadow-sm">Register Here</a>
    </div>
</body>

</html>