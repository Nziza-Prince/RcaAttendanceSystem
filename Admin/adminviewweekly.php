<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/logox.jpg" rel="icon">
    <title>Dashboard</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        td.present {
            background-color: lightgreen;
            color: green;
        }
        td.absent {
            background-color: lightcoral;
            color: red;
        }
        td.auth-absent {
            background-color: lightyellow;
            color: orange;
        }
        .no-column {
            width: 1px;
            white-space: nowrap;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include "Includes/sidebar.php";?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php";?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="container">
                        <h1>View Attendance by Date Range</h1>
                        <form method="post">
                            <div class="form-group">
                                <label for="class_id">Select Class:</label>
                                <select class="form-control" id="class_id" name="class_id">
                                    <option value="">Select Class</option>
                                    <?php
                                    $query = "SELECT Id, className FROM tblclass";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='". $row['Id'] . "'>". $row['className'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="classArmId">Select Class Arm:</label>
                                <select class="form-control" id="classArmId" name="classArmId">
                                    <option value="">Select Class Arm</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <?php
                        if (isset($_POST['submit'])) {
                            $class_id = $_POST['class_id'];
                            $classArmId = $_POST['classArmId'];
                            $start_date = $_POST['start_date'];
                            $end_date = $_POST['end_date'];

                            $query = "SELECT s.Id, s.firstName, s.lastName FROM tblstudents s WHERE s.classId = ? AND s.classArmId = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ii", $class_id, $classArmId);
                            $stmt->execute();
                            $students = $stmt->get_result();

                            $dates = [];
                            $query = "SELECT DISTINCT DATE(dateTimeTaken) as attendance_date FROM tblattendance WHERE classId = ? AND classArmId = ? AND dateTimeTaken BETWEEN ? AND ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("iiss", $class_id, $classArmId, $start_date, $end_date);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                $dates[] = $row["attendance_date"];
                            }

                            if (empty($dates)) {
                                echo "<p>No attendance records found for the selected criteria.</p>";
                            } else {
                                echo "<table border='1'>";
                                echo "<tr><th class='no-column'></th><th>Student Name</th>";
                                foreach ($dates as $date) {
                                    echo "<th>". $date . "</th>";
                                }
                                echo "</tr>";
                                $i = 1;
                                while ($student = $students->fetch_assoc()) {
                                    echo "<tr><td class='no-column'>". $i++ . "</td><td>". $student["firstName"] . ' ' . $student["lastName"] . "</td>";
                                    foreach ($dates as $date) {
                                        $query = "SELECT status FROM tblattendance WHERE admissionNo = ? AND classId = ? AND classArmId = ? AND DATE(dateTimeTaken) = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("iiis", $student["Id"], $class_id, $classArmId, $date);
                                        $stmt->execute();
                                        $attendance = $stmt->get_result()->fetch_assoc();
                                        if ($attendance) {
                                            $status = $attendance["status"];
                                            $class = "";
                                            if ($status == 1) {
                                                $class = "present";
                                                $status = "Present";
                                            } elseif ($status == 0) {
                                                $class = "absent";
                                                $status = "Absent";
                                            } elseif ($status == 2) {
                                                $class = "auth-absent";
                                                $status = "Auth. Absent";
                                            }
                                            echo "<td class='". $class . "'>". $status . "</td>";
                                        } else {
                                            echo "<td>-</td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <!-- End Container Fluid-->
            </div>
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#class_id').change(function() {
                var classId = $(this).val();
                $.ajax({
                    url: './ajaxClassArms3.php',
                    type: 'POST',
                    data: {classId: classId},
                    success: function(data) {
                        $('#classArmId').html(data);
                    }
                });
            });
        });
    </script>
</body>

</html>