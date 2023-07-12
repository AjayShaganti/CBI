<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$partNo = $_POST['partNo'];
$quantitynew = $_POST['newQuantity'];
$backorder = $_POST['oldQuantity'];

$status='Partially Received';
$response ='';





if ($conn) {
    try {
        $newbackorder=$backorder-$quantitynew;

        if ($conn) { 
            // Prepare the SQL statement with placeholders
            $updateQuery = "UPDATE reordercables SET Received = Received + ? , BackOrder = ?,Status=?, lastupdated = ? WHERE PartNo = ?";
            $statement = mysqli_prepare($conn, $updateQuery);

            if ($statement) {
                // Bind the parameters to the statement
                mysqli_stmt_bind_param($statement, 'sssss',$quantitynew, $newbackorder,$status, date('Y-m-d H:i:s'), $partNo);

                // Execute the statement
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response = " Recieved Quantity has been updated for Part No: $partNo";
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
        $response = "Something wrong! Please try again. If the issue persists, please contact the administrator.";
    }
}

if ($newbackorder<=0) {
    try {
        if ($conn) {
            // Prepare the SQL statement with placeholders
            $InsertQuery = "INSERT INTO orderhistory(BinLocation, PartName, PartNo, Category, Available, Ordered, PurchaseOrder, LastUpdated, Comments) select BinLocation, PartName, PartNo, Category,0, Received, PurchaseOrder, LastUpdated, Comments from reordercables WHERE PartNo = ? ";
            $statement = mysqli_prepare($conn, $InsertQuery);
			
            if ($statement) {
				mysqli_stmt_bind_param($statement, 's', $partNo);
                // Execute the statement
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response .= " and details sent to order history ";
                } else {
                    throw new Exception("<h3>Failed to delete the record</h3>");
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
        $response = "Something went wrong! Please try again.";
    }
}

if ($newbackorder<=0) {
    try {
        if ($conn) {
            // Prepare the SQL statement with placeholders
            $deleteQuery = "DELETE FROM reordercables WHERE PartNo = ?";
            $statement = mysqli_prepare($conn, $deleteQuery);
			
            if ($statement) {
                // Bind the parameter to the statement
                mysqli_stmt_bind_param($statement, 's', $partNo);

                // Execute the statement
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response .= " and Record has been deleted from this list.";
                } else {
                    throw new Exception("<h3>Failed to delete the record</h3>");
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
        $response = "Something went wrong! Please try again.";
    }
}

echo $response;
?>
