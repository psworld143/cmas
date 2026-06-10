<?php
// Include the database connection
include('db_connection.php');

// Fetch all donations from the database
$query = "SELECT Donations.DonationID, Donations.Amount, Donations.IsRecurring, Donations.Frequency, 
                 Members.FirstName, Members.LastName, Ministries.MinistryName
          FROM Donations
          INNER JOIN Members ON Donations.MemberID = Members.MemberID
          INNER JOIN Ministries ON Donations.MinistryID = Ministries.MinistryID";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations - Church Management</title>
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
        <a href="member.php"><i class="fas fa-users"></i> Members</a>
        <a href="ministries.php"><i class="fas fa-church"></i> Ministries</a>
        <a href="donations.php" class="active"><i class="fas fa-donate"></i> Donations</a>
        <a href="receipts.php"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php"><i class="fas fa-calendar-alt"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Donations</h2>
        
        <!-- Button to trigger Add Donation Modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addDonationModal">Add Donation</button>
        
        <!-- Donations Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member Name</th>
                    <th>Ministry</th>
                    <th>Amount</th>
                    <th>Recurring</th>
                    <th>Frequency</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['DonationID']; ?></td>
                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                    <td><?php echo $row['MinistryName']; ?></td>
                    <td><?php echo number_format($row['Amount'], 2); ?></td>
                    <td><?php echo $row['IsRecurring'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $row['Frequency'] ? $row['Frequency'] : 'N/A'; ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editDonationModal" 
                                data-id="<?php echo $row['DonationID']; ?>" 
                                data-amount="<?php echo $row['Amount']; ?>" 
                                data-recurring="<?php echo $row['IsRecurring']; ?>" 
                                data-frequency="<?php echo $row['Frequency']; ?>" 
                                data-member="<?php echo $row['FirstName'] . ' ' . $row['LastName']; ?>" 
                                data-ministry="<?php echo $row['MinistryName']; ?>">Edit</button>
                        <a href="donations/delete_donation.php?id=<?php echo $row['DonationID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Donation Modal -->
    <div class="modal fade" id="addDonationModal" tabindex="-1" role="dialog" aria-labelledby="addDonationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDonationModalLabel">Add New Donation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="donations/add_donation.php" method="POST">
                        <div class="form-group">
                            <label for="member">Member</label>
                            <select class="form-control" id="member" name="memberID" required>
                                <?php
                                // Fetch all members for the dropdown
                                $membersQuery = "SELECT MemberID, FirstName, LastName FROM Members";
                                $membersResult = mysqli_query($conn, $membersQuery);
                                while ($member = mysqli_fetch_assoc($membersResult)) {
                                    echo "<option value='" . $member['MemberID'] . "'>" . $member['FirstName'] . " " . $member['LastName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ministry">Ministry</label>
                            <select class="form-control" id="ministry" name="ministryID" required>
                                <?php
                                // Fetch all ministries for the dropdown
                                $ministriesQuery = "SELECT MinistryID, MinistryName FROM Ministries";
                                $ministriesResult = mysqli_query($conn, $ministriesQuery);
                                while ($ministry = mysqli_fetch_assoc($ministriesResult)) {
                                    echo "<option value='" . $ministry['MinistryID'] . "'>" . $ministry['MinistryName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="recurring">Recurring</label>
                            <select class="form-control" id="recurring" name="isRecurring">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group" id="frequencyGroup" style="display: none;">
                            <label for="frequency">Frequency</label>
                            <select class="form-control" id="frequency" name="frequency">
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Annually">Annually</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Donation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Donation Modal -->
    <div class="modal fade" id="editDonationModal" tabindex="-1" role="dialog" aria-labelledby="editDonationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDonationModalLabel">Edit Donation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="doantions/edit_donation.php" method="POST">
                        <input type="hidden" id="editDonationID" name="donationID">
                        <div class="form-group">
                            <label for="editAmount">Amount</label>
                            <input type="number" class="form-control" id="editAmount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="editRecurring">Recurring</label>
                            <select class="form-control" id="editRecurring" name="isRecurring" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group" id="editFrequencyGroup" style="display: none;">
                            <label for="editFrequency">Frequency</label>
                            <select class="form-control" id="editFrequency" name="frequency">
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Annually">Annually</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Donation</button>
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
        // Toggle frequency field based on recurring selection
        $('#recurring').change(function() {
            if ($(this).val() == '1') {
                $('#frequencyGroup').show();
            } else {
                $('#frequencyGroup').hide();
            }
        });

        // Populate edit modal with data
        $('#editDonationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var donationID = button.data('id');
            var amount = button.data('amount');
            var isRecurring = button.data('recurring');
            var frequency = button.data('frequency');
            
            var modal = $(this);
            modal.find('#editDonationID').val(donationID);
            modal.find('#editAmount').val(amount);
            modal.find('#editRecurring').val(isRecurring);
            if (isRecurring == '1') {
                modal.find('#editFrequencyGroup').show();
                modal.find('#editFrequency').val(frequency);
            } else {
                modal.find('#editFrequencyGroup').hide();
            }
        });
    </script>
</body>
</html>
