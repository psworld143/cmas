<?php
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryName = $_POST['categoryName'];
    $categoryDescription = $_POST['categoryDescription'];

    // Insert the donation category into the database
    $query = "INSERT INTO DonationCategories (CategoryName, Description) 
              VALUES ('$categoryName', '$categoryDescription')";

    if (mysqli_query($conn, $query)) {
        header('Location: ../donation_categories.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
