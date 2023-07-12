<?php
include 'connection.php';

// Retrieve the values sent from the JavaScript code
$partNo = $_POST['partNo'];
$quantitynew = $_POST['quantity'];
$flag = 0;
$response = '';

try {
    if ($conn) {
        $query = "SELECT Quantity, special FROM stock WHERE PartNo = ?";
        $statement = mysqli_prepare($conn, $query);

        if ($statement) {
            mysqli_stmt_bind_param($statement, 's', $partNo);
            mysqli_stmt_execute($statement);

            $result = mysqli_stmt_get_result($statement);

            if ($result) {
                $record = mysqli_fetch_assoc($result);

                if ($record) {
                    $quantityOld = $record['Quantity'];
                    $special = $record['special'];
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
        $quantity = $quantitynew;

        if ($conn) {
            if ($special) {
                $updateQuery = "UPDATE stock SET BF16Back =BF16Back+ ?, lastupdated = ? WHERE PartNo = ?";
                $response = "3rd stock not available";
            } else {
                $updateQuery = "UPDATE stock SET 3rdStock = 3rdStock+?, lastupdated = ? WHERE PartNo = ?";
                $response = "3rd stock available";
            }
            $statement = mysqli_prepare($conn, $updateQuery);

            if ($statement) {
                mysqli_stmt_bind_param($statement, 'sss', $quantity, date('Y-m-d H:i:s'), $partNo);
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response .= " and Quantity has been updated for Part No: $partNo";
                } else {
                    throw new Exception("<h3>Failed to update</h3>");
                }

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
            $InsertQuery = "INSERT INTO orderhistory(BinLocation, PartName, PartNo, Category, Available, Ordered, PurchaseOrder, LastUpdated, Comments) SELECT BinLocation, PartName, PartNo, Category, Available, Required, PurchaseOrder, LastUpdated, Comments FROM reorder WHERE PartNo = ?";
            $statement = mysqli_prepare($conn, $InsertQuery);

            if ($statement) {
                mysqli_stmt_bind_param($statement, 's', $partNo);
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response .= " and details sent to order history";
                } else {
                    throw new Exception("<h3>Failed to delete the record</h3>");
                }

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
            $deleteQuery = "DELETE FROM reorder WHERE PartNo = ?";
            $statement = mysqli_prepare($conn, $deleteQuery);

            if ($statement) {
                mysqli_stmt_bind_param($statement, 's', $partNo);
                $result = mysqli_stmt_execute($statement);

                if ($result) {
                    $response .= " and Record has been deleted from this list.";
                } else {
                    throw new Exception("<h3>Failed to delete the record</h3>");
                }

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
