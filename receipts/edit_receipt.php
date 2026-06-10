<?php
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiptID = $_POST['receiptID'];
    $receiptDate = $_POST['receiptDate'];
    $receiptFile = $_FILES['receiptFile']['name'];

    // Upload the receipt file if new file is selected
    if ($receiptFile) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($receiptFile);
        move_uploaded_file($_FILES['receiptFile']['tmp_name'], $targetFile);
    }

    // Update the receipt record
    $query = "UPDATE Receipts SET ReceiptDate='$receiptDate', ReceiptFile='$receiptFile' WHERE ReceiptID=$receiptID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../receipts.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
