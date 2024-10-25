<?php
include '../Includes/dbcon.php';
ini_set('display_errors', '0');
include '../Includes/session.php';

$query = "SELECT tblclass.className,tblclassarms.classArmName 
FROM tblclassteacher
INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
Where tblclassteacher.Id = '$_SESSION[userId]'";

$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();

// Get current date
$currentDate = date("Y-m-d");





#-------------------------------------------------------------------------------------------------------------------------------------------------------
// Fetch all class attendance data for the graph
$query_all_classes = "
SELECT tblclass.className, tblclassarms.classArmName, 
       (COUNT(CASE WHEN tblattendance.status = '1' THEN 1 END) * 100.0 / (SELECT COUNT(*) FROM tblstudents WHERE classId = tblclass.Id AND classArmId = tblclassarms.Id)) as presence_percentage
FROM tblattendance
INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
WHERE DATE(tblattendance.dateTimeTaken) = '$currentDate'
GROUP BY tblattendance.classId, tblattendance.classArmId
ORDER BY presence_percentage DESC";

$result_all_classes = $conn->query($query_all_classes);

$classes = [];
$percentages = [];

// Fetch data for the graph
while ($row = $result_all_classes->fetch_assoc()) {
    $classes[] = $row['className'] . ' - ' . $row['classArmName'];
    $percentages[] = number_format($row['presence_percentage'], 2);
}






// Calculate total school attendance
$query_attendance = "SELECT COUNT(*) as total_present FROM tblattendance WHERE status = '1' AND DATE(dateTimeTaken) = '$currentDate' AND attendanceId = 1";
$result_attendance = $conn->query($query_attendance);
$row_attendance = $result_attendance->fetch_assoc();
$total_school_attendance = $row_attendance['total_present'];

// Calculate total school absence
$query_absence = "SELECT COUNT(*) as total_absent FROM tblattendance WHERE status IN ('0', '2') AND DATE(dateTimeTaken) = '$currentDate' AND attendanceId = 1";
$result_absence = $conn->query($query_absence);
$row_absence = $result_absence->fetch_assoc();
$total_school_absence = $row_absence['total_absent'];

// Calculate overall school attendance percentage
$query_total_students = "SELECT COUNT(*) as total_students FROM tblstudents";
$result_total_students = $conn->query($query_total_students);
$row_total_students = $result_total_students->fetch_assoc();
$total_students = $row_total_students['total_students'];

$overall_attendance_percentage = ($total_school_attendance / $total_students) * 100;

// Find best presence class
$query_best_class = "SELECT tblclass.className, tblclassarms.classArmName, 
                            COUNT(*) as present_count,
                            (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tblstudents WHERE classId = tblclass.Id AND classArmId = tblclassarms.Id)) as presence_percentage
                     FROM tblattendance
                     INNER JOIN tblclassarms ON tblattendance.classArmId = tblclassarms.Id
                     INNER JOIN tblclass ON tblattendance.classId = tblclass.Id
                     WHERE tblattendance.status = '1' AND DATE(tblattendance.dateTimeTaken) = '$currentDate' AND tblattendance.attendanceId = 1
                     GROUP BY tblattendance.classId, tblattendance.classArmId
                     ORDER BY presence_percentage DESC
                     LIMIT 1";
$result_best_class = $conn->query($query_best_class);
$row_best_class = $result_best_class->fetch_assoc();
$best_presence_class = $row_best_class['className'] . ' - ' . $row_best_class['classArmName'] . ' (' . number_format($row_best_class['presence_percentage'], 2) . '%)';

// Find lowest presence class
$query_lowest_class = "SELECT tblclass.className, tblclassarms.classArmName, 
                              COUNT(*) as absent_count,
                              (100.0 - (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tblstudents WHERE classId = tblclass.Id AND classArmId = tblclassarms.Id))) as presence_percentage
                       FROM tblattendance
                       INNER JOIN tblclassarms ON tblattendance.classArmId = tblclassarms.Id
                       INNER JOIN tblclass ON tblattendance.classId = tblclass.Id
                       WHERE tblattendance.status IN ('0', '2') AND DATE(tblattendance.dateTimeTaken) = '$currentDate' AND tblattendance.attendanceId = 1
                       GROUP BY tblattendance.classId, tblattendance.classArmId
                       ORDER BY presence_percentage ASC
                       LIMIT 1";

$result_lowest_class = $conn->query($query_lowest_class);
$row_lowest_class = $result_lowest_class->fetch_assoc();
$lowest_presence_class = $row_lowest_class['className'] . ' - ' . $row_lowest_class['classArmName'] . ' (' . number_format($row_lowest_class['presence_percentage'], 2) . '%)';


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
            <h1 class="h3 mb-0 text-gray-800">Teacher's Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Total School Attendance Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Attendance</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $total_school_attendance; ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Total School Absence Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Absence</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_school_absence; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-times fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Overall School Attendance Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Overall Attendance</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($overall_attendance_percentage, 2); ?>%
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-percentage fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Best Presence Class Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Best Presence Class</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $best_presence_class; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-trophy fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Lowest Presence Class Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Lowest Presence Class</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $lowest_presence_class; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Students Card -->
            <?php
            $query1 = mysqli_query($conn, "SELECT * from tblstudents");
            $students = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Class Card -->
            <?php
            $query1 = mysqli_query($conn, "SELECT * from tblclass");
            $class = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Class Arm Card -->
            <?php
            $query1 = mysqli_query($conn, "SELECT * from tblclassarms");
            $classArms = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Class Arms</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classArms; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-code-branch fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Row-->
        </div>
        <!---Container Fluid-->
       <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <canvas id="attendanceChart" width="400" height="200"></canvas>
</div>

<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($classes); ?>, // X-axis: Class names
            datasets: [{
                label: 'Class Attendance (%)',
                data: <?php echo json_encode($percentages); ?>, // Y-axis: Attendance percentages
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Classes'
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Attendance (%)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + '%';
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>


      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php'; ?>
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
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>
</body>

</html>

