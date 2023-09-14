<?php
include 'connection.php';
session_start();
$password=$_SESSION['password'];
$msg = '';
$flag = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partno = $_POST['barcode'];
    $_SESSION['partno'] = $partno;
    $query = "SELECT * FROM labels WHERE PartNo = '$partno'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $BinLocation = $row['BinLocation'];
            $PartName = $row['PartName'];
            $PartNo = $row['PartNo'];
            $Max = $row['Max'];
            $Min = $row['Min'];
            $OrderUnitSpec = $row['OrderUnitSpec'];
            $PackQty = $row['PackQty'];
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
        h3 {
            color: red;
        }

        #header {
            /* background-image: url('path/to/your/image.jpg'); */
        }
		        @media screen and (max-width: 768px) {
            /* Adjust styles for smaller screens */
            .sub-header.white-txt {
                font-size: 20px;
            }

            .white-txt.center {
                font-size: 18px;
            }

            .form-cube {
                width: 90%;
                margin: 0 auto;
            }

            .form-cube h4,
            .form-cube h2,
            .form-cube h3 {
                font-size: 16px;
            }

            #fill {
                font-size: 16px;
            }
        }
    </style>
    <script type="text/javascript">
        function handleBarcodeInput(event) {
            var barcodeInput = document.getElementById("barcodeInput");
            barcodeInput.value = event.data; // Set the input value to the scanned barcode data
            document.getElementById("myForm").submit(); // Submit the form
        }

        function disableEnterKey(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                return false;
            }
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
    <h4 class="white-txt center"><center><strong>Welcome to Labels Re-Order/Recieve Console</strong></center></h4>
</section>

<?php
if ($flag) {
    echo "
    <br><br>
  <form method=\"post\" id=\"myForm\" onkeydown=\"disableEnterKey(event)\" action=\"\">
    <div class=\"signup-container\">
      <!-- Box container containing elements -->
      <div class=\"form-cube\">
        <h4><strong>BinLocation: </strong> " . $BinLocation . "</h4><br>
        <h4><strong>PartNo: </strong> " . $PartNo . "</h4><br>
        <h4><strong>PartName: </strong> " . $PartName . "</h4><br>
        <h4><strong>Max Stock (No's): </strong> " . $Max . "</h4><br>
        <h4><strong>Min Stock (No's): </strong> " . $Min . "</h4><br>
        <h4><strong>Order Unit Spec: </strong> " . $OrderUnitSpec . "</h4><br>
        <h4><strong>Pack Qty: </strong> " . $PackQty . "</h4><br>
        <h1 id=\"heading\">Enter No's to be Ordered</h1>
        <div class=\"input-field\" id=\"idFld\">
          <input type=\"number\" id=\"quantity\" name=\"effectedquantity\" autofocus>
        </div>
        <button id=\"fill\" class=\"used\" type=\"button\" onclick=\"submitForm('labels2.php')\">Order</button>
		<br>
		<br>
		<br>
		<button id=\"fill-green\" class=\"receive\" type=\"button\" onclick=\"receive('$PartNo')\">Receive</button><br><br><br>
		<button id=\"no-fill\"}><strong><a href=\"validateuserlabels.php?password=" . urlencode($password) . "\" style=\"margin-left:0%;\"> &#x1F50D Scan New Item</a><strong></button><br>
      </div>
      <center>
        <a id=\"\" class=\"ri-logout-circle-line\" href=\"labelslogin.html\">Logout</a>
      </center>
    </div>
  </form>    ";
} else {
    echo "
    <form method=\"post\" id=\"myForm\" action=\"labels1.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <br>  
            <h2 style=\"text-align:center;\">$msg</h2>  
            <div class=\"form-cube\">
          <h3 id=\"heading\">Scan/Enter the PartNo</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                </div>
            </div><center> <a id=\"\" class=\"ri-logout-circle-line\" href=\"labelslogin.html\">Logout</a>
            </center>     
        </div>
    </form>
    ";
	

}
	$flag1 = 0;
	$BackOrder=0;
    $query1 = "SELECT BackOrder FROM reorderlabels WHERE PartNo = '$partno'";
    $result1 = mysqli_query($conn, $query1);

    if ($result1) {
        if (mysqli_num_rows($result1) > 0) {
            $row = mysqli_fetch_assoc($result1);
            $BackOrder = $row['BackOrder'];
            $flag1 = 1;
        } else {
            $msg = 'Please enter a valid PartNo';
            $flag1 =0;
        }
    } else {
        $msg = 'Query failed: ' . mysqli_error($conn);
        $flag1 = 0;
    }
?>
</body>
</html>
<script>
function receive(partNo) {
    var newQuantity = document.getElementById('quantity').value;
	    // Display a confirmation prompt with the new value
	
	var flag1 = <?php echo $flag1; ?>;
	if(flag1)
	{
		var oldQuantity = <?php echo $BackOrder; ?>;
    var confirmed = confirm("Are you sure you want to update the Received Quantity to " + newQuantity + "?");


    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&newQuantity=' + encodeURIComponent(newQuantity)+
               '&oldQuantity=' + encodeURIComponent(oldQuantity);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'partialOrderReceivedlabels.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);

            // Refresh the page
            location.reload();
        }
    };
    xhr.send(data);}
	else
	{
		
		alert("No order placed for this item");
	}
}
</script>
