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

    // Fetch the current data to edit
    $stmt = $conn->prepare("SELECT * FROM trip WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated values from form and update the record
        $name = $_POST['name'];
        $age = (int) $_POST['age'];
        $gender = strtolower($_POST['gender']);
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $desc = $_POST['desc'];

        // Prepared statement for updating the record
        $update_stmt = $conn->prepare("UPDATE trip SET name = ?, age = ?, gender = ?, email = ?, phone = ?, other = ? WHERE id = ?");
        $update_stmt->bind_param("sissssi", $name, $age, $gender, $email, $mobile, $desc, $id);

        if ($update_stmt->execute()) {
            header("Location: admin_panel.php"); // Redirect to the admin panel after update
            exit();
        } else {
            echo "Error: " . $update_stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No ID provided!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
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
        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        input[type="text"], input[type="email"], input[type="number"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group select {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>Edit Record</h2>
    <form method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($record['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo $record['age']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="male" <?php if ($record['gender'] === 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if ($record['gender'] === 'female') echo 'selected'; ?>>Female</option>
                <option value="other" <?php if ($record['gender'] === 'other') echo 'selected'; ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($record['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="mobile">Phone:</label>
            <input type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars($record['phone']); ?>" required>
        </div>

        <div class="form-group">
            <label for="desc">Description:</label>
            <textarea name="desc" id="desc" required><?php echo htmlspecialchars($record['other']); ?></textarea>
        </div>

        <input type="submit" value="Update">
    </form>
</body>
</html>
