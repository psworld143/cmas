<?php
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reportID = $_POST['reportID'];
    $reportDate = $_POST['reportDate'];
    $totalDonations = $_POST['totalDonations'];
    $totalExpenses = $_POST['totalExpenses'];
    $netIncome = $totalDonations - $totalExpenses;

    // Update the financial report in the database
    $query = "UPDATE FinancialReports SET ReportDate='$reportDate', TotalDonations='$totalDonations', 
              TotalExpenses='$totalExpenses', NetIncome='$netIncome' WHERE ReportID=$reportID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../financial_reports.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
