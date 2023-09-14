<?php
include 'connection.php';
session_start();
$fittername = $_SESSION['fittername'];
$password=$_SESSION['password'];

$msg = '';
$flag = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partno = $_POST['barcode'];
    $jobid = $_POST['jobid'];
    $_SESSION['jobid'] = $jobid;
    $query = "SELECT * FROM tophathymod WHERE PartNo = '$partno'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $BinLocation = $row['BinLocation'];
			$_SESSION['BinLocation'] = $BinLocation;
            $PartName = $row['PartName'];
			$_SESSION['PartName'] = $PartName;
            $PartNo = $row['PartNo'];
            $_SESSION['PartNo'] = $PartNo;
            $Quantity = $row['Quantity'];
            $_SESSION['Quantity'] = $Quantity;
            $Limit = $row['Limit'];
            $_SESSION['Limit'] = $Limit;
            $Category = $row['Category'];
            $_SESSION['Category'] = $Category;
			$ppu = $row['PricePerUnit'];
            $_SESSION['ppu'] = $ppu;
            $flag = 1;
        } else {
            $msg = 'Please enter a valid PartNo';
            $flag = 0;
        }
    } else {
        $msg = 'Query failed: ' . mysqli_error($conn);
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
        h6 {
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

        .form-cube h5 {
            margin: 0;
        }

        .form-cube label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .form-cube select,
        .form-cube input[type="text"],
        .form-cube input[type="password"] {
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

            .form-cube h5,
            .form-cube label,
            .form-cube select,
            .form-cube input[type="text"],
            .form-cube input[type="password"] {
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
    <h4 class="white-txt center"><center>Logged in as: <strong><?php echo $fittername; ?></strong><center></h4>
</section>

<?php
if ($flag) {
    echo "
    <br><br>
    <form method=\"post\" id=\"myForm\" action=\"userused.php\">
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <div class=\"form-cube\">
                <h5><strong>BinLocation: </strong> " . $BinLocation . "</h5><br>
                <h5><strong>PartNo: </strong> " . $PartNo . "</h5><br>
                <h5><strong>PartName: </strong> " . $PartName . "</h5><br>
                <h5><strong>Available Quantity: </strong> " . $Quantity . "</h5><br>
                <h5><strong>Sales Order No: </strong> " . $jobid . "</h5><br>
                <h5><strong>Allowed Limit: </strong> " . $Limit . "</h5><br>
                <h5><strong>Category: </strong> " . $Category . "</h5><br>
                <h6>**Scan and enter multiple times if you're using more than allowed limit**</h6>
                <h3 id=\"heading\">Enter the quantity used/restored</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"quantity\" name=\"effectedquantity\" autofocus>
                </div>
                <button id=\"fill\" class=\"used\" type=\"button\" onclick=\"submitForm('userused.php')\">Used</button><br><br>
                <button id=\"fill-green\" class=\"restored\" type=\"button\" onclick=\"submitForm('userrestored.php')\">Restored</button>
				<br><br><strong><a href=\"validateuser.php?password=" . urlencode($password) . "\" style=\"margin-left:0%;\"> &#x1F50D Scan New Item</a><strong><br><br><center><a id=\"\" class=\"ri-logout-circle-line\" href=\"userlogin.html\">Logout</a></center>
            </div>
              
        </div>
    </form>
    ";
} else {
    $query = "SELECT jobid, projectmanager, type FROM jobs WHERE allocatedfitter='$fittername' AND currentstate='InProgress'";
    $result = mysqli_query($conn, $query);
    $options = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $columnValue = $row['jobid'];
        $options .= "<option value=\"$columnValue\">$columnValue</option>";
    }
    echo "
    <form method=\"post\" id=\"myForm\" action=\"userorder.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <br>  
            
            <div class=\"form-cube\">
			<h2 style=\"text-align:center;\">$msg</h2>  
                <h2>Hello $fittername</h2><br>
                <label for=\"fitter\">Sales Order:</label>
                <select id=\"jobid\" name=\"jobid\">$options</select>
                <h3 id=\"heading\">Scan/Enter the PartNo of the Item</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                </div>
            
            <center><a id=\"\" class=\"ri-logout-circle-line\" href=\"userlogin.html\">Logout</a></center>      </div>
        </div>
    </form>
    ";
}
?>
</body>
</html>
