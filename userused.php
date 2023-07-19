<?php
include 'connection.php';
session_start();
$fittername = $_SESSION['fittername'];
$PartNo = $_SESSION['PartNo'];
$PartName = $_SESSION['PartName'];
$Limit = $_SESSION['Limit'];
$Category = $_SESSION['Category'];
$jobid = $_SESSION['jobid'];
$ppu = $_SESSION['ppu'];
$BinLocation = $_SESSION['BinLocation'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$msg = '';
$flag = 0;
$msg1 = '';

$query = "SELECT * FROM tophathymod WHERE PartNo = '$PartNo'";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $Quantity = $row['Quantity'];
        $_SESSION['Quantity'] = $Quantity;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $effectedquantity = $_POST['effectedquantity'];
    if (($effectedquantity <= $Limit) && ($effectedquantity > 0)) {
        $flag = 1;
        $QuantityLeft = $Quantity - $effectedquantity;
    } else {
        $flag = 0;
    }
}

if ($flag) {
    $query = "UPDATE tophathymod SET Quantity = Quantity - ?,LastUpdated=? WHERE PartNo = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        $partNo = $_SESSION['PartNo'];
		$date1=date('Y-m-d H:i:s');

        mysqli_stmt_bind_param($stmt, "iss", $effectedquantity,$date1, $partNo);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $msg = $effectedquantity . " No's " . $partNo . " is used for " . $jobid;
        } else {
            $msg = 'Please enter a valid number.';
        }

        mysqli_stmt_close($stmt);
    } else {
        $msg = 'Query preparation failed: ' . mysqli_error($conn);
    }

}

// Fetching sales orders
$query = "SELECT jobid, projectmanager, type FROM jobs WHERE allocatedfitter = '$fittername' AND currentstate = 'InProgress'";
$result = mysqli_query($conn, $query);
$options = '';
while ($row = mysqli_fetch_assoc($result)) {
    $columnValue = $row['jobid'];
    $options .= "<option value=\"$columnValue\">$columnValue</option>";
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

        body {
            font-family: 'Jost', sans-serif;
            margin: 0;
            padding: 0;
        }

        section {
            background-color: #007bff;
            padding: 20px;
            text-align: center;
        }

        .white-txt {
            color: #fff;
            font-size: 18px;
            margin: 0;
        }

        .center {
            text-align: center;
        }

        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-cube {
            width: 90%;
            max-width: 400px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
        }

        .form-cube h2,
        .form-cube h3 {
            color: red;
            text-align: center;
            margin: 0 0 20px 0;
        }

        .form-cube h4 {
            margin: 0;
        }

        .form-cube label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .form-cube select,
        .form-cube input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-cube .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .form-cube .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
            color: #888;
        }

        .form-cube button {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .ri-logout-circle-line {
            display: block;
            font-size: 24px;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .form-cube {
                width: 100%;
                max-width: 100%;
                padding: 10px;
            }

            .form-cube h2,
            .form-cube h3 {
                font-size: 20px;
            }

            .form-cube h4,
            .form-cube label,
            .form-cube select,
            .form-cube input[type="text"] {
                font-size: 14px;
            }

            .ri-logout-circle-line {
                font-size: 20px;
            }
        }
    </style>
    <script type="text/javascript">
        function handleBarcodeInput(event) {
            var barcodeInput = document.getElementById("barcodeInput");
            barcodeInput.value = event.data; // Set the input value to the scanned barcode data
            document.getElementById("myForm").submit(); // Submit the form
        }

        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener("message", handleBarcodeInput);
        });

        function submitForm(action) {
            var form = document.getElementById('myForm');
            var effectedquantityInput = document.getElementById('quantity');
            effectedquantityInput.value = effectedquantityInput.value.trim(); // Trim any leading/trailing whitespace

            if (effectedquantityInput.value !== '') {
                form.action = action;
                form.submit();
            } else {
                alert('Please enter a valid quantity.');
            }
        }
		       function submitForm(action) {
            document.getElementById("myForm").action = action;
            document.getElementById("myForm").submit();
        }
    </script>
</head>

<body>
    <section id="page-top-2">
        <h4 class="sub-header white-txt">Stock Management System</h4>
        <br>
        <h4 class="white-txt center">Logged in as: <strong><?php echo $fittername; ?></strong></h4>
    </section>

    <?php if ($flag) : ?>
	<?php    // Inserting transaction record
    //$insertQuery = "INSERT INTO transactions (jobid, fittername, partno, category, quantity,netquantity, scandate, type) VALUES (?, ?, ?, ?, ?, ?, ?,?)";

	$insertQuery = "INSERT INTO transactions (jobid, fittername,BinLocation, partno,partname,PricePerUnit, category, quantity, netquantity, scandate, type) VALUES (?, ?, ?,?, ?, ?,?,?, ?, ?, ?)";
	$insertStmt = mysqli_prepare($conn, $insertQuery);
	
	if ($insertStmt) {
		$category = $_SESSION['Category'];
		$type = 'used';
	
		mysqli_stmt_bind_param($insertStmt, "ssssssssiss", $jobid, $fittername,$BinLocation, $partNo,$PartName,$ppu, $category, $effectedquantity, $QuantityLeft, $currentDateTime, $type);
		mysqli_stmt_execute($insertStmt);
		mysqli_stmt_close($insertStmt);
	} else {
		$msg1 = 'Insert query preparation failed: ' . mysqli_error($conn);
	}
?>
        <br><br>
        <div class="signup-container">
            <!-- Box container containing elements -->
            <div class="form-cube">
                <form method="post" id="myForm" action="userorder.php">
                    <h4><strong>Available Quantity:</strong> <?php echo $QuantityLeft; ?></h4><br>
                    <h3><?php echo $msg; ?></h3><br>
                    <label for="jobid">Sales Order:</label>
                    <select id="jobid" name="jobid">
                        <?php echo $options; ?>
                    </select>
                    <h2 id="heading">Scan/Enter the PartNo of the Item</h2>
                    <div class="input-field" id="idFld">
                        <input type="text" id="barcodeInput" name="barcode" autofocus>
                    <br>
                    <br> </div>
					<button id="fill-blue" class="used" type="button" onclick="submitForm('userusage.php')">Check Usage</button><p> You can check the parts used by you under this sales order</p><br><br>
                    <center><a id="" class="ri-logout-circle-line" href="userlogin.html">Logout</a></center>
               </form>
            </div>
        </div>

    <?php else : ?>

      <br><br>
        <div class="signup-container">
            <!-- Box container containing elements -->
            <div class="form-cube">
       <form method="post" id="myForm" action="userused.php">
                    <h3>**Enter a valid number<br>Scan and enter multiple times if you're using more than allowed limit**</h3>
                    <h4><strong>PartNo:</strong> <?php echo $PartNo; ?></h4><br>
                    <h4><strong>Available Quantity:</strong> <?php echo $Quantity; ?></h4><br>
                    <h4><strong>Sales Order No:</strong> <?php echo $jobid; ?></h4><br>
                    <h4><strong>Allowed Limit:</strong> <?php echo $Limit; ?></h4><br>
                    <h1 id="heading">Enter the effectedquantity used/restored</h1>
                    <div class="input-field" id="idFld">
                        <input type="text" id="effectedquantity" name="effectedquantity" autofocus onkeypress="handleKeyPress(event)">
                    </div>
					              
                    <button id="fill" class="used" type="button" onclick="submitForm('userused.php')">Used</button><br><br>
                    <button id="fill-green" class="restored" type="button" onclick="submitForm('userrestored.php')">Restored</button>
					<br>
                    <br>
                
                    <center><a id="" class="ri-logout-circle-line" href="userlogin.html">Logout</a></center>
                </div></div>
        </form>
            </div>        </div>
    <?php endif; ?>
</body>

</html>
