<?php
include('db_connection.php');

if (isset($_GET['id'])) {
    $reportID = $_GET['id'];

    // Delete the financial report from the database
    $query = "DELETE FROM FinancialReports WHERE ReportID = $reportID";

    if (mysqli_query($conn, $query)) {
        header('Location: financial_reports.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
