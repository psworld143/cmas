<?php
// Include the database connection
include('db_connection.php');

// Fetch all members from the database
$query = "SELECT * FROM Members";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management - Members</title>
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
        <a href="member.php" class="active"><i class="fas fa-users"></i> Members</a>
        <a href="ministries.php"><i class="fas fa-church"></i> Ministries</a>
        <a href="donations.php"><i class="fas fa-donate"></i> Donations</a>
        <a href="receipts.php"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php"><i class="fas fa-calendar-alt"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Church Members</h2>
        <!-- Button to trigger Add Member Modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMemberModal">Add Member</button>
        
        <!-- Members Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['MemberID']; ?></td>
                    <td><?php echo $row['FirstName']; ?></td>
                    <td><?php echo $row['LastName']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Phone']; ?></td>
                    <td><?php echo $row['JoinDate']; ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editMemberModal" data-id="<?php echo $row['MemberID']; ?>" data-firstname="<?php echo $row['FirstName']; ?>" data-lastname="<?php echo $row['LastName']; ?>" data-email="<?php echo $row['Email']; ?>" data-phone="<?php echo $row['Phone']; ?>">Edit</button>
                        <a href="meber/delete_member.php?id=<?php echo $row['MemberID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">Add New Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="member/add_member.php" method="POST">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" role="dialog" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMemberModalLabel">Edit Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="member/edit_member.php" method="POST">
                        <input type="hidden" id="editMemberID" name="memberID">
                        <div class="form-group">
                            <label for="editFirstName">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="editLastName">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="lastName" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="editPhone">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Member</button>
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
        $('#editMemberModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var memberID = button.data('id');
            var firstName = button.data('firstname');
            var lastName = button.data('lastname');
            var email = button.data('email');
            var phone = button.data('phone');

            var modal = $(this);
            modal.find('#editMemberID').val(memberID);
            modal.find('#editFirstName').val(firstName);
            modal.find('#editLastName').val(lastName);
            modal.find('#editEmail').val(email);
            modal.find('#editPhone').val(phone);
        });
    </script>
</body>
</html>
