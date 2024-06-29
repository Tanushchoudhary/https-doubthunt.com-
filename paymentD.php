<?php
include_once ('./includes/connection.php');
$productId = $_POST['product_id'];
$new_folder = $_POST['packageFolder'];
$sql = "INSERT INTO `downloads`(`id`, `userID`, `downloadID`) VALUES (null,'$global_id','$productId')";
$query = mysqli_query($conn, $sql);
if ($query) {
    rename($global_folder_name, $new_folder);
    $sql = "UPDATE `download_folder` SET `folder`='$new_folder' WHERE `folder`='$global_folder_name'";
    $query = mysqli_query($conn, $sql);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
