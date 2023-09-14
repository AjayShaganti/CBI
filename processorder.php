<?php
include 'connection.php';
session_start();
$partno = $_POST['barcode'];
$_SESSION['partno'] = $partno;

if ($conn) {
    $updateQuery = "UPDATE stock SET Quantity = Quantity - 1 WHERE PartNo = '$partno'";
    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        $selectQuery = "SELECT PartName,Quantity FROM stock WHERE PartNo = '$partno'";
        $result = mysqli_query($conn, $selectQuery);

        if ($result) {
            $record = mysqli_fetch_assoc($result);
            if ($record) {
                echo 'Only ' .$record['Quantity']. " " .$record['PartName']. " left" ;
            }
        }
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
} else {
    echo "Connection failed: " . mysqli_connect_error();
}

mysqli_close($conn);
header("Location: index-after.php");
?>
