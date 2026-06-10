<?php
// Include the database connection
include('../db_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Insert the member into the database
    $query = "INSERT INTO Members (FirstName, LastName, Email, Phone) VALUES ('$firstName', '$lastName', '$email', '$phone')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: ../member.php'); // Redirect back to the main page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
