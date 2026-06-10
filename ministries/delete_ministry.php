<?php
// Include the database connection
include('../db_connection.php');

// Check if the ID is set in the query string
if (isset($_GET['id'])) {
    $ministryID = $_GET['id'];

    // Delete the ministry from the database
    $query = "DELETE FROM Ministries WHERE MinistryID = $ministryID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../ministries.php'); // Redirect back to the ministries page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
