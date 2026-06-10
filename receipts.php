<?php
// Include the database connection
include('db_connection.php');

// Fetch all receipts from the database
$query = "SELECT Receipts.ReceiptID, Receipts.ReceiptDate, Receipts.ReceiptFile, Donations.DonationID, Members.FirstName, Members.LastName
          FROM Receipts
          INNER JOIN Donations ON Receipts.DonationID = Donations.DonationID
          INNER JOIN Members ON Donations.MemberID = Members.MemberID";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipts - Church Management</title>
    <!-- Bootstrap 4 CDN -->
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
        <a href="index.php"><i class="fas fa-users"></i> Members</a>
        <a href="ministries.php"><i class="fas fa-church"></i> Ministries</a>
        <a href="donations.php"><i class="fas fa-donate"></i> Donations</a>
        <a href="receipts.php" class="active"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php"><i class="fas fa-calendar-alt"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Receipts</h2>
        
        <!-- Button to trigger Add Receipt Modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addReceiptModal">Add Receipt</button>
        
        <!-- Receipts Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member Name</th>
                    <th>Donation ID</th>
                    <th>Receipt Date</th>
                    <th>Receipt File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['ReceiptID']; ?></td>
                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                    <td><?php echo $row['DonationID']; ?></td>
                    <td><?php echo $row['ReceiptDate']; ?></td>
                    <td>
                        <?php if ($row['ReceiptFile']): ?>
                            <a href="uploads/<?php echo $row['ReceiptFile']; ?>" target="_blank">View Receipt</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editReceiptModal" 
                                data-id="<?php echo $row['ReceiptID']; ?>" 
                                data-receipt-date="<?php echo $row['ReceiptDate']; ?>" 
                                data-file="<?php echo $row['ReceiptFile']; ?>">Edit</button>
                        <a href="receipts/#delete_receipt.php?id=<?php echo $row['ReceiptID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Receipt Modal -->
    <div class="modal fade" id="addReceiptModal" tabindex="-1" role="dialog" aria-labelledby="addReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReceiptModalLabel">Add New Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="receipts/add_receipt.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="donationID">Donation</label>
                            <select class="form-control" id="donationID" name="donationID" required>
                                <?php
                                // Fetch all donations for the dropdown
                                $donationsQuery = "SELECT DonationID FROM Donations";
                                $donationsResult = mysqli_query($conn, $donationsQuery);
                                while ($donation = mysqli_fetch_assoc($donationsResult)) {
                                    echo "<option value='" . $donation['DonationID'] . "'>" . $donation['DonationName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="receiptDate">Receipt Date</label>
                            <input type="date" class="form-control" id="receiptDate" name="receiptDate" required>
                        </div>
                        <div class="form-group">
                            <label for="receiptFile">Receipt File</label>
                            <input type="file" class="form-control" id="receiptFile" name="receiptFile" accept="application/pdf,image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Receipt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Receipt Modal -->
    <div class="modal fade" id="editReceiptModal" tabindex="-1" role="dialog" aria-labelledby="editReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReceiptModalLabel">Edit Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="receipts/edit_receipt.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="editReceiptID" name="receiptID">
                        <div class="form-group">
                            <label for="editReceiptDate">Receipt Date</label>
                            <input type="date" class="form-control" id="editReceiptDate" name="receiptDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editReceiptFile">Receipt File</label>
                            <input type="file" class="form-control" id="editReceiptFile" name="receiptFile" accept="application/pdf,image/*">
                            <small>If you want to update the file, select a new one.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Receipt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        // Populate edit modal with data
        $('#editReceiptModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var receiptID = button.data('id');
            var receiptDate = button.data('receipt-date');
            var file = button.data('file');
            
            var modal = $(this);
            modal.find('#editReceiptID').val(receiptID);
            modal.find('#editReceiptDate').val(receiptDate);
        });
    </script>
</body>
</html>
