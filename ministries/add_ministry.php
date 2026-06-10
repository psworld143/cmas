<?php
// Include the database connection
include('../db_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ministryName = mysqli_real_escape_string($conn, $_POST['ministryName']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Insert the ministry into the database
    $query = "INSERT INTO Ministries (MinistryName, Description) VALUES ('$ministryName', '$description')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: ../ministries.php'); // Redirect back to the ministries page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
