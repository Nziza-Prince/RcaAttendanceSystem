<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Date
$dateTaken = date("Y-m-d");

// Get class name and arm name
$classQuery = mysqli_query($conn, "SELECT className FROM tblclass WHERE Id = '$_SESSION[classId]'");
$className = mysqli_fetch_assoc($classQuery)['className'];

$armQuery = mysqli_query($conn, "SELECT classArmName FROM tblclassarms WHERE Id = '$_SESSION[classArmId]'");
$armName = mysqli_fetch_assoc($armQuery)['classArmName'];

// Get students sorted alphabetically
$studentQuery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$_SESSION[classId]' AND classArmId = '$_SESSION[classArmId]' ORDER BY lastName, firstName, otherName");

$filename = $className . "_" . $armName . "_" . $dateTaken . ".xls"; // Generate filename

// Set headers for file download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Output table headers
echo '<table border="1">
        <thead>
            <tr>
                <th colspan="5" style="font-size: 20px; font-weight: bold;">Attendance Report - ' . $className . ' (Class ' . $armName . ')</th>
            </tr>
            <tr>
                <th colspan="5" style="font-style: italic;">Date: ' . date("F j, Y", strtotime($dateTaken)) . '</th>
            </tr>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Other Name</th>
                <th>Admission No</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

$cnt = 1;
while ($row = mysqli_fetch_assoc($studentQuery)) {
    $status = "Absent"; // Default status
    $colour = "#FF0000"; // Default color
    // Check if student's attendance exists for the day
    $attendanceQuery = mysqli_query($conn, "SELECT status FROM tblattendance WHERE admissionNo = '".$row['admissionNumber']."' AND dateTimeTaken = '$dateTaken'");
    if(mysqli_num_rows($attendanceQuery) > 0) {
        $statusData = mysqli_fetch_assoc($attendanceQuery);
        if($statusData['status'] == '1') {
            $status = "Present";
            $colour = "#00FF00";
        }
    }
    // Output table row
    echo '<tr>
            <td>'.$row['firstName'].'</td>
            <td>'.$row['lastName'].'</td>
            <td>'.$row['otherName'].'</td>
            <td>'.$row['admissionNumber'].'</td>
            <td style="background-color: '.$colour.';">'.$status.'</td>
        </tr>';
    $cnt++;
}

echo '</tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="font-style: italic;">Powered by Spark Attendance</td>
            </tr>
        </tfoot>
    </table>';

// Generate and save the report
$reportContent = ob_get_clean(); // Get the generated HTML content
$reportFolder = 'C:\xampp\htdocs\Student-Attendance-System01-main/'; // Set the folder path for storing reports
$filePath = $reportFolder . $filename; // Set the full file path

// Save the report to the designated folder
file_put_contents($filePath, $reportContent);

// Check if the report was successfully saved
if (file_exists($filePath)) {
    echo "Report generated and saved successfully!";
} else {
    echo "Failed to generate and save the report.";
}
?>
