<?php
include 'connection.php';


$msg = '';
$fittername = 'Not Logged In';
$flag = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userpin = $_GET['password'];
    $query = "SELECT fittername FROM fitters WHERE password='$userpin' and cables=1 ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $fittername = $row['fittername'];
            $flag = 1;
        } else {
            $msg = 'Either you have entered a wrong PIN or you dont have access to order console.Contact Project Manager';
            $flag = 0;
        }
    } else {
        $msg = 'Query failed: ' . mysqli_error($conn);
        $flag = 0;
    }
}
session_start();
$$_SESSION['fittername']=$fittername;
$_SESSION['password']=$userpin;


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
	h2{
		color:red;
		
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
	$query = "SELECT jobid,projectmanager, type FROM jobs where allocatedfitter='$fittername' and currentstate='InProgress'";
$result = mysqli_query($conn, $query);
$options = '';
while ($row = mysqli_fetch_assoc($result)) {
	$columnValue = $row['jobid'];
	$options .= "<option value=\"$columnValue\">$columnValue</option>";
}
    echo " 
			 <form method=\"post\" id=\"myForm\" action=\"cablesorder1.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <div class=\"form-cube\">
			<h2>Hello " . $fittername . "</h2><br>
                <h3 id=\"heading\">Scan/Enter the Cable PartNo</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                </div><center><a id=\"\" class=\"ri-logout-circle-line\" href=\"cablesindex.html\">Logout</a></center>
            </div> 
</div>
</form> ";
} else {
    echo "
<form method=\"GET\" id=\"myForm\" action=\"validateusercables.php\">
	<br><br>
	<div class=\"signup-container\"> 
            <!-- Box container containing elements -->
            <div class=\"form-cube\"> 
		<h4>".$msg."</h4><br>
                <h1 id=\"heading\">Scan/Enter the PIN</h1>
		<div class=\"input-field\" id=\"idFld\"> <i class=\"ri-lock-fill\"></i>
        <input type=\"password\" id=\"barcodeInput\" name=\"password\" autofocus>                        </div>
						<button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\" >Login</button>
						     </div></div>
</form>";

}
?>
</body>
</html>
