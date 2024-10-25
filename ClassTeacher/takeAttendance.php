<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch class and arm names
$class_arm_query = "SELECT tblclass.className, tblclassarms.classArmName 
                    FROM tblclassteacher
                    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
                    INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
                    WHERE tblclassteacher.Id = ?";
$stmt = $conn->prepare($class_arm_query);
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$rs = $stmt->get_result();
$rrw = $rs->fetch_assoc();
$stmt->close();

// Session and Termp
$querey = $conn->query("SELECT * FROM tblsessionterm WHERE isActive ='1'");
$rwws = $querey->fetch_assoc();
$sessionTermId = $rwws['Id'];

$dateTaken = date("Y-m-d");

// Fetch the current time
$timeTaken = date("H:i:s");
$timeLimit = "07:15:00"; // Set the time limit

// Check if attendance submission is allowed based on the time
/*if ($timeTaken > $timeLimit) {
    $statusMsg = "<div class='alert alert-danger mt-3' role='alert'>Attendance can only be submitted before 7:15 AM.</div>";
} else {*/
    // Check if attendance record exists for today
    $attendance_check_query = "SELECT COUNT(*) AS attendanceCount FROM tblattendance WHERE classId = ? AND classArmId = ? AND DATE(dateTimeTaken) = ?";
    $stmt = $conn->prepare($attendance_check_query);
    $stmt->bind_param("iis", $_SESSION['classId'], $_SESSION['classArmId'], $dateTaken);
    $stmt->execute();
    $qurty = $stmt->get_result();
    $row = $qurty->fetch_assoc();
    $attendanceCount = $row['attendanceCount'];
    $stmt->close();

    if (isset($_POST['save'])) {
        $admissionNos = $_POST['admissionNo'];
        $statuses = $_POST['status'];
        $teacherwti = $_POST['teacherwti'];
        $subject = $_POST['subject'];

        if ($attendanceCount > 0) {
            $statusMsg = "<div class='alert alert-danger mt-3' role='alert'>Attendance already taken for today.</div>";
        } else {
            foreach ($admissionNos as $index => $admissionNo) {
                $status = intval($statuses[$index]); // Convert to integer

                $insert_attendance_query = "INSERT INTO tblattendance(admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken, timeTaken, teacherId, attendanceId, teacherwti, subject) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";
                $insert_stmt = $conn->prepare($insert_attendance_query);
                $insert_stmt->bind_param("siisissisi", $admissionNo, $_SESSION['classId'], $_SESSION['classArmId'], $sessionTermId, $status, $dateTaken, $timeTaken, $_SESSION['userId'], $teacherwti, $subject);
                $insert_stmt->execute();
                $insert_stmt->close();
            }

            $statusMsg = "<div class='alert alert-success mt-3' role='alert'>Attendance Taken Successfully!</div>";
        }
    }
//}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/rcalogo.png" rel="icon">
    <title>Dashboard</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        input[type="checkbox"] {
            transform: scale(1.8);
            margin-right: 10px;
        }

        .authorize-btn {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>
    <script>
    $(document).ready(function() {
        // Handle the Check All checkbox
        $('#checkAll').change(function() {
            var checked = this.checked;
            $('.present-checkbox').prop('checked', checked);
            $('.present-checkbox').change(); // Trigger change event to update statuses
        });

        $('.present-checkbox').change(function() {
            var $row = $(this).closest('tr');
            var $authorizeBtn = $row.find('.authorize-btn');
            var $statusInput = $row.find('.status-input');

            if (this.checked) {
                $authorizeBtn.hide();
                $statusInput.val('1'); // Present
            } else {
                $authorizeBtn.show().text('Authorize Absence').removeClass('btn-success').addClass('btn-warning');
                $statusInput.val('0'); // Absent
            }
        });

        $('.authorize-btn').click(function(e) {
            e.preventDefault();
            var $row = $(this).closest('tr');
            var $presentCheckbox = $row.find('.present-checkbox');
            var $statusInput = $row.find('.status-input');

            if ($(this).hasClass('btn-warning')) {
                $(this).text('✓ Authorized').removeClass('btn-warning').addClass('btn-success');
                $statusInput.val('2'); // Authorized Absence
            } else {
                $(this).text('Authorize Absence').removeClass('btn-success').addClass('btn-warning');
                $statusInput.val('0'); // Absent
            }
            $presentCheckbox.prop('checked', false);
        });
    });
    </script>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include "Includes/sidebar.php"; ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php"; ?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date: <?php echo date("m-d-Y"); ?>)</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Students in Class</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card mb-4">
                                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                <h6 class="m-0 font-weight-bold text-primary">All Students in (<?php echo $rrw['className'].' - '.$rrw['classArmName']; ?>) Class</h6>
                                                <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes beside each student to take attendance!</i></h6>
                                            </div>
                                            <div class="table-responsive p-3">
                                                <?php if (isset($statusMsg)) echo $statusMsg; ?>

                                                <div class="form-group">
                                                    <label for="teacherwti">Class Monitor: </label>
                                                    <input type="text" name="teacherwti" id="teacherwti" class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="subject">Day of the week:</label>
                                                    <input type="text" name="subject" id="subject" class="form-control" required>
                                                </div>

                                                <table class="table align-items-center table-flush table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th><input type="checkbox" id="checkAll"> Present</th>
                                                            <th>Authorize Absence</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $students_query = "SELECT tblstudents.admissionNumber, tblclass.className, tblclass.Id AS classId, tblclassarms.classArmName, tblclassarms.Id AS classArmId, tblstudents.firstName, tblstudents.lastName
                                                                           FROM tblstudents
                                                                           INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                                                                           INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
                                                                           WHERE tblstudents.classId = ? AND tblstudents.classArmId = ?";
                                                        $stmt = $conn->prepare($students_query);
                                                        $stmt->bind_param("ii", $_SESSION['classId'], $_SESSION['classArmId']);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($row = $result->fetch_assoc()) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $cnt; ?></td>
                                                                <td><?php echo $row['firstName']; ?></td>
                                                                <td><?php echo $row['lastName']; ?></td>
                                                                <td><input type="checkbox" class="present-checkbox">
                                                                    <input type="hidden" name="status[]" class="status-input" value="0">
                                                                    <input type="hidden" name="admissionNo[]" value="<?php echo $row['admissionNumber']; ?>">
                                                                </td>
                                                                <td><button class="btn btn-warning authorize-btn" style="display: none;">Authorize Absence</button></td>
                                                            </tr>
                                                        <?php
                                                            $cnt++;
                                                        }
                                                        $stmt->close();
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <button type="submit" name="save" class="btn btn-primary">Submit Attendance</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!---Container Fluid-->
            </div>
            <!-- Footer -->
            <?php include "Includes/footer.php"; ?>
            <!-- Footer -->
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
</body>

</html>