<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $column = $_POST['column'];
    $value = $_POST['value'];
    $binlocation = $_POST['binlocation'];
	
    // Prepare the UPDATE query
    $query = "UPDATE tophathymod SET `$column` = ?,LastUpdated=now() WHERE BinLocation = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "ss", $value, $binlocation);

    // Execute the query or throw an exception
    try {
        if (mysqli_stmt_execute($stmt)) {
            // Data update was successful
            $response = array(
                'success' => true,
                'message' => 'Data updated successfully'
            );
        } else {
            // Data update failed
            $response = array(
                'success' => false,
                'message' => 'Failed to update data'
            );
        }
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        );
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
