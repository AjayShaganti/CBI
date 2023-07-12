<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$partNo = $_POST['partNo'];
$comments = $_POST['comments'];


$response = '';

try {
    if ($conn) {
        // Prepare the SQL statement with placeholders
        $updateQuery = "UPDATE reorderhmtp SET Comments = ?, lastupdated = ? WHERE PartNo = ?";
        $statement = mysqli_prepare($conn, $updateQuery);

        if ($statement) {
            // Bind the parameters to the statement
            mysqli_stmt_bind_param($statement, 'sss', $comments, date('Y-m-d H:i:s'), $partNo);

            // Execute the statement
            $result = mysqli_stmt_execute($statement);

            if ($result) {
                $response = "Comments has been set to $comments for Part No: $partNo";
            } else {
                throw new Exception("<h3>Failed to update</h3>");
            }

            // Close the statement
            mysqli_stmt_close($statement);
        } else {
            throw new Exception("<h3>Failed to prepare statement</h3>");
        }
    } else {
        throw new Exception("<h3>Connection failed: " . mysqli_connect_error() . "</h3>");
    }
} catch (Exception $e) {
    $response = "Something went wrong! Please try again. If the issue persists, please contact the administrator.";
}

echo $response;
?>
