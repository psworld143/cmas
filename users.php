<?php
// Include the database connection
include('db_connection.php');

// Handle user registration (Create a new user)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role']; // User role (admin, user)

    // Simple validation
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($role)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Prevent SQL injection
        $username = mysqli_real_escape_string($conn, $username);

        // Check if the username already exists
        $checkQuery = "SELECT * FROM Users WHERE Username = '$username'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username already taken. Please choose another one.";
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the Users table
            $insertQuery = "INSERT INTO Users (Username, Password, Role) VALUES ('$username', '$hashedPassword', '$role')";

            if (mysqli_query($conn, $insertQuery)) {
                header('Location: users.php'); // Reload the page after successful registration
                exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch all users from the database
$query = "SELECT UserID, Username, Role, CreatedAt FROM Users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Church System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar style */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Church Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white">Church Management</h3>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="members.php"><i class="fas fa-users"></i> Members</a>
        <a href="ministries.php"><i class="fas fa-church"></i> Ministries</a>
        <a href="donations.php"><i class="fas fa-donate"></i> Donations</a>
        <a href="receipts.php"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php" class="active"><i class="fas fa-users"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>User Management</h2>

        <!-- Display any error or success message -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add User Form -->
        <div class="card">
            <div class="card-header">
                <h4>Add New User</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="users.php">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Register User</button>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="card">
            <div class="card-header">
                <h4>Users List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['UserID']; ?></td>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['Role']; ?></td>
                            <td><?php echo $row['CreatedAt']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['UserID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row['UserID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
