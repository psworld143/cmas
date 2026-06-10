<?php
// Include the database connection
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donationID = $_POST['donationID'];
    $amount = $_POST['amount'];
    $isRecurring = $_POST['isRecurring'];
    $frequency = $_POST['frequency'];

    // Update the donation in the database
    $query = "UPDATE Donations SET Amount='$amount', IsRecurring='$isRecurring', Frequency='$frequency' 
              WHERE DonationID=$donationID";
    
    if (mysqli_query($conn, $query)) {
        header('Location: ../donations.php'); // Redirect back to the donations page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
