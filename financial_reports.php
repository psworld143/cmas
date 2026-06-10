<?php
// Include the database connection
include('db_connection.php');

// Fetch all financial reports from the database
$query = "SELECT FinancialReports.ReportID, FinancialReports.ReportDate, FinancialReports.TotalDonations, 
          FinancialReports.TotalExpenses, FinancialReports.NetIncome, Members.FirstName, Members.LastName 
          FROM FinancialReports 
          INNER JOIN Members ON FinancialReports.CreatedBy = Members.MemberID";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports - Church Management</title>
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
        <a href="receipts.php"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php" class="active"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php"><i class="fas fa-calendar-alt"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Financial Reports</h2>
        
        <!-- Button to trigger Add Report Modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addReportModal">Add Financial Report</button>
        
        <!-- Financial Reports Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Report Date</th>
                    <th>Total Donations</th>
                    <th>Total Expenses</th>
                    <th>Net Income</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['ReportID']; ?></td>
                    <td><?php echo $row['ReportDate']; ?></td>
                    <td><?php echo number_format($row['TotalDonations'], 2); ?></td>
                    <td><?php echo number_format($row['TotalExpenses'], 2); ?></td>
                    <td><?php echo number_format($row['NetIncome'], 2); ?></td>
                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editReportModal" 
                                data-id="<?php echo $row['ReportID']; ?>" 
                                data-report-date="<?php echo $row['ReportDate']; ?>"
                                data-total-donations="<?php echo $row['TotalDonations']; ?>"
                                data-total-expenses="<?php echo $row['TotalExpenses']; ?>"
                                data-net-income="<?php echo $row['NetIncome']; ?>"
                                data-created-by="<?php echo $row['CreatedBy']; ?>">Edit</button>
                        <a href="financial_reports/delete_report.php?id=<?php echo $row['ReportID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Financial Report Modal -->
    <div class="modal fade" id="addReportModal" tabindex="-1" role="dialog" aria-labelledby="addReportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReportModalLabel">Add Financial Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="financial_reports/add_report.php" method="POST">
                        <div class="form-group">
                            <label for="reportDate">Report Date</label>
                            <input type="date" class="form-control" id="reportDate" name="reportDate" required>
                        </div>
                        <div class="form-group">
                            <label for="totalDonations">Total Donations</label>
                            <input type="number" class="form-control" id="totalDonations" name="totalDonations" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="totalExpenses">Total Expenses</label>
                            <input type="number" class="form-control" id="totalExpenses" name="totalExpenses" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Financial Report Modal -->
    <div class="modal fade" id="editReportModal" tabindex="-1" role="dialog" aria-labelledby="editReportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReportModalLabel">Edit Financial Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="financial_reports/edit_report.php" method="POST">
                        <input type="hidden" id="editReportID" name="reportID">
                        <div class="form-group">
                            <label for="editReportDate">Report Date</label>
                            <input type="date" class="form-control" id="editReportDate" name="reportDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editTotalDonations">Total Donations</label>
                            <input type="number" class="form-control" id="editTotalDonations" name="totalDonations" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="editTotalExpenses">Total Expenses</label>
                            <input type="number" class="form-control" id="editTotalExpenses" name="totalExpenses" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Report</button>
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
        $('#editReportModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reportID = button.data('id');
            var reportDate = button.data('report-date');
            var totalDonations = button.data('total-donations');
            var totalExpenses = button.data('total-expenses');
            var netIncome = button.data('net-income');

            var modal = $(this);
            modal.find('#editReportID').val(reportID);
            modal.find('#editReportDate').val(reportDate);
            modal.find('#editTotalDonations').val(totalDonations);
            modal.find('#editTotalExpenses').val(totalExpenses);
        });
    </script>
</body>
</html>
