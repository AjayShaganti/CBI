<?php
include 'connection.php';

$msg = '';
$fittername = 'NA';
$flag = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userpin = $_POST['password'];
    $query = "SELECT fittername FROM fitters WHERE password='$userpin'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $fittername = $row['fittername'];
            $flag = 1;
        } else {
            $msg = 'Please enter a valid PIN';
            $flag = 0;
        }
    } else {
        $msg = 'Query failed: ' . mysqli_error($conn);
        $flag = 0;
    }
}
session_start();
$_SESSION['fittername'] = $fittername;

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
        h2 {
            color: red;
        }

        #header {
            /* background-image: url('path/to/your/image.jpg'); */
        }
    </style>
    <script type="text/javascript">
        function startBarcodeScanner() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#barcodeScanner')
                },
                decoder: {
                    readers: ["ean_reader"] // Specify the desired barcode reader (EAN in this case)
                }
            }, function(err) {
                if (err) {
                    console.error("Error initializing Quagga:", err);
                    return;
                }
                console.log("Quagga initialized.");

                Quagga.start(); // Start the barcode scanning
            });

            Quagga.onDetected(function(data) {
                var barcode = data.codeResult.code;
                console.log("Detected barcode:", barcode);
                document.getElementById("barcodeInput").value = barcode; // Set the input value to the scanned barcode data
                document.getElementById("myForm").submit(); // Submit the form
            });
        }

        function stopBarcodeScanner() {
            Quagga.stop(); // Stop the barcode scanning
        }
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
                <div class=\"form-cube\">
                    <h2>Hello " . $fittername . "</h2><br>
                    <label for=\"fitter\">Sales Order:</label>
                    <select id=\"jobid\" name=\"jobid\">";
        echo $options;
        echo "</select>
                    <h3 id=\"heading\">Scan/Enter the PartNo of the Item</h3>
                    <div class=\"input-field\" id=\"idFld\">
                        <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                    </div>
                </div>
                <center><a id=\"\" class=\"ri-logout-circle-line\" href=\"userlogin.html\">Logout</a></center>
            </div>
        </form> ";
    } else {
        echo "
        <form method=\"post\" id=\"myForm\" action=\"validateuser.php\">
            <br><br>
            <div class=\"signup-container\">
                <!-- Box container containing elements --> <br> <h2 style=\"text-align:center;\">" . $msg . "</h2>
                <div class=\"form-cube\">
                    <h1 id=\"heading\">Scan/Enter the PIN</h1>
                    <div class=\"input-field\" id=\"idFld\"><i class=\"ri-lock-fill\"></i>
                        <input type=\"password\" id=\"barcodeInput\" name=\"password\" autofocus>
                    </div>
                    <button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\">Login</button>
                </div>
            </div>
        </form>";
    }
    ?>

    <div id="barcodeScanner"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        // Check if the browser supports camera access
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            startBarcodeScanner();
        } else {
            console.error("Camera access not supported by the browser.");
        }
    </script>
</body>

</html>
