<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!-- Viewport set to scale 1.0 -->       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CBi &#8211; Stock Management System</title>
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
		.update1{
		margin-left:10%;
			
		}
	.corpu-logo{
		margin-left:-20%;
		width:140%;
		height:200px;
		}

		</style>
		    <script type="text/javascript">
        function handleBarcodeInput(event) {
            var barcodeInput = document.getElementById("barcodeInput");
            barcodeInput.value = event.data; // Set the input value to the scanned barcode data
            document.getElementById("myForm").submit(); // Submit the form
        }

        function setFocusOnBarcodeInput() {
            var iframe = document.getElementById("myIframe");
            var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            var iframeInput = iframeDoc.getElementById("barcodeInput");
            iframeInput.focus(); // Set the focus to the input field inside the iframe
        }

        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener("message", handleBarcodeInput);
            document.addEventListener("keydown", function(event) {
                if (event.key === "F2") {
                    setFocusOnBarcodeInput();
                }
            });
            setFocusOnBarcodeInput();
        });
    </script>

    </head>
    <body>
        <!-- Main navigation bar --> 
        <header>
            <nav> 
                <div class="corpu-logo"><img src="images/cbi-logo.png" alt="CorpU logo"></div>
                <input type="checkbox" id="burger">
                <label for="burger" class="burgerbtn">
                    <i class="ri-menu-line"></i>
                </label>
                <ul>
                        <li><a href="Dashboard.php" id="select"><p>Dashboard</p></a></li>
                        <li><a href="Stock.php" id="select"><p>Stock</p></a></li>
					    <li><a href="updatebybin1.php" id="select"><p>Update by BinLocation</p></a></li>
					    <li><a href="updatebypartno1.php" id="select"><p>Update by PartNumber</p></a></li>
						<li><a href="viewjobs1.php" id="select"><p>View Jobs</p></a></li>
						<li><a href="managejobs1.php" id="select"><p>Manage Jobs</p></a></li>
						<li><a href="Viewusers1.php" id="select"><p>View Users</p></a></li>
						<li><a href="manageusers1.php" id="select"><p>Manage Users</p></a></li>
						<li><a href="updatestock1.php" id="select"><p>Update Stock Data </p></a></li>

		<li><a href="usage.php" id="select">
            <p>Usage</p>
          </a></li>	
<li><a href="reorder.php" id="select">
            <p>Re-Order</p>
          </a></li>

						
                </ul>
            </nav>
        </header>  
        <!-- General heading text section --> 
        <div id="full-container">
            <section id="page-top-2">
                <h4 class="sub-header white-txt">Stock Management System</h4>
                <br>
                <p class="white-txt center">Welcome</p>  
            </section>
            <!-- Section with applicants and courses containers --> 
	<form method="post" id="myForm" action="updatebypartno2.php">
	<br><br>
	<div class="signup-container"> 
            <!-- Box container containing elements -->
            <div class="form-cube"> 
                <h1 id="heading">Scan/Enter the Part No</h1>
		<div class="input-field" id="idFld"> 
        <input type="text" id="barcodeInput" name="partno" autofocus>
                        </div>
</form>
       <?php include 'loading.php'; ?> </div>
    </body>
</html>