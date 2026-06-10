<?php
// Include the database connection
include('../db_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ministryID = $_POST['ministryID'];
    $ministryName = mysqli_real_escape_string($conn, $_POST['ministryName']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Update the ministry in the database
    $query = "UPDATE Ministries SET MinistryName='$ministryName', Description='$description' WHERE MinistryID=$ministryID";
    
    if (mysqli_query($conn, $query)) {
        header('Location: ../ministries.php'); // Redirect back to the ministries page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
