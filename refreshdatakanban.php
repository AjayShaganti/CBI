<?php
include 'connection.php';

try {
    if ($conn) {
        // Prepare the SQL statement with placeholders
        $deleteQuery = "DELETE FROM reorder WHERE status = ?";
        $statement = mysqli_prepare($conn, $deleteQuery);

        if ($statement) {
            // Define the status value for deletion
            $statusToDelete = "To be Ordered";

            // Bind the parameter to the statement
            mysqli_stmt_bind_param($statement, 's', $statusToDelete);

            // Execute the statement
            $result = mysqli_stmt_execute($statement);

            if ($result) {
                $response = "Rows with status 'To be Ordered' have been refreshed.";
            } else {
                throw new Exception("<h3>Failed to delete</h3>");
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
