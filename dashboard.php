<?php
// Include the database connection
include('db_connection.php');

// Get Total Donations
$donationQuery = "SELECT SUM(Amount) AS TotalDonations FROM Donations";
$donationResult = mysqli_query($conn, $donationQuery);
$donationData = mysqli_fetch_assoc($donationResult);

// Get Total Expenses
$expenseQuery = "SELECT SUM(TotalExpenses) AS TotalExpenses FROM FinancialReports WHERE ReportDate <= CURDATE()";
$expenseResult = mysqli_query($conn, $expenseQuery);
$expenseData = mysqli_fetch_assoc($expenseResult);

// Get Net Income
$netIncomeQuery = "SELECT (SUM(Amount) - (SELECT SUM(TotalExpenses) FROM FinancialReports WHERE ReportDate <= CURDATE())) AS NetIncome FROM Donations";
$netIncomeResult = mysqli_query($conn, $netIncomeQuery);
$netIncomeData = mysqli_fetch_assoc($netIncomeResult);

// Get Total Members
$memberQuery = "SELECT COUNT(*) AS TotalMembers FROM Members";
$memberResult = mysqli_query($conn, $memberQuery);
$memberData = mysqli_fetch_assoc($memberResult);

// Get Total Events
$eventQuery = "SELECT COUNT(*) AS TotalEvents FROM Events WHERE EventDate >= CURDATE()";
$eventResult = mysqli_query($conn, $eventQuery);
$eventData = mysqli_fetch_assoc($eventResult);

// Get Total Ministries
$ministryQuery = "SELECT COUNT(*) AS TotalMinistries FROM Ministries";
$ministryResult = mysqli_query($conn, $ministryQuery);
$ministryData = mysqli_fetch_assoc($ministryResult);

// Get Recurring Donations
$recurringDonationQuery = "SELECT COUNT(*) AS RecurringDonations FROM Donations WHERE IsRecurring = TRUE";
$recurringDonationResult = mysqli_query($conn, $recurringDonationQuery);
$recurringDonationData = mysqli_fetch_assoc($recurringDonationResult);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Church Management</title>
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
          <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="member.php"><i class="fas fa-users"></i> Members</a>
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
        <h2>Dashboard</h2>

        <div class="row">
            <!-- Total Donations -->
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Donations</h5>
                        <p class="card-text">₱<?php echo number_format($donationData['TotalDonations'], 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenses</h5>
                        <p class="card-text">₱<?php echo number_format($expenseData['TotalExpenses'], 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- Net Income -->
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Net Income</h5>
                        <p class="card-text">₱<?php echo number_format($netIncomeData['NetIncome'], 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Members -->
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Members</h5>
                        <p class="card-text"><?php echo $memberData['TotalMembers']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Events -->
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Events</h5>
                        <p class="card-text"><?php echo $eventData['TotalEvents']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Ministries -->
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Ministries</h5>
                        <p class="card-text"><?php echo $ministryData['TotalMinistries']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Recurring Donations -->
            <div class="col-md-3">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h5 class="card-title">Recurring Donations</h5>
                        <p class="card-text"><?php echo $recurringDonationData['RecurringDonations']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
