<?php 
error_reporting(0);
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
        html, body {
            height: 100%;
        }
        #wrapper {
            min-height: 100%;
            position: relative;
        }
        #content-wrapper {
            flex: 1 0 auto;
        }
        #footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            line-height: 60px;
            background-color: #f5f5f5;
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
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                                                <input type="date" class="form-control" name="dateTaken" id="exampleInputFirstName" placeholder="Class Arm Name">
                                            </div>
                                        </div>
                                        <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Input Group -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">Class Attendance</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <?php
                                            if(isset($_POST['view'])){
                                                $dateTaken = $_POST['dateTaken'];
                                                $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblattendance.teacherwti, tblattendance.timeTaken, tblattendance.subject,
                                                    tblclass.className, tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                                                    tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                                                    FROM tblattendance
                                                    INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                                    INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                                    INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                                                    INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                                                    INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                                                    WHERE tblattendance.dateTimeTaken = '$dateTaken' AND tblattendance.classId = '$_SESSION[classId]' AND tblattendance.classArmId = '$_SESSION[classArmId]'
                                                    ORDER BY tblattendance.teacherwti, tblattendance.timeTaken";
                                                $rs = $conn->query($query);
                                                $num = $rs->num_rows;
                                                if($num > 0) {
                                                    $currentTeacher = '';
                                                    $currentTime = '';
                                                    while ($rows = $rs->fetch_assoc()) {
                                                        if($currentTeacher != $rows['teacherwti'] || $currentTime != $rows['timeTaken']) {
                                                            if($currentTeacher != '') {
                                                                echo '</tbody></table>';
                                                            }
                                                            $currentTeacher = $rows['teacherwti'];
                                                            $currentTime = $rows['timeTaken'];
                                                            echo '<h5 class="mt-4">'.$currentTeacher.' - '.$currentTime.'</h5>';
                                                            echo '<table class="table align-items-center table-flush table-hover" id="dataTableHover">';
                                                            echo '<thead class="thead-light">
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>First Name</th>
                                                                        <th>Last Name</th>
                                                                        <th>Other Name</th>
                                                                        <th>Admission No</th>
                                                                        <th>Session</th>
                                                                        <th>Term</th>
                                                                        <th>Status</th>
                                                                        <th>Date</th>
                                                                        <th>Subject</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>';
                                                        }
                                                        $status = $rows['status'] == '1' ? 'Present' : 'Absent';
                                                        $colour = $rows['status'] == '1' ? '#00FF00' : '#FF0000';
                                                        echo '<tr>
                                                                <td>'.(++$sn).'</td>
                                                                <td>'.$rows['firstName'].'</td>
                                                                <td>'.$rows['lastName'].'</td>
                                                                <td>'.$rows['otherName'].'</td>
                                                                <td>'.$rows['admissionNumber'].'</td>
                                                                <td>'.$rows['sessionName'].'</td>
                                                                <td>'.$rows['termName'].'</td>
                                                                <td style="background-color:'.$colour.'">'.$status.'</td>
                                                                <td>'.$rows['dateTimeTaken'].'</td>
                                                                <td>'.$rows['subject'].'</td>
                                                              </tr>';
                                                    }
                                                    echo '</tbody></table>';
                                                } else {
                                                    echo '<p>No Record Found!</p>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer id="footer">
            <?php include "Includes/footer.php";?>
        </footer>
        <!-- Footer -->
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable(); // ID From dataTable 
            $('#dataTableHover').DataTable(); // ID From dataTable with Hover
        });
    </script>
</body>

</html>
