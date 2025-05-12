<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "trip"; 

    // Connect to DB
    $conn = mysqli_connect($server, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize and validate input
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $name = clean_input($_POST['name']);
    $age = (int) $_POST['age'];
    $gender = strtolower(clean_input($_POST['gender']));
    $email = clean_input($_POST['email']);
    $mobile = clean_input($_POST['mobile']);
    $desc = clean_input($_POST['desc']);

    // Validation
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if ($age < 1 || $age > 120) {
        $errors[] = "Age must be between 1 and 120.";
    }

    if (!in_array($gender, ['male', 'female', 'other'])) {
        $errors[] = "Gender must be Male, Female, or Other.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^\d{10}$/', $mobile)) {
        $errors[] = "Mobile number must be 10 digits.";
    }

    if (strlen($desc) < 5) {
        $errors[] = "Description should be at least 5 characters.";
    }

    // If any error, show and stop
    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo "<p style='color: red;'>$err</p>";
        }
        exit();
    }

    // Use prepared statements
    $stmt = $conn->prepare("INSERT INTO trip (name, age, gender, email, phone, other, dt) VALUES (?, ?, ?, ?, ?, ?, current_timestamp())");
    $stmt->bind_param("sissss", $name, $age, $gender, $email, $mobile, $desc);

    if ($stmt->execute()) {
        header("Location: index.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>



