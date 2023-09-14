<?php
include 'connection.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$msg = '';
$flag = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = $_POST['effectedquantity'];
    $partno = $_SESSION['partno'];

    if ($required > 0) {
        // Check if the record exists in reordercables table
        $selectQuery = "SELECT * FROM reorderconsumables WHERE PartNo = ?";
        $selectStatement = mysqli_prepare($conn, $selectQuery);
        mysqli_stmt_bind_param($selectStatement, 's', $partno);
        mysqli_stmt_execute($selectStatement);
        $result = mysqli_stmt_get_result($selectStatement);

        if (mysqli_num_rows($result) > 0) {
            // Update the existing record
            $updateQuery = "UPDATE reorderconsumables
            SET Required = ?,
            BackOrder = BackOrder + (? - BackOrder)
            WHERE PartNo = ?";
            $updateStatement = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStatement, 'iis', $required, $required, $partno);
            mysqli_stmt_execute($updateStatement);

            $msg = 'PartNo ' . $partno . ' already exists. Updated existing value to ' . $required;
            $flag = 1;
			mysqli_stmt_close($updateStatement);
        } else {
            // Insert a new record
            $insertQuery = "INSERT INTO reorderconsumables (BinLocation, PartName, PartNo, Category, Required, BackOrder, PurchaseOrder, Comments)
            SELECT BinLocation, PartName, ?, 'Consumables', ?, (? - 0), 'NA', 'NA' FROM consumables WHERE PartNo = ?";
            $insertStatement = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStatement, 'siss', $partno, $required, $required, $partno);
            mysqli_stmt_execute($insertStatement);

            $msg = 'No\'s: ' . $required . ' of PartNo ' . $partno . ' added to orders list.';
            $flag = 1;

            mysqli_stmt_close($insertStatement);
        }

        mysqli_stmt_close($selectStatement);
        
    } else {
        $msg = 'Please enter a valid number';
        $flag = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBI | Stock Management System</title>
    <!-- References to external basic CSS file -->
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <!-- Favicon for tab -->
    <link rel="icon" type="image/x-icon" href="images/game-fill.png">
    <!-- Reference to web icons from Remixicon.com -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Reference to web icons from Fontawesome -->
    <script src="https://kit.fontawesome.com/d7376949ab.js" crossorigin="anonymous"></script>
    <!-- References to external fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        h3 {
            color: red;
        }

        #header {
            /* background-image: url('path/to/your/image.jpg'); */
        }
    </style>
    <script type="text/javascript">
        function handleBarcodeInput(event) {
            var barcodeInput = document.getElementById("barcodeInput");
            barcodeInput.value = event.data; // Set the input value to the scanned barcode data
            document.getElementById("myForm").submit(); // Submit the form
        }

        function submitForm(action) {
            document.getElementById("myForm").action = action;
            document.getElementById("myForm").submit();
        }

        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener("message", handleBarcodeInput);
        });
    </script>
</head>
<body>
<section id="page-top-2">
    <h4 class="sub-header white-txt">Stock Management System</h4>
    <br>
    <h4 class="white-txt center"><center><strong>Welcome to Consumables Re-Order Console</strong><center></h4>
</section>

<?php
 echo "
    <br><br>
    <form method=\"post\" id=\"myForm\" action=\"consumables1.php\">
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <div class=\"form-cube\">
                <h4><strong></strong>" . $msg . "</h4><br>
                <h3 id=\"heading\">Scan/Enter the PartNo</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                </div><center><a id=\"\" class=\"ri-logout-circle-line\" href=\"consumableslogin.html\">Logout</a></center>				
            </div>
            <center>  
        </div>
    </form>
    ";

?>
</body>
</html>
