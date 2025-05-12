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

// Initialize search variables
$search = "";
$searchField = "name";

// Search functionality
if (isset($_GET['search']) && isset($_GET['field'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $searchField = mysqli_real_escape_string($conn, $_GET['field']);
    
    // Validate search field to prevent SQL injection
    $validFields = ['name', 'email', 'phone', 'picnic_date'];
    if (!in_array($searchField, $validFields)) {
        $searchField = 'name'; // Default to name if invalid field
    }
    
    // Build query with search condition
    $sql = "SELECT * FROM trip WHERE $searchField LIKE '%$search%' ORDER BY id DESC";
} else {
    // Default query
    $sql = "SELECT * FROM trip ORDER BY id DESC";
}

// Pagination settings
$results_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Add pagination to SQL query
$sql_with_pagination = $sql . " LIMIT $start_from, $results_per_page";
$result = mysqli_query($conn, $sql_with_pagination);

// Get total number of results for pagination
$total_results = mysqli_num_rows(mysqli_query($conn, $sql));
$total_pages = ceil($total_results / $results_per_page);

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
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-form input, .search-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .export-btn {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
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
            text-decoration: none;
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
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel - View All Records</h2>
        
        <div class="admin-header">
            <form class="search-form" method="GET">
                <select name="field">
                    <option value="name" <?php if($searchField == 'name') echo 'selected'; ?>>Name</option>
                    <option value="email" <?php if($searchField == 'email') echo 'selected'; ?>>Email</option>
                    <option value="phone" <?php if($searchField == 'phone') echo 'selected'; ?>>Phone</option>
                    <option value="picnic_date" <?php if($searchField == 'picnic_date') echo 'selected'; ?>>Picnic Date</option>
                </select>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search...">
                <button type="submit">Search</button>
                <?php if(!empty($search)): ?>
                    <a href="admin_panel.php" style="margin-left: 10px;">Clear</a>
                <?php endif; ?>
            </form>
            
            <a href="export.php" class="export-btn">Export to CSV</a>
        </div>

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
                        <th>Picnic Date</th>
                        <th>Registration Date</th>
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
                        <td>" . (isset($row['picnic_date']) ? $row['picnic_date'] : 'N/A') . "</td>
                        <td>" . (isset($row['dt']) ? $row['dt'] : 'N/A') . "</td>
                        <td class='actions'>
                            <a href='edit.php?id=" . (isset($row['id']) ? $row['id'] : '') . "'>Edit</a> | 
                            <a href='delete.php?id=" . (isset($row['id']) ? $row['id'] : '') . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                        </td>
                    </tr>";
            }

            echo "</table>";
            
            // Display pagination
            if ($total_pages > 1) {
                echo "<div class='pagination'>";
                
                // Previous page link
                if ($page > 1) {
                    echo "<a href='?page=".($page-1).((!empty($search))?"&search=$search&field=$searchField":"")."'>&laquo; Previous</a>";
                }
                
                // Page numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='?page=$i".((!empty($search))?"&search=$search&field=$searchField":"")."' ".($i==$page?"class='active'":"").">$i</a>";
                }
                
                // Next page link
                if ($page < $total_pages) {
                    echo "<a href='?page=".($page+1).((!empty($search))?"&search=$search&field=$searchField":"")."'>Next &raquo;</a>";
                }
                
                echo "</div>";
            }
            
        } else {
            echo "<p class='no-data'>No records found.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</body>
</html>