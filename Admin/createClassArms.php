<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$teacher_id = $_SESSION['userId'];

//------------------------SAVE--------------------------------------------------
if (isset($_POST['save'])) {
  $selected_class = $_POST['class'];
  $subject_name = $_POST['subject'];

  // Check if the class already exists for the teacher
  $stmt = $conn->prepare("SELECT * FROM teacher_class_subject WHERE classRoomId = ? AND teacher_id = ?");
  $stmt->bind_param("ii", $selected_class, $teacher_id);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check for any errors during the execution
  if ($stmt->error) {
      $statusMsg = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
  } elseif ($result->num_rows > 0) {
      // Class already exists
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Class Already Exists!</div>";
  } else {
      // Insert the new class for the teacher
      $stmt = $conn->prepare("INSERT INTO teacher_class_subject (teacher_id, classRoomId, subject_name) VALUES (?, ?, ?)");
      $stmt->bind_param("iis", $teacher_id, $selected_class, $subject_name);

      if ($stmt->execute()) {
          // Insert was successful
          $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
      } else {
          // Insert failed
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred: " . $stmt->error . "</div>";
      }
  }
}


//--------------------EDIT------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  // Retrieve the existing data for the selected class
  $query = $conn->prepare("SELECT * FROM teacher_class_subject WHERE Id = ?");
  $query->bind_param("i", $Id);
  $query->execute();
  $result = $query->get_result();
  $row = $result->fetch_assoc();

  //------------UPDATE-----------------------------
  if (isset($_POST['update'])) {
    $selected_class = $_POST['class'];
    $subject_name = $_POST['subject'];

    // Update the teacher's class subject data using a prepared statement
    $updateQuery = $conn->prepare("UPDATE teacher_class_subject SET classRoomId = ?, subject_name = ? WHERE Id = ?");
    $updateQuery->bind_param("isi", $selected_class, $subject_name, $Id);

    if ($updateQuery->execute()) {
      // Redirect to the class list page after successful update
      echo "<script type='text/javascript'>
                  window.location = 'createClassArms.php';
                </script>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred: " . $conn->error . "</div>";
    }
  }
}


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];

  $query = mysqli_query($conn, "DELETE FROM teacher_class_subject WHERE Id='$Id'");

  if ($query == TRUE) {

    echo "<script type = \"text/javascript\">
                window.location = (\"createClassArms.php\")
                </script>";
  } else {

    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
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
  <link href="img/logo/logox.jpg" rel="icon">
  <?php include 'includes/title.php'; ?>
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
            <h1 class="h3 mb-0 text-gray-800">Add A classroom</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class Arms</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Arms</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>

                        <?php
                        // Fetch the existing class for the teacher if the `Id` is set (for editing)
                        $selectedClassId = "";
                        if (isset($Id)) {
                          $query = $conn->prepare("SELECT classRoomId FROM teacher_class_subject WHERE Id = ?");
                          $query->bind_param("i", $Id);
                          $query->execute();
                          $result = $query->get_result();
                          $row = $result->fetch_assoc();

                          if ($row) {
                            $selectedClassId = $row['classRoomId'];
                          }
                        }
                        ?>

                        <select required name="class" class="form-control mb-3">
                          <option value="">--Select Class--</option>
                          <?php
                          $querry = "SELECT * FROM tblclassarms";
                          $stmt = $conn->prepare($querry);
                          $stmt->execute();
                          $result = $stmt->get_result();

                          while ($row = $result->fetch_assoc()) {
                            $classOptionName = $row['classId'];
                            $classroom = "";

                            // Map classId to classroom names
                            if ($classOptionName == 1) {
                              $classroom = "Y1";
                            } else if ($classOptionName == 2) {
                              $classroom = "Y2";
                            } elseif ($classOptionName == 3) {
                              $classroom = "Y3";
                            }

                            // Use the correct value to preselect
                            $selected = ($row['Id'] == $selectedClassId) ? "selected" : "";


                            // Generate the option with preselection
                            echo "<option value='{$row['Id']}' {$selected}>{$classroom} - {$row['classArmName']}</option>";
                          }
                          ?>
                        </select>

                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Subject<span class="text-danger ml-2">*</span></label>

                        <?php
                        if (isset($Id)) {
                          // Prepare and execute query to get the current subject name
                          $query = $conn->prepare("SELECT subject_name FROM teacher_class_subject WHERE Id = ?");
                          $query->bind_param("i", $Id);
                          $query->execute();
                          $result = $query->get_result();
                          $row = $result->fetch_assoc();

                          if ($row) {
                            ?>
                            <input type="text" class="form-control" name="subject"
                              value="<?php echo $row['subject_name']; ?>" id="exampleInputFirstName"
                              placeholder="Assign a Subject">
                            <?php
                          } else {
                            ?>
                            <input type="text" class="form-control" name="subject" id="exampleInputFirstName"
                              placeholder="Assign a Subject">
                            <?php
                          }
                        } else {
                          ?>
                          <input type="text" class="form-control" name="subject" id="exampleInputFirstName"
                            placeholder="Assign a Subject">
                          <?php
                        }
                        ?>
                      </div>

                    </div>
                    <?php
                    if (isset($Id)) {
                      ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <?php
                    } else {
                      ?>
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                      <?php
                    }
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Class Arm</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Classroom Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php

                          // Query to get the teacher's classes and subjects, joined with class details
                          $query = "SELECT 
                          teacher_class_subject.Id, 
                          teacher_class_subject.classRoomId, 
                          teacher_class_subject.subject_name, 
                          teacher_class_subject.teacher_id,
                          tblclassArms.classId, 
                          tblclassArms.classArmName 
                          FROM teacher_class_subject
                          INNER JOIN tblclassArms 
                          ON tblclassArms.Id = teacher_class_subject.classRoomId
                          WHERE teacher_class_subject.teacher_id = ?";

                          // Prepare and execute the query
                          $stmt = $conn->prepare($query);
                          $stmt->bind_param("i", $teacher_id); // Bind the logged-in teacher's ID
                          $stmt->execute();
                          $rs = $stmt->get_result();
                          $num = $rs->num_rows;
                          $sn = 0;

                          if ($num > 0) {
                            // Loop through the teacher's classes and subjects
                            while ($row = $rs->fetch_assoc()) {
                              // Map classId to classroom name (Y1, Y2, Y3)
                              $classOptionName = $row['classId'];
                              echo "<!-- Debug: classId = " . $classOptionName . " -->";

                              $classroom = "";

                              // Debugging output
                              echo "<!-- Debug: classId = " . $classOptionName . " -->";  // Check what classId is being fetched
                          
                              if ($classOptionName == 1) {
                                $classroom = "Y1";
                              } elseif ($classOptionName == 2) {
                                $classroom = "Y2";
                              } elseif ($classOptionName == 3) {
                                $classroom = "Y3";
                              } else {
                                $classroom = "Unknown";  // Fallback for unexpected classIds
                              }

                              // Status based on whether the teacher has taken the class or not
                              $status = "Taken";

                              // Increment the serial number for each row
                              $sn = $sn + 1;

                              // Output the rows with the teacher's class and subject details
                              echo "
                              <tr>
                                  <td>" . $sn . "</td>
                                  <td>" . $classroom . " - " . $row['classArmName'] . "</td> <!-- Combined classroom and arm name -->
                                  <td>" . $row['subject_name'] . "</td>
                                  <td>" . $status . "</td>
                                  <td><a href='?action=edit&Id=" . $row['Id'] . "'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                  <td><a href='?action=delete&Id=" . $row['Id'] . "'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                            }

                          } else {
                            echo "
    <div class='alert alert-danger' role='alert'>
        No Record Found!
    </div>";
                          }
                          ?>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--Row-->

            <!-- Documentation Link -->
            <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

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