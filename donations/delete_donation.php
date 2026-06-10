<?php
// Include the database connection
include('../db_connection.php');

// Check if the ID is set in the query string
if (isset($_GET['id'])) {
    $donationID = $_GET['id'];

    // Delete the donation from the database
    $query = "DELETE FROM Donations WHERE DonationID = $donationID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../donations.php'); // Redirect back to the donations page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
