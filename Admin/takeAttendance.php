<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

if (!isset($_SESSION['userId'])) {
    die('User not logged in');
}

$teacher_id = $_SESSION['userId'];

// Fetch class and arm names
$class_arm_query = "SELECT tblclass.className, tblclassArms.classArmName 
                    FROM tblclassteacher
                    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
                    INNER JOIN tblclassArms ON tblclassArms.Id = tblclassteacher.classArmId
                    WHERE tblclassteacher.Id = ?";
$stmt = $conn->prepare($class_arm_query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$rs = $stmt->get_result();
$rrw = $rs->fetch_assoc();
$stmt->close();



// Fetch active session and term
$query = $conn->query("SELECT * FROM tblsessionterm WHERE isActive ='1'");
$rwws = $query->fetch_assoc();
$sessionTermId = $rwws['Id'];

if (!$sessionTermId) {
    die('Active session term not found.');
}

$dateTaken = date("Y-m-d");
$timeTaken = date("H:i:s");

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
    // Retrieve and validate form inputs
    $admissionNos = $_POST['admissionNo'] ?? [];
    $statuses = $_POST['attendanceStatus'] ?? [];
    $teacherwti = $_POST['teacherwti'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $day = $_POST['weekday'] ?? null;

    echo "<pre>";
    print_r($admissionNos);
    print_r($statuses);
    echo "</pre>";

    // Check if no students are selected
    if (empty($admissionNos)) {
        echo "No students selected for attendance.";
        return;
    }

    // Ensure teacherwti (classArmId) is provided and validate it
    $classArmId = intval($teacherwti);
    if (!$classArmId) {
        echo "Invalid classArmId.";
        return;
    }

    // Fetch classId associated with classArmId
    $class_fetch_query = "SELECT classId FROM tblclassArms WHERE Id = ?";
    $stmt = $conn->prepare($class_fetch_query);
    $stmt->bind_param("i", $classArmId);
    $stmt->execute();
    $result = $stmt->get_result();
    $classRow = $result->fetch_assoc();
    $classId = $classRow['classId'] ?? null;
    $stmt->close();

    if (!$classId) {
        echo "Class not found.";
        return;
    }

    // Check if attendance has already been taken for today
    if ($attendanceCount > 0) {
        $statusMsg = "<div class='alert alert-danger mt-3' role='alert'>Attendance already taken for today.</div>";
    } else {
        // Process attendance status for each student
        foreach ($statuses as $admissionNo => $status) {
            $status = intval($status); // Convert to integer for safety

            // Debug output for each student
            echo "Processing Admission No: $admissionNo, Status: $status<br>";

            // Check if attendance record already exists
            $check_query = "SELECT COUNT(*) AS existsCount FROM tblattendance WHERE admissionNo = ? AND DATE(dateTimeTaken) = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("ss", $admissionNo, $dateTaken);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $attendanceExists = $check_result->fetch_assoc()['existsCount'] > 0;
            $check_stmt->close();

            // Skip if attendance already exists
            if ($attendanceExists) {
                echo "<div class='alert alert-warning mt-3' role='alert'>Attendance for admission number {$admissionNo} already exists for today.</div>";
                continue;
            }

            // Prepare to insert a new attendance record
            $insert_attendance_query = "INSERT INTO tblattendance (admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken, timeTaken, teacherId, attendanceId, teacherwti, subject, weekday) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_attendance_query);
            $insert_stmt->bind_param("siisissisii", $admissionNo, $classId, $classArmId, $sessionTermId, $status, $dateTaken, $timeTaken, $_SESSION['userId'], $teacherwti, $subject, $day);
            
            // Execute and check for errors
            if (!$insert_stmt->execute()) {
                echo "Insert failed: " . htmlspecialchars($insert_stmt->error) . "<br>";
            } else {
                echo "Attendance saved for Admission No: {$admissionNo}, Status: {$status}<br>";
            }

            $insert_stmt->close();
        }

        $statusMsg = "<div class='alert alert-success mt-3' role='alert'>Attendance Taken Successfully!</div>";
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
    // Listen for changes in the attendance dropdown
    $('.attendanceSelect').change(function() {
        var admissionNo = $(this).data('admission');
        var status = $(this).val();

        // Toggle the Authorize Absence button based on the selected status
        if (status == '0') {
            $('#auth-' + admissionNo).show();
        } else {
            $('#auth-' + admissionNo).hide();
        }
    });

    // Optional: Action when the authorize absence button is clicked
    $('.authorizeAbsenceBtn').click(function() {
        var admissionNo = $(this).data('admission');
        alert('Absence authorized for Admission No: ' + admissionNo);
        // You can further enhance this to perform AJAX or form submission if needed
    });
});


            // Handle Authorize Absence button clicks
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
                $presentCheckbox.prop('checked', false);
            }
        });
        
    </script>

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
                        <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date: <?php echo date("m-d-Y"); ?>)
                        </h1>
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
                                            <div
                                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                <h6 class="m-0 font-weight-bold text-primary">All Students in Class</h6>
                                                <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the
                                                        checkboxes beside each student to take attendance!</i></h6>
                                            </div>
                                            <div class="table-responsive p-3">
                                                <?php if (isset($statusMsg))
                                                    echo $statusMsg; ?>

                                                <div class="form-group">
                                                    <label for="weekday">Select Weekday</label>
                                                    <select class="form-control" name="weekday" id="weekday" required>
                                                        <option value="" disabled selected>Select a Weekday</option>
                                                        <option value="Monday">Monday</option>
                                                        <option value="Tuesday">Tuesday</option>
                                                        <option value="Wednesday">Wednesday</option>
                                                        <option value="Thursday">Thursday</option>
                                                        <option value="Friday">Friday</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="teacherwti">Choose a Class: </label>
                                                    <select type="text" name='teacherwti' id='teacherwti'
                                                        class="form-control" required>
                                                        <option value="">Select Class</option>
                                                        <?php
                                                        // Assume $teacher_id is set based on the logged-in teacher's ID.
                                                        echo "Teacher ID: " . htmlspecialchars($_SESSION['userId']) . "<br>"; // Debugging output
                                                        
                                                        // Update query to correctly retrieve the class arms based on teacher_id
                                                        $query = "SELECT tblclassArms.Id,
                                                        tblclassArms.classId,
                                                        tblclassArms.classArmName 
                                                        FROM tblclassArms 
                                                        INNER JOIN teacher_class_subject 
                                                        ON teacher_class_subject.classRoomId = tblclassArms.Id 
                                                        WHERE teacher_class_subject.teacher_id = ?";

                                                        $stmt = $conn->prepare($query);

                                                        if (!$stmt) {
                                                            echo "Query prepare failed: " . htmlspecialchars($conn->error) . "<br>"; // Debugging output
                                                        }

                                                        $stmt->bind_param("i", $_SESSION['userId']); // Bind the logged-in teacher's ID
                                                        if (!$stmt->execute()) {
                                                            echo "Query execution failed: " . htmlspecialchars($stmt->error) . "<br>"; // Debugging output
                                                        }

                                                        $result = $stmt->get_result();
                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $classOptionName = $row['classId'];
                                                                $classroom = "";

                                                                // Map classId to classroom name
                                                                switch ($classOptionName) {
                                                                    case 1:
                                                                        $classroom = "Y1";
                                                                        break;
                                                                    case 2:
                                                                        $classroom = "Y2";
                                                                        break;
                                                                    case 3:
                                                                        $classroom = "Y3";
                                                                        break;
                                                                    default:
                                                                        $classroom = "";
                                                                }

                                                                echo "<option value='{$row['Id']}'>{$classroom} - {$row['classArmName']}</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>No classes found</option>"; // No results found
                                                        }

                                                        $stmt->close(); // Close the statement
                                                        ?>
                                                    </select>

                                                </div>

                                                <table class="table align-items-center table-flush" id="studentsTable">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th>admissionNo</th>
                                                            <th scope="col">First Name</th>
                                                            <th scope="col">Lasn Name</th>
                                                            <th scope="col"><input type="checkbox" id="checkAll">
                                                                Present</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Student rows will be populated here by AJAX -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" name="save" class="btn btn-primary">Save
                                            Attendance</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php include "Includes/footer.php"; ?>
            <!-- Footer -->
        </div>
    </div>

    <script>
        $('#teacherwti').change(function () {
            var classArmId = $(this).val();
            if (classArmId) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_students.php',
                    data: { classArmId: classArmId },
                    success: function (response) {
                        $('#studentsTable tbody').html(response);
                    }
                });
            } else {
                $('#studentsTable tbody').html('<tr><td colspan="5">Please select a class.</td></tr>');
            }
        });

    </script>
    <!-- Scripts -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/ruang-admin.min.js"></script>
</body>

</html>