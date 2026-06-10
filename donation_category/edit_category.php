<?php
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryID = $_POST['categoryID'];
    $categoryName = $_POST['categoryName'];
    $categoryDescription = $_POST['categoryDescription'];

    // Update the donation category in the database
    $query = "UPDATE DonationCategories SET CategoryName='$categoryName', Description='$categoryDescription' 
              WHERE CategoryID=$categoryID";

    if (mysqli_query($conn, $query)) {
        header('Location: ../donation_categories.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
