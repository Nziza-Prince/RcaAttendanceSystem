<?php

include '../Includes/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['classId'])) {
    $classId = $_POST['classId'];
    $query = "SELECT Id, classArmName FROM tblclassarms WHERE classId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<option value=''>Select Class Arm</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['Id'] . "'>" . $row['classArmName'] . "</option>";
    }
}
?>