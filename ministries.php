<?php
// Include the database connection
include('db_connection.php');

// Fetch all ministries from the database
$query = "SELECT * FROM Ministries";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministries - Church Management</title>
    <!-- Bootstrap 4 CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"> <!-- For Icons -->
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
    </style>
</head>
<body>
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
        <a href="member.php"><i class="fas fa-users"></i> Members</a>
        <a href="ministries.php" class="active"><i class="fas fa-church"></i> Ministries</a>
        <a href="donations.php"><i class="fas fa-donate"></i> Donations</a>
        <a href="receipts.php"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php"><i class="fas fa-calendar-alt"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Church Ministries</h2>
        
        <!-- Button to trigger Add Ministry Modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMinistryModal">Add Ministry</button>
        
        <!-- Ministries Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ministry Name</th>
                    <th>Description</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['MinistryID']; ?></td>
                    <td><?php echo $row['MinistryName']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><?php echo $row['CreatedDate']; ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editMinistryModal" data-id="<?php echo $row['MinistryID']; ?>" data-ministryname="<?php echo $row['MinistryName']; ?>" data-description="<?php echo $row['Description']; ?>">Edit</button>
                        <a href="ministries/delete_ministry.php?id=<?php echo $row['MinistryID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Ministry Modal -->
    <div class="modal fade" id="addMinistryModal" tabindex="-1" role="dialog" aria-labelledby="addMinistryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMinistryModalLabel">Add New Ministry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="ministries/add_ministry.php" method="POST">
                        <div class="form-group">
                            <label for="ministryName">Ministry Name</label>
                            <input type="text" class="form-control" id="ministryName" name="ministryName" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Ministry</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Ministry Modal -->
    <div class="modal fade" id="editMinistryModal" tabindex="-1" role="dialog" aria-labelledby="editMinistryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMinistryModalLabel">Edit Ministry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="ministries/edit_ministry.php" method="POST">
                        <input type="hidden" id="editMinistryID" name="ministryID">
                        <div class="form-group">
                            <label for="editMinistryName">Ministry Name</label>
                            <input type="text" class="form-control" id="editMinistryName" name="ministryName" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Ministry</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- JavaScript to populate the edit form modal -->
    <script>
        $('#editMinistryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var ministryID = button.data('id');
            var ministryName = button.data('ministryname');
            var description = button.data('description');

            var modal = $(this);
            modal.find('#editMinistryID').val(ministryID);
            modal.find('#editMinistryName').val(ministryName);
            modal.find('#editDescription').val(description);
        });
    </script>
</body>
</html>
