<?php
// ==========================================
// Assignment: Student Registration Form
// This program collects student details,
// validates input, calculates grade,
// assigns remarks, and displays results.
// ==========================================

// ----------------------------
// INITIALIZE VARIABLES
// ----------------------------
$name = $email = $age = $gender = $course = $marks = "";
$errors = []; // Array to store validation errors
$grade = "";
$remark = "";

// ----------------------------
// CHECK IF FORM IS SUBMITTED
// ----------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ----------------------------
    // COLLECT FORM DATA SAFELY
    // ----------------------------
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $age = $_POST["age"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $course = $_POST["course"] ?? "";
    $marks = $_POST["marks"] ?? "";

    // ----------------------------
    // VALIDATION SECTION
    // ----------------------------

    // Validate Name
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate Age
    if (empty($age)) {
        $errors[] = "Age is required.";
    } elseif ($age < 15 || $age > 100) {
        $errors[] = "Age must be between 15 and 100.";
    }

    // Validate Gender
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }

    // Validate Course
    if (empty($course)) {
        $errors[] = "Course is required.";
    }

    // Validate Marks
    if ($marks === "") {
        $errors[] = "Marks are required.";
    } elseif ($marks < 0 || $marks > 100) {
        $errors[] = "Marks must be between 0 and 100.";
    }

    // ----------------------------
    // PROCESS DATA IF NO ERRORS
    // ----------------------------
    if (empty($errors)) {

        // Grade Calculation
        if ($marks >= 90) {
            $grade = "A";
        } elseif ($marks >= 80) {
            $grade = "B";
        } elseif ($marks >= 70) {
            $grade = "C";
        } elseif ($marks >= 60) {
            $grade = "D";
        } else {
            $grade = "F";
        }

        // ----------------------------
        // ASSIGN REMARK USING SWITCH
        // ----------------------------
        switch ($grade) {
            case "A":
                $remark = "Excellent";
                break;
            case "B":
                $remark = "Very Good";
                break;
            case "C":
                $remark = "Good";
                break;
            case "D":
                $remark = "Fair";
                break;
            case "F":
                $remark = "Fail";
                break;
        }

        // ----------------------------
        // SAVE DATA TO FILE (BONUS)
        // ----------------------------
        $data = "$name, $email, $age, $gender, $course, $marks, $grade\n";
        file_put_contents("students.txt", $data, FILE_APPEND);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration Form</title>
    <style>
        body { font-family: Arial; }
        .error { color: red; }
        table { border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 10px; }
    </style>
</head>
<body>

<h2>Student Registration Form</h2>

<!-- ----------------------------
     DISPLAY VALIDATION ERRORS
----------------------------- -->
<?php
if (!empty($errors)) {
    echo "<div class='error'><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
}
?>

<!-- ----------------------------
     HTML FORM (SELF-SUBMITTING)
----------------------------- -->
<form method="POST">

    Full Name:
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"><br><br>

    Email:
    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>"><br><br>

    Age:
    <input type="number" name="age" value="<?= htmlspecialchars($age) ?>"><br><br>

    Gender:
    <input type="radio" name="gender" value="Male" <?= ($gender=="Male")?"checked":"" ?>> Male
    <input type="radio" name="gender" value="Female" <?= ($gender=="Female")?"checked":"" ?>> Female
    <br><br>

    Course:
    <select name="course">
        <option value="">--Select Course--</option>
        <option value="IT" <?= ($course=="IT")?"selected":"" ?>>IT</option>
        <option value="Business" <?= ($course=="Business")?"selected":"" ?>>Business</option>
        <option value="Engineering" <?= ($course=="Engineering")?"selected":"" ?>>Engineering</option>
    </select><br><br>

    Marks:
    <input type="number" name="marks" value="<?= htmlspecialchars($marks) ?>"><br><br>

    <input type="submit" value="Submit">
</form>

<?php
// ----------------------------
// DISPLAY RESULTS IF NO ERRORS
// ----------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors)) {
    echo "<h3>Student Details</h3>";
    echo "<table>
            <tr><th>Field</th><th>Value</th></tr>
            <tr><td>Name</td><td>" . htmlspecialchars($name) . "</td></tr>
            <tr><td>Email</td><td>" . htmlspecialchars($email) . "</td></tr>
            <tr><td>Age</td><td>" . htmlspecialchars($age) . "</td></tr>
            <tr><td>Gender</td><td>" . htmlspecialchars($gender) . "</td></tr>
            <tr><td>Course</td><td>" . htmlspecialchars($course) . "</td></tr>
            <tr><td>Marks</td><td>" . htmlspecialchars($marks) . "</td></tr>
            <tr><td>Grade</td><td>$grade</td></tr>
            <tr><td>Remark</td><td>$remark</td></tr>
          </table>";
}
?>

</body>
</html>