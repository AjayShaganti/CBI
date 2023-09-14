<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$partNo = $_POST['partNo'];
$requiredQty = $_POST['requiredQty'];

try {
    if ($conn) {
        // Prepare the SQL statement with placeholders
        $updateQuery = "UPDATE reorderlabels SET Required = ?, BackOrder = ?, lastupdated = ? WHERE PartNo = ?";
        $statement = mysqli_prepare($conn, $updateQuery);

        if ($statement) {
            // Get the current date and time in the desired format
            $currentDateTime = date('Y-m-d H:i:s');

            // Bind the parameters to the statement
            mysqli_stmt_bind_param($statement, 'ssss', $requiredQty, $requiredQty, $currentDateTime, $partNo);

            // Execute the statement
            $result = mysqli_stmt_execute($statement);

            if ($result) {
                $response = "Required Quantity has been updated to $requiredQty for Part No: $partNo";
            } else {
                throw new Exception("<h3>Failed to update</h3>");
            }

            // Close the statement
            mysqli_stmt_close($statement);
        } else {
            throw new Exception("<h3>Failed to prepare statement</h3>");
        }
    } else {
        throw new Exception("<h3>Connection failed: " . mysqli_connect_error(). "</h3>");
    }
} catch (Exception $e) {
    $response = "Something went wrong! Please try again. If the issue persists, please contact the administrator.";
}

echo $response;
?>
