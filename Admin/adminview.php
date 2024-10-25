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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">View Class Attendance</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Class Attendance</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">View Class Attendance</h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="classId" id="classId">
                                                    <option value="">Select Class</option>
                                                    <?php
                                                    $query = "SELECT Id, className FROM tblclass";
                                                    $result = $conn->query($query);
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='" . $row['Id'] . "'>" . $row['className'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Class Arm<span class="text-danger ml-2">*</span></label>
                                                <select class="form-control" name="classArmId" id="classArmId">
                                                    <option value="">Select Class Arm</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                                                <input type="date" class="form-control" name="dateTaken" id="exampleInputFirstName" placeholder="Class Arm Name">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">View Attendance</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Records -->
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dateTaken']) && isset($_POST['classId']) && isset($_POST['classArmId'])) {
                        $dateTaken = $_POST['dateTaken'];
                        $classId = $_POST['classId'];
                        $classArmId = $_POST['classArmId'];

                        $query = "SELECT DISTINCT tblclassarms.Id AS classArmId, tblclass.Id AS classId, tblclass.className, tblclassarms.classArmName, tblattendance.teacherwti, tblattendance.timeTaken
                                  FROM tblattendance
                                  INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                  INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                  WHERE DATE(tblattendance.dateTimeTaken) = ? AND tblattendance.classId = ? AND tblattendance.classArmId = ?";
                        
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sii", $dateTaken, $classId, $classArmId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Check if any attendance records exist for the selected date
                        if ($result->num_rows > 0) {
                            echo "<div class='row'>";
                            while ($row = $result->fetch_assoc()) {
                                $classId = $row['classId'];
                                $classArmId = $row['classArmId'];
                                $className = $row['className'];
                                $classArmName = $row['classArmName'];
                                $teacherwti = $row['teacherwti'];
                                $timeTaken = $row['timeTaken'];
                                
                                echo "<div class='col-xl-3 col-md-6 mb-4'>
                                        <div class='card border-left-primary shadow h-100 py-2'>
                                            <div class='card-body'>
                                                <div class='row no-gutters align-items-center'>
                                                    <div class='col mr-2'>
                                                        <div class='text-xs font-weight-bold text-primary text-uppercase mb-1'>$className - $classArmName</div>
                                                        <div class='text-xs font-weight-bold text-secondary mb-1'>Class Monitor: $teacherwti</div>
                                                        <div class='text-xs font-weight-bold text-secondary mb-1'>Time: $timeTaken</div>
                                                    </div>
                                                    <div class='col-auto'>
                                                        <a href='admin_download.php?classId=$classId&classArmId=$classArmId&dateTaken=$dateTaken&teacherwti=$teacherwti&timeTaken=$timeTaken'><i class='fas fa-download'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p>No attendance records found for the selected date, class, and class arm.</p>";
                        }
                    }
                    ?>
                    <!-- End Attendance Records -->
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
            $('#classId').change(function() {
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
