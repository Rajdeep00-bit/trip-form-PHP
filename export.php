<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "trip";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="registrations_'.date('Y-m-d').'.csv"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('ID', 'Name', 'Age', 'Gender', 'Email', 'Phone', 'Description', 'Picnic Date', 'Registration Date'));

// Fetch records from database
$sql = "SELECT * FROM trip";
$result = mysqli_query($conn, $sql);

// Loop over the rows, outputting them
while ($row = mysqli_fetch_assoc($result)) {
    $csvRow = array(
        $row['id'],
        $row['name'],
        $row['age'],
        ucfirst($row['gender']),
        $row['email'],
        $row['phone'],
        $row['other'],
        isset($row['picnic_date']) ? $row['picnic_date'] : 'N/A',
        $row['dt']
    );
    fputcsv($output, $csvRow);
}

// Close database connection
mysqli_close($conn);