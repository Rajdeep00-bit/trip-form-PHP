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

// Fetch data from the trip table
$sql = "SELECT * FROM trip";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
        }
        td {
            background-color: #f9f9f9;
        }
        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }
        tr:hover td {
            background-color: #e2f7d1;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            color: #0056b3;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a {
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .actions a:hover {
            background-color: #218838;
        }
        .actions .delete {
            background-color: #dc3545;
        }
        .actions .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h2>Admin Panel - View All Records</h2>

    <?php
    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>";
        
        // Output data for each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . (isset($row['id']) ? $row['id'] : 'N/A') . "</td>
                    <td>" . (isset($row['name']) ? $row['name'] : 'N/A') . "</td>
                    <td>" . (isset($row['age']) ? $row['age'] : 'N/A') . "</td>
                    <td>" . (isset($row['gender']) ? ucfirst($row['gender']) : 'N/A') . "</td>
                    <td>" . (isset($row['email']) ? $row['email'] : 'N/A') . "</td>
                    <td>" . (isset($row['phone']) ? $row['phone'] : 'N/A') . "</td>
                    <td>" . (isset($row['other']) ? $row['other'] : 'N/A') . "</td>
                    <td>" . (isset($row['dt']) ? $row['dt'] : 'N/A') . "</td>
                    <td class='actions'>
                        <a href='edit.php?id=" . (isset($row['id']) ? $row['id'] : '') . "'>Edit</a> | 
                        <a href='delete.php?id=" . (isset($row['id']) ? $row['id'] : '') . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No records found.</p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>
