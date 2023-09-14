<?php
include 'connection.php';

if ($conn) {
    if (isset($_POST['partNo'])) {
        $partNo = $_POST['partNo'];

        $query = "DELETE FROM reorderconsumables WHERE PartNo = '$partNo'";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Row deleted successfully.";
        } else {
            echo "Error deleting row: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid parameters.";
    }

    mysqli_close($conn);
} else {
    echo "Error connecting to the database.";
}
?>
