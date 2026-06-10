<?php
// Include the database connection
include('../db_connection.php');

// Get the member ID to delete
if (isset($_GET['id'])) {
    $memberID = $_GET['id'];

    // Delete the member from the database
    $query = "DELETE FROM Members WHERE MemberID = $memberID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../member.php'); // Redirect back to the main page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
