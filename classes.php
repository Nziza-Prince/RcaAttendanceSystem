<?php
include 'Includes/dbcon.php';
include 'Includes/session.php';

// Check if the teacher is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

$teacher_id = $_SESSION['userId'];

// Fetch all available class arms
$querry = "SELECT * FROM tblclassArms";
$stmt = $conn->prepare($querry);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the number of rows to insert (dynamic form fields)
    $numRows = count($_POST['class']);

    for ($i = 0; $i < $numRows; $i++) {
        $selected_class = $_POST['class'][$i];
        $subject_name = $_POST['subject'][$i];

        // Insert into database
        $sql = "INSERT INTO teacher_class_subject (teacher_id, classRoomId, subject_name) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $teacher_id, $selected_class, $subject_name);

        if ($stmt->execute()) {
            echo "<p class='success'>Class and subject assigned successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Classes and Subjects</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            align-items: center;
        }

        select,
        input[type="text"] {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        select:focus,
        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        button {
            padding: 12px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            transition: all 0.3s ease-in;
        }

        button:hover {
            background: blue;
        }

        .form-group button {
            background-color: #e74c3c;
            padding: 10px;
            flex: 0;
            transition: all 0.3s ease-in;
        }

        .form-group button:hover {
            background: blue;
        }

        .success,
        .error {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            border-radius: 5px;
        }

        .success {
            color: #2ecc71;
            background-color: #dff0d8;
            border: 1px solid #d0e9c6;
        }

        .error {
            color: #e74c3c;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
        }

        /* Add more button */
        .add-more {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .submit {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            /* Remove underline */
            border: 1px solid dodgerblue;
            height: 3rem;
            width: 3rem;
            border-radius: 50%;
            background: dodgerblue;
            transition: all 0.3s ease-in;
        }

        a:hover {
            background: blue;
        }

        .fas {
            font-size: 2rem;
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script>
        function addMoreClass() {
            const container = document.querySelector('.form-container');
            const newFormGroup = document.createElement('div');
            newFormGroup.className = 'form-group';
            newFormGroup.innerHTML = `
                <select name="class[]" required>
                <?php
                $querry = "SELECT * FROM tblclassarms";
                $stmt = $conn->prepare($querry);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $classOptionName = $row['classId'];
                    $classroom = "";

                    if ($classOptionName == 1) {
                        $classroom = "Y1";
                    } else if ($classOptionName == 2) {
                        $classroom = "Y2";
                    } else {
                        $classroom = "Y3";
                    }

                    echo "<option value='{$row['Id']}'>{$classroom} - {$row['classArmName']}</option>";
                }
                ?>
                </select>
                <input type="text" name="subject[]" placeholder="Subject Name" required>
                <button type="button" onclick="removeClass(this)">Remove</button>
            `;
            container.appendChild(newFormGroup);
        }

        function removeClass(button) {
            button.parentElement.remove(); // Remove the selected form group
        }
    </script>
</head>

<body>
    <header>
        <h1>Assign Classes and Subjects</h1>
    </header>
    <div class="container">
        <form method="POST">
            <div class="form-container">
                <div class="form-group">
                    <select name="class[]" required>
                        <option value="">Select Class
                        </option>
                        <?php
                        $querry = "SELECT * FROM tblclassarms";
                        $stmt = $conn->prepare($querry);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $classOptionName = $row['classId'];
                            $classroom = "";

                            if ($classOptionName == 1) {
                                $classroom = "Y1";
                            } else if ($classOptionName == 2) {
                                $classroom = "Y2";
                            } else {
                                $classroom = "Y3";
                            }

                            echo "<option value='{$row['Id']}'>{$classroom} - {$row['classArmName']}</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="subject[]" placeholder="Subject Name" required>
                </div>
            </div>
            <div class="add-more">
                <button type="button" onclick="addMoreClass()">Add More Classes and Subjects</button>
            </div>
            <div class="submit">
                <button type="submit">Assign Subject</button>
                <a href="./Admin/index.php"><i class="fas fa-arrow-right"></i></a>
            </div>
        </form>
    </div>
</body>

</html>