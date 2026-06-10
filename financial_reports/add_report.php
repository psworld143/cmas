<?php
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reportDate = $_POST['reportDate'];
    $totalDonations = $_POST['totalDonations'];
    $totalExpenses = $_POST['totalExpenses'];
    $netIncome = $totalDonations - $totalExpenses;
    $createdBy = 1; // You can set this dynamically based on the logged-in user

    // Insert the financial report into the database
    $query = "INSERT INTO FinancialReports (ReportDate, TotalDonations, TotalExpenses, NetIncome, CreatedBy) 
              VALUES ('$reportDate', '$totalDonations', '$totalExpenses', '$netIncome', '$createdBy')";

    if (mysqli_query($conn, $query)) {
        header('Location: ../financial_reports.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
