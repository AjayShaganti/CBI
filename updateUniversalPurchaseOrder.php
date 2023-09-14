<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$tabNumber = $_POST['tabNumber'];
$partNo = $_POST['partNo'];
$purchaseOrder = $_POST['purchaseOrder'];
$status = 'Ordered';

if(($purchaseOrder!='NA')&&($purchaseOrder!='na'))
{
try {
    if ($conn) {
        // Determine which table to update based on tab number
        $tableName = '';
        switch ($tabNumber) {
            case 1:
                $tableName = 'reorderhmtp';
                break;
            case 2:
                $tableName = 'reorder';
                break;
            case 3:
                $tableName = 'reordercables';
                break;
            case 4:
                $tableName = 'reorderconsumable';
                break;
            case 5:
                $tableName = 'reorderlabels';
                break;
            default:
                throw new Exception("<h3>Invalid tab number</h3>");
        }

        // Prepare the SQL statement with placeholders
        $updateQuery = "UPDATE $tableName SET PurchaseOrder = ?, Status = ?, lastupdated = ? WHERE PartNo = ?";
        $statement = mysqli_prepare($conn, $updateQuery);

        if ($statement) {
            // Bind the parameters to the statement
            mysqli_stmt_bind_param($statement, 'ssss', $purchaseOrder, $status, date('Y-m-d H:i:s'), $partNo);

            // Execute the statement
            $result = mysqli_stmt_execute($statement);

            if ($result) {
				$response = array(
                'success' => true,
                'message' => "Purchase Order has been updated to $purchaseOrder for Part No: $partNo in table $tableName"
            );
			
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
	$response = array(
                'success' => false,
				'message' => "Something went wrong! Please try again. If the issue persists, please contact the administrator."
				);
}
}
else
{
	$response = array(
                'success' => true,
				'message' => "The value is NA so cant update."
				);
}
// Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>
