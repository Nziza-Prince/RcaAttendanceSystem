<?php
// ob_start(); // Start output buffering

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// include '../Includes/dbcon.php';
// include '../Includes/session.php';

// if (isset($_GET['classId']) && isset($_GET['classArmId']) && isset($_GET['dateTaken']) && isset($_GET['teacherwti']) && isset($_GET['timeTaken'])) {
//     $classId = $_GET['classId'];
//     $classArmId = $_GET['classArmId'];
//     $dateTaken = $_GET['dateTaken'];
//     $teacherwti = $_GET['teacherwti'];
//     $timeTaken = $_GET['timeTaken'];

//     $classQuery = mysqli_query($conn, "SELECT className FROM tblclass WHERE Id = '$classId'");
//     $className = mysqli_fetch_assoc($classQuery)['className'];

//     $armQuery = mysqli_query($conn, "SELECT classArmName FROM tblclassarms WHERE Id = '$classArmId'");
//     $armName = mysqli_fetch_assoc($armQuery)['classArmName'];

//     $studentQuery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$classId' AND classArmId = '$classArmId' ORDER BY firstName");

//     require_once('../tcpdf/tcpdf.php');
//     $pdf = new TCPDF();
//     $pdf->SetCreator(PDF_CREATOR);
//     $pdf->SetAuthor('Your Name');
//     $pdf->SetTitle('Attendance Report');
//     $pdf->SetSubject('PDF Subject');
//     $pdf->SetKeywords('TCPDF, PDF, attendance, report');
//     $pdf->SetHeaderData('', 0, 'Rwanda Coding Academy', 'Attendance Report');
//     $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//     $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//     $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//     $pdf->SetMargins(15, 30, 15);
//     $pdf->SetHeaderMargin(5);
//     $pdf->SetFooterMargin(10);
//     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//     $pdf->AddPage();
//     $pdf->SetFont('helvetica', '', 12);

//     $html = '<h1 style="text-align: center;">Rwanda Coding Academy</h1>
//              <h2 style="text-align: center;">Attendance Report</h2>
//              <p style="text-align: center;"><strong>Class:</strong> ' . htmlspecialchars($className) . '<br>
//              <strong>Class Arm:</strong> ' . htmlspecialchars($armName) . '<br>
//              <strong>Date:</strong> ' . htmlspecialchars($dateTaken) . '<br>
//              <strong>Class Monitor:</strong> ' . htmlspecialchars($teacherwti) . '<br>
//              <strong>Time Taken:</strong> ' . htmlspecialchars($timeTaken) . '</p>';

//     $html .= '<table border="1" cellspacing="0" cellpadding="5">
//                 <thead>
//                     <tr>
//                         <th>#</th>
//                         <th>First Name</th>
//                         <th>Last Name</th>
//                         <th>Status</th>
//                     </tr>
//                 </thead>
//                 <tbody>';

//     $cnt = 1;
//     while ($row = mysqli_fetch_assoc($studentQuery)) {
//         $status = "Absent";
//         $colour = "#FF0000";
//         $attendanceQuery = mysqli_query($conn, "SELECT status FROM tblattendance WHERE admissionNo = '" . $row['admissionNumber'] . "' AND dateTimeTaken = '$dateTaken' AND teacherwti = '$teacherwti' AND timeTaken = '$timeTaken'");
//         if (mysqli_num_rows($attendanceQuery) > 0) {
//             $statusData = mysqli_fetch_assoc($attendanceQuery);
//             if ($statusData['status'] == 1) {
//                 $status = "Present";
//                 $colour = "#00FF00";
//             } elseif ($statusData['status'] == 2) {
//                 $status = "Auth. Absent";
//                 $colour = "#FFFF00";
//             }
//         }
//         $html .= '<tr>
//                     <td>' . $cnt . '</td>
//                     <td>' . htmlspecialchars($row['firstName']) . '</td>
//                     <td>' . htmlspecialchars($row['lastName']) . '</td>
//                     <td style="background-color: ' . $colour . ';">' . htmlspecialchars($status) . '</td>
//                 </tr>';
//         $cnt++;
//     }

//     $html .= '</tbody>
//                 <tfoot>
//                     <tr>
//                         <td colspan="4" style="text-align: center; font-style: italic;">Powered by Spark Attendance</td>
//                     </tr>
//                 </tfoot>
//             </table>';

//     $pdf->writeHTML($html, true, false, true, false, '');
//     $pdf->Output($className . "_" . $armName . "_" . $dateTaken . ".pdf", 'D');

//     ob_end_flush(); // Send output buffer content
//     exit;
// } else {
//     header("Location: adminview.php");
//     exit();
// }
?>

<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_GET['classId']) && isset($_GET['classArmId']) && isset($_GET['dateTaken']) && isset($_GET['teacherwti']) && isset($_GET['timeTaken'])) {
    $classId = $_GET['classId'];
    $classArmId = $_GET['classArmId'];
    $dateTaken = $_GET['dateTaken'];
    $teacherwti = $_GET['teacherwti'];
    $timeTaken = $_GET['timeTaken'];

    $classQuery = mysqli_query($conn, "SELECT className FROM tblclass WHERE Id = '$classId'");
    $className = mysqli_fetch_assoc($classQuery)['className'];

    $armQuery = mysqli_query($conn, "SELECT classArmName FROM tblclassarms WHERE Id = '$classArmId'");
    $armName = mysqli_fetch_assoc($armQuery)['classArmName'];

    $studentQuery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$classId' AND classArmId = '$classArmId' ORDER BY firstName");

    require_once('../tcpdf/tcpdf.php');
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Attendance Report');
    $pdf->SetSubject('PDF Subject');
    $pdf->SetKeywords('TCPDF, PDF, attendance, report');
    $pdf->SetHeaderData('', 0, 'Rwanda Coding Academy', 'Attendance Report');
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 30, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h1 style="text-align: center;">Rwanda Coding Academy</h1>
             <h2 style="text-align: center;">Attendance Report</h2>
             <p style="text-align: center;"><strong>Class:</strong> ' . htmlspecialchars($className) . '<br>
             <strong>Class Arm:</strong> ' . htmlspecialchars($armName) . '<br>
             <strong>Date:</strong> ' . htmlspecialchars($dateTaken) . '<br>
             <strong>Class Monitor:</strong> ' . htmlspecialchars($teacherwti) . '<br>
             <strong>Time Taken:</strong> ' . htmlspecialchars($timeTaken) . '</p>';

    $html .= '<table border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

    $cnt = 1;
    while ($row = mysqli_fetch_assoc($studentQuery)) {
        $status = "Absent";
        $colour = "#FF0000";
        $attendanceQuery = mysqli_query($conn, "SELECT status FROM tblattendance WHERE admissionNo = '" . $row['admissionNumber'] . "' AND dateTimeTaken = '$dateTaken' AND teacherwti = '$teacherwti' AND timeTaken = '$timeTaken'");
        if (mysqli_num_rows($attendanceQuery) > 0) {
            $statusData = mysqli_fetch_assoc($attendanceQuery);
            if ($statusData['status'] == 1) {
                $status = "Present";
                $colour = "#00FF00";
            } elseif ($statusData['status'] == 2) {
                $status = "Auth. Absent";
                $colour = "#FFFF00";
            }
        }
        $html .= '<tr>
                    <td>' . $cnt . '</td>
                    <td>' . htmlspecialchars($row['firstName']) . '</td>
                    <td>' . htmlspecialchars($row['lastName']) . '</td>
                    <td style="background-color: ' . $colour . ';">' . htmlspecialchars($status) . '</td>
                </tr>';
        $cnt++;
    }
    $html .= '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: center; font-style: italic;">Powered by Spark Attendance</td>
                    </tr>
                </tfoot>
            </table>';
    $pdfFilePath = realpath('../uploads/') . DIRECTORY_SEPARATOR . $className . "_" . $armName . "_" . str_replace('-', '_', $dateTaken) . ".pdf";

    if (!is_writable(realpath('../uploads/'))) {
        die('Uploads directory is not writable.');
    }

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($pdfFilePath, 'F'); // Ensure this is set to 'F'

    ob_end_flush();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>

<body>
    <iframe
        src="../uploads/<?php echo htmlspecialchars($className . "_" . $armName . "_" . str_replace('-', '_', $dateTaken) . ".pdf"); ?>"
        frameborder="0" style="width:100%; height:600px;"></iframe>

</body>

</html>