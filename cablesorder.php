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
    <h4 class="white-txt center"><center> Welcome to Cable Re-order Console</strong><center></h4>
</section>
<?php echo"
    <form method=\"post\" id=\"myForm\" action=\"cablesorder1.php\">
        <br><br>
        <div class=\"signup-container\">
            <!-- Box container containing elements -->
            <div class=\"form-cube\">
                <h3 id=\"heading\">Scan/Enter the Cable PartNo</h3>
                <div class=\"input-field\" id=\"idFld\">
                    <input type=\"text\" id=\"barcodeInput\" name=\"barcode\" autofocus>
                </div>
            </div> ";
			?>
</div>
</form>
</body>
</html>
