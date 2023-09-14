<?php
session_start();
include 'connection.php';


$msg = '';
$fittername = 'NA';
$flag = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userpin = $_GET['password'];
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

$_SESSION['fittername']=$fittername;
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
    <style>
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
            //margin-top: 20px;
			//margin-left: -50px;
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
		.disabled-input input[type="text"] {
    background-color: #f2f2f2;
    color: #888;
    cursor: not-allowed;
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
		  function shiftFocus() {
    // Get the current input element
    var currentInput = document.activeElement;

    // Check if the current input is a select dropdown
    if (currentInput.tagName === 'SELECT') {
      // Find the index of the current input in the form's elements array
      var currentIndex = Array.prototype.indexOf.call(document.forms[0].elements, currentInput);

      // Get the next input element
      var nextInput = document.forms[0].elements[currentIndex + 1];

      // Shift the focus to the next input element if it exists
      if (nextInput) {
        nextInput.focus();
      }
    }
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

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $columnValue = $row['jobid'];
            $options .= "<option value=\"$columnValue\">$columnValue</option>";
        }
    } else {
        $options = "<option value=\"\">No jobs assigned</option>";
    }

    $inputDisabled = ($options === "<option value=\"\">No jobs assigned</option>") ? 'disabled' : '';

    echo "
    <form method=\"post\" id=\"myForm\" action=\"userorder.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <div class=\"form-cube\">
                <h2>Hello " . $fittername . "</h2><br>
                <label for=\"fitter\">Sales Order:</label>
                <select id=\"jobid\" name=\"jobid\" $inputDisabled onchange=\"shiftFocus()\">" . $options . "</select>
                <h3 id=\"heading\">Scan/Enter the PartNo of the Item</h3>
                <div class=\"input-field " . ($inputDisabled ? 'disabled-input' : '') . "\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus $inputDisabled>
                </div>
				<button id=\"fill-blue\" class=\"used\" type=\"button\" onclick=\"submitForm('userusage.php')\">Check Usage</button><p> You can check the parts used by you under this sales order</p><br><br>
                <center><a id=\"\" class=\"ri-logout-circle-line\" href=\"userlogin.html\">Logout</a></center>
            </div>
        </div>
    </form> ";
} else {
    echo "
    <form method=\"get\" id=\"myForm\" action=\"validateuser.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements --> <br>             
            <div class=\"form-cube\"> <h2>" . $msg . "</h2> 
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
</body>
</html>
