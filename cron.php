<?php include_once ("./includes/connection.php");
$sql = "SELECT * FROM `active_plans`";
$query = mysqli_query($conn, $sql);
while($rows = mysqli_fetch_assoc($query)) {
    $activeID = $rows['id'];
    $userID = $rows['userID'];
    $packageID = $rows['packageID'];
    $daysLimit = $rows['daysLimit'];
    $limitUse = $rows['limitUse'];
    
    $sql1 = "SELECT * FROM `config_plans` WHERE `id`='$packageID'";
    $query1 = mysqli_query($conn, $sql1);
    $row = mysqli_fetch_assoc($query1);
    
    $renewQty = $row['qty'];
    $renewDays = $daysLimit - 1;

    if($daysLimit == "0") {
    $sqlS = "UPDATE `active_plans` SET `expired`='1' WHERE `id`='$activeID' AND `expired`='0'";
    $queryS = mysqli_query($conn, $sqlS);
} else {
    $sqlS = "UPDATE `active_plans` SET `limitUse`='$renewQty',`daysLimit`='$renewDays',`expired`='0' WHERE `id`='$activeID' AND `expired`='0'";
    $queryS = mysqli_query($conn, $sqlS);
}
}