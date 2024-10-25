<?php
include '../Includes/dbcon.php';

if (isset($_POST['classArmId'])) {
    $classroomId = trim($_POST['classArmId']); // Sanitize input

    // Check if classroom ID is a valid integer
    if (is_numeric($classroomId)) {
        $query = "SELECT * FROM tblstudents WHERE classArmId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $classroomId);
        $stmt->execute();
        $result = $stmt->get_result();

        $output = '';
        if ($result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                // Sanitize output for HTML rendering to prevent XSS
                $firstName = htmlspecialchars($row['firstName']);
                $lastName = htmlspecialchars($row['lastName']);
                $admissionNo = htmlspecialchars($row['admissionNumber']); // Admission number

                $output .= '<tr>
                                <td>' . $i++ . '</td>
                                <td>' . $admissionNo . '</td>
                                <td>' . $firstName . '</td>
                                <td>' . $lastName . '</td>
                                <td>
                                  <input type="hidden" name="attendanceStatus[' . $admissionNo . ']" value="0">
                                  <input type="checkbox" name="attendanceStatus[' . $admissionNo . ']" value="1" class="present-checkbox">

                                 </td>
                                <td>
                                    <button class="btn btn-warning authorize-btn">Authorize Absence</button>
                                </td>
                            </tr>';

            }
        } else {
            $output .= '<tr><td colspan="5">No students found for this class.</td></tr>';
        }
        echo $output;
    } else {
        // Handle invalid classArmId input
        echo '<tr><td colspan="5">Invalid class ID provided.</td></tr>';
    }
}
