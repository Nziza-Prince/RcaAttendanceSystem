<?php
include 'Includes/dbcon.php';
session_start();

// Function to fetch class arms based on class ID
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password']; // Getting the password from the form input

        // Hash the password using MD5
        $hashedPassword = md5($password);

        // Check if email address already exists
        $checkQuery = "SELECT * FROM tbladmin WHERE emailAddress = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "<div class='alert alert-danger' role='alert'>
                    Error: Instructor already exists!
                </div>";
        } else {
            // Insert instructor's data into the database with the MD5 hashed password
            $query = "INSERT INTO tbladmin (firstName, lastName, emailAddress, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssss', $firstName, $lastName, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Fetch the inserted user's data
                $userId = $conn->insert_id;
                $_SESSION['userId'] = $userId;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['emailAddress'] = $email;

                // Redirect after successful registration
                header("Location: classes.php");
                exit();
            } else {
                echo "<div class='alert alert-danger' role='alert'>
                        Error: " . $conn->error . "
                    </div>";
            }
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
    <link href="img/rca logo 2.png" rel="icon">
    <title>Instructor's Registration Page</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

    <style>
.input-container {
    position: relative;
}

.input-container input {
    padding-right: 40px; 
}
.input-container i {
    position: absolute;
    right: 10px;
    top: 50%; 
    transform: translateY(-50%); /
    cursor: pointer;
    color: #aaa;
}

.input-container i:hover {
    color: #000;
}

    </style>

</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpe00g');">
    <!-- Registration Form -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <h4 align="center">Instructor's Registration Page</h4>
                                    <div class="text-center">
                                        <img src="img/rca logo 2.png" style="width:100px;height:100px">
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <small>First Name</small>
                                            <input type="text" class="form-control" required name="firstName"
                                                placeholder="Enter First Name">
                                        </div>
                                        <div class="form-group">
                                            <small>Last Name</small>
                                            <input type="text" class="form-control" required name="lastName"
                                                placeholder="Enter Last Name">
                                        </div>
                                        <div class="form-group">
                                            <small>Email Address</small>
                                            <input type="text" class="form-control" required name="email"
                                                placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group1">
                                            <small>Password</small>
                                            <div class="input-container">
                                                <input type="password" name="password" required class="form-control"
                                                    id="exampleInputPassword" placeholder="Enter Password">
                                                <i class="fas fa-eye" id="togglePassword"></i>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success btn-block" value="Register"
                                                name="register" style="margin-top:1rem" />
                                        </div>
                                    </form>

                                    <div class="text-center">
                                        <a href="index.php" class="text-decoration-none">Back to Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Registration Form -->

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('exampleInputPassword');

            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            this.classList.toggle('fa-eye-slash');
        });

        $(document).ready(function () {
            $('#class').change(function () {
                var classId = $(this).val();
                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: { class_id: classId },
                    success: function (response) {
                        $('#class_arm').html(response);
                    }
                });
            });
        });
    </script>
</body>

</html>