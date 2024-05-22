<?php
include "db_conn.php";
$logID = $_GET['logID'];
$sql = "DELETE FROM `log` WHERE logID = $logID";
$result = mysqli_query($conn, $sql);

if($result){
    header("Location: ../admin.php?msg=logID: ". $logID ." is Deleted Successfully");
}
else{
    echo "Failed: " . mysqli_error($conn);
}
?>