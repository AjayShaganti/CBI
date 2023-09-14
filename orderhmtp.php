<?php
include 'connection.php';
session_start();

$msg = '';
$flag = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = $_POST['newQuantity'];
    $partno = $_SESSION['partno'];

    if ($required > 0) {
        // Check if the record exists in reorderhmtp table
        $selectQuery = "SELECT * FROM reorderhmtp WHERE PartNo = ?";
        $selectStatement = mysqli_prepare($conn, $selectQuery);
        mysqli_stmt_bind_param($selectStatement, 's', $partno);
        mysqli_stmt_execute($selectStatement);
        $result = mysqli_stmt_get_result($selectStatement);

        if (mysqli_num_rows($result) > 0) {
            // Update the existing record
            $updateQuery = "UPDATE reorderhmtp
            SET Required = ?,
            BackOrder = BackOrder + (? - BackOrder)
            WHERE PartNo = ?";
            $updateStatement = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStatement, 'iis', $required, $required, $partno);
            mysqli_stmt_execute($updateStatement);

            $msg = 'PartNo ' . $partno . ' already exists. Updated existing value to ' . $required;
            $flag = 1;
			mysqli_stmt_close($updateStatement);
        } else {
    try {
        // Insert a new record
        $insertQuery = "INSERT INTO reorderhmtp (BinLocation, PartName, PartNo, Category,Available, Required, BackOrder, PurchaseOrder, Comments)
        SELECT BinLocation, PartName, ?, Category,Quantity, ?, (? - 0), 'NA', 'NA' FROM tophathymod WHERE PartNo = ?";
        $insertStatement = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($insertStatement, 'siss', $partno, $required, $required, $partno);
        mysqli_stmt_execute($insertStatement);

        $msg = 'No\'s: ' . $required . ' of PartNo ' . $partno . ' added to orders list.';
        $flag = 1;

        mysqli_stmt_close($insertStatement);
    } catch (mysqli_sql_exception $e) {
        $msg = 'Error: ' . $e->getMessage();
        $flag = 0;
    }
}

        mysqli_stmt_close($selectStatement);
        
    } else {
        $msg = 'Please enter a valid number';
        $flag = 0;
    }
}
echo $msg;
?>


