<?php
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donationID = $_POST['donationID'];
    $receiptDate = $_POST['receiptDate'];
    $receiptFile = $_FILES['receiptFile']['name'];

    // Upload the receipt file
    if ($receiptFile) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($receiptFile);
        move_uploaded_file($_FILES['receiptFile']['tmp_name'], $targetFile);
    }

    // Insert the receipt into the database
    $query = "INSERT INTO Receipts (DonationID, ReceiptDate, ReceiptFile) 
              VALUES ('$donationID', '$receiptDate', '$receiptFile')";

    if (mysqli_query($conn, $query)) {
        header('Location: ../receipts.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
