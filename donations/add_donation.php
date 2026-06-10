<?php
// Include the database connection
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memberID = $_POST['memberID'];
    $ministryID = $_POST['ministryID'];
    $amount = $_POST['amount'];
    $isRecurring = $_POST['isRecurring'];
    $frequency = $_POST['frequency'];

    // Insert the donation into the database
    $query = "INSERT INTO Donations (MemberID, MinistryID, Amount, IsRecurring, Frequency) 
              VALUES ('$memberID', '$ministryID', '$amount', '$isRecurring', '$frequency')";

    if (mysqli_query($conn, $query)) {
        header('Location: ../donations.php'); // Redirect back to the donations page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
