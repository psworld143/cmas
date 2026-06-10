<?php
include('../db_connection.php');

if (isset($_GET['id'])) {
    $receiptID = $_GET['id'];

    // Delete the receipt from the database
    $query = "DELETE FROM Receipts WHERE ReceiptID = $receiptID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../receipts.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
