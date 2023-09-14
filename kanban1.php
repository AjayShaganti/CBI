<?php
include 'connection.php';
session_start();
$password=$_SESSION['password'];
$msg = '';
$flag = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partno = $_POST['barcode'];
    $_SESSION['partno'] = $partno;
    $query = "SELECT * FROM stock WHERE PartNo = '$partno'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $BinLocation = $row['BinLocation'];
            $PartName = $row['PartName'];
            $PartNo = $row['PartNo'];
            $bf16 = $row['BF16Back'];
            $stock = $row['3rdStock'];
            $total = $row['Quantity'];
            $Max = $row['Max'];
            $Min = $row['Min'];
			$special = $row['special'];
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
    <h5 class="sub-header white-txt">Stock Management System</h5>
</section>

<?php
if ($flag) {
    echo "
    <br><br>
  <form method=\"post\" id=\"myForm\" onkeydown=\"disableEnterKey(event)\" action=\"\">
    <div class=\"signup-container\">
      <!-- Box container containing elements -->
      <div class=\"form-cube\">
        <h5><strong>BinLocation: </strong> " . $BinLocation . "</h5><br>
        <h5><strong>PartNo: </strong> " . $PartNo . "</h5><br>
        <h5><strong>PartName: </strong> " . $PartName . "</h5><br>
        <h5><strong>BF16 Back: </strong> " . $bf16 . "</h5><br>
        <h5><strong>3rd Stock: </strong> " . $stock . "</h5><br>
        <h5><strong>Total Stock: </strong> " . $total . "</h5><br>
        <h5><strong>Max Stock (No's): </strong> " . $Max . "</h5><br>
        <h5><strong>Min Stock (No's): </strong> " . $Min . "</h5><br>
        <h3 id=\"heading\">Enter the Quantity affected</h3>
        <div class=\"input-field\" id=\"idFld\">
          <input type=\"number\" id=\"quantity\" name=\"effectedquantity\" autofocus>
        </div>";
		   if ($special) {
        echo "<h6 hidden>3rd Stock to BF16 Back</h6><button id=\"fill-blue\" class=\"transferdisabled\" type=\"button\" hidden>Transfer</button><br><br>";
    } else {
        echo "<h6>3rd Stock to BF16 Back</h6><button id=\"fill-blue\" class=\"transfer\" type=\"button\" onclick=\"transfer('$PartNo')\">Transfer</button><br><br>";
    }

echo "
    <h6>BF16 Back to BF16 Front</h6><button id=\"fill\" class=\"consume\" type=\"button\" onclick=\"consume('$PartNo')\">Consume</button><br><br>
    <button id=\"fill-green\" class=\"receive\" type=\"button\" onclick=\"receive('$PartNo')\">Receive</button><br><br>
    <button id=\"no-fill\"}><strong><a href=\"validateuserkanban.php?password=" . urlencode($password) . "\" style=\"margin-left:0%;\"> &#x1F50D Scan New Item</a><strong></button><br><br><br>
    <center>
        <a id=\"\" class=\"ri-logout-circle-line\" href=\"kanbanlogin.html\">Logout</a>
    </center>
</div>
";
} else {
    echo "
    <form method=\"post\" id=\"myForm\" action=\"kanban1.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <br>  
            <div class=\"form-cube\">
			<h2 style=\"text-align:center;\">$msg</h2>  
                <h3 id=\"heading\">Scan/Enter the PartNo</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                </div>
            
            <center> <a id=\"\" class=\"ri-logout-circle-line\" href=\"kanbanlogin.html\">Logout</a>
            </center> </div>    
        </div>
    </form>
    ";
}
	$flag1 = 0;
	$BackOrder=0;
    $query1 = "SELECT BackOrder FROM reorder WHERE PartNo = '$partno'";
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
function transfer(partNo) {
    var quantity = document.getElementById('quantity').value;
	    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to transfer '+quantity+' of '+partNo+ '?');


    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&quantity=' + encodeURIComponent(quantity);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'kanbantransfer.php', true);
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
    xhr.send(data);
}

function consume(partNo) {
    var quantity = document.getElementById('quantity').value;
	    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to transfer '+quantity+' of '+partNo+ ' from BF16 back to Front?');


    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&quantity=' + encodeURIComponent(quantity);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'kanbanconsume.php', true);
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
    xhr.send(data);
}

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
    xhr.open('POST', 'partialOrderReceived.php', true);
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
