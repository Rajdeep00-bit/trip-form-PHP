<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Connect to DB
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "trip"; 

    $conn = mysqli_connect($server, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepared statement for deleting the record
    $stmt = $conn->prepare("DELETE FROM trip WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_panel.php"); // Redirect to the admin panel after deleting
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No ID provided!";
}
?>
