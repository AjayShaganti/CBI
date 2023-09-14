<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$partNo = $_POST['partNo'];
$quantitynew = $_POST['quantity'];
$flag=0;
$response ='';
$error='error:';

if ($conn) {
    try {
        if ($conn) {
            // Prepare the SQL statement with placeholders
            $InsertQuery = "INSERT INTO orderhistory(BinLocation, PartName, PartNo, Category, Available, Ordered, PurchaseOrder, LastUpdated, Comments) select BinLocation, PartName, PartNo, Category,0,?, PurchaseOrder, LastUpdated, Comments from reorderlabels WHERE PartNo = ? ";
            $statement = mysqli_prepare($conn,$InsertQuery);
			
            if ($statement) {
				mysqli_stmt_bind_param($statement, 'ss',$quantitynew, $partNo);
                // Execute the statement
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response .= "Details sent to order history ";
					$flag=1;
                } else {
                    throw new Exception("<h3>Failed to update the record</h3>");
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
        $response = "Something went wrong! Please try again.".$partNo. ",".$quantitynew;
    }
}



if ($flag) {
    try {
        if ($conn) {
            // Prepare the SQL statement with placeholders
            $deleteQuery = "DELETE FROM reorderlabels WHERE PartNo = ?";
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
