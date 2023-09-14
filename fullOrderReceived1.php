<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$partNo = $_POST['partNo'];
$quantitynew = $_POST['quantity'];
$flag = 0;
$response ='';
$error='error:';

try {
    if ($conn) {
        $query = "SELECT Quantity FROM tophathymod WHERE PartNo = ?";
        $statement = mysqli_prepare($conn, $query);

        if ($statement) {
            mysqli_stmt_bind_param($statement, 's', $partNo);
            mysqli_stmt_execute($statement);

            $result = mysqli_stmt_get_result($statement);

            if ($result) {
                $record = mysqli_fetch_assoc($result);

                if ($record) {
                    $quantityOld = $record['Quantity'];
                    $flag = 1;
                } else {
                    $response = "No record found for Part No: $partNo";
                }
            } else {
                throw new Exception("<h3>Failed to fetch result</h3>");
            }

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

if ($flag) {
    try {
        $quantity = $quantityOld + $quantitynew;

        if ($conn) {
            // Prepare the SQL statement with placeholders
            $updateQuery = "UPDATE tophathymod SET Quantity = ?, lastupdated = ? WHERE PartNo = ?";
            $statement = mysqli_prepare($conn, $updateQuery);

            if ($statement) {
                // Bind the parameters to the statement
                mysqli_stmt_bind_param($statement, 'sss', $quantity, date('Y-m-d H:i:s'), $partNo);

                // Execute the statement
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response = "Quantity has been updated to $quantity for Part No: $partNo";
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


if ($flag) {
    try {
        if ($conn) {
            // Prepare the SQL statement with placeholders
            $InsertQuery = "INSERT INTO orderhistory(BinLocation, PartName, PartNo, Category, Available, Ordered, PurchaseOrder, LastUpdated, Comments) select BinLocation, PartName, PartNo, Category, Available, Required, PurchaseOrder, LastUpdated, Comments from reorderhmtp WHERE PartNo = ? ";
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



if ($flag) {
    try {
        if ($conn) {
            // Prepare the SQL statement with placeholders
            $deleteQuery = "DELETE FROM reorderhmtp WHERE PartNo = ?";
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
