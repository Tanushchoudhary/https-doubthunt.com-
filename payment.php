<?php
// Include database connection file
include_once('./includes/connection.php');

// Retrieve POST data
$package_qty = $_POST['package_qty'];
$package_days = $_POST['package_days'];
$product_id = $_POST['product_id'];

// Example of user ID (global_id) retrieval
$global_id = 123; // Replace with your actual user ID retrieval mechanism

// Update expired plans
$sql = "UPDATE `active_plans` SET `expired`='1' WHERE `userID`='$global_id'";
$query = mysqli_query($conn, $sql);

// Insert new active plan
$sql = "INSERT INTO `active_plans`(`id`, `userID`, `packageID`, `limitUse`, `daysLimit`, `expired`) VALUES (null,'$global_id','$product_id','$package_qty','$package_days','0')";
$query = mysqli_query($conn, $sql);

// Check if queries were successful
if ($query) {
    echo "Plans updated and new plan added successfully.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
