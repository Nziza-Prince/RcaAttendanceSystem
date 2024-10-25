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

// Get today's date
$today = date("Y-m-d");

// Calculate total present
$query_present = mysqli_query($conn, "SELECT COUNT(*) as total_present FROM tblattendance WHERE status = '1' AND classId = '$_SESSION[classId]' AND classArmId = '$_SESSION[classArmId]' AND DATE(dateTimeTaken) = '$today'");
$row_present = mysqli_fetch_assoc($query_present);
$total_present = $row_present['total_present'];

// Calculate total absent
$query_absent = mysqli_query($conn, "SELECT COUNT(*) as total_absent FROM tblattendance WHERE (status = '0' OR status = '2') AND classId = '$_SESSION[classId]' AND classArmId = '$_SESSION[classArmId]' AND DATE(dateTimeTaken) = '$today'");
$row_absent = mysqli_fetch_assoc($query_absent);
$total_absent = $row_absent['total_absent'];

// Calculate overall class attendance
$total_students = $total_present + $total_absent;
$attendance_percentage = ($total_students > 0) ? round(($total_present / $total_students) * 100, 2) : 0;

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
            <h1 class="h3 mb-0 text-gray-800">Class Monitor Dashboard (<?php echo $rrw['className'].' - '.$rrw['classArmName'];?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- Total Present Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Present</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $total_present;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span>For <?php echo date("F j, Y"); ?></span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Total Absent Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Absent</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_absent;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span>For <?php echo date("F j, Y"); ?></span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-times fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Overall Class Attendance Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Overall Class Attendance</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $attendance_percentage;?>%</div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span>For <?php echo date("F j, Y"); ?></span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-percentage fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Total Students Card -->
            <?php 
            $query1=mysqli_query($conn,"SELECT * from tblstudents where classId = '$_SESSION[classId]' and classArmId = '$_SESSION[classArmId]'");                       
            $students = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Students</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $students;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span>Registered in class</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-graduate fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
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