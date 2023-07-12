<?php   session_start();
include 'common.php'; ?>
<style>
		.update1{
		margin-left:10%;
			
		}
		h4{
		color:red;}
			
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
  <?php
include 'connection.php';
$binlocation = $_POST['binlocation'];

$_SESSION['bin'] = $binlocation;

if ($conn) {
    $Query = "select * from stock where BinLocation = '$binlocation'";
		$result=mysqli_query($conn,$Query);
 			$record=mysqli_fetch_assoc($result);

				if ($record) {
				
								echo "
												
								<form method=\"post\" id=\"myForm\">
									<br><br>
								<div class=\"signup-container\"> 
											<!-- Box container containing elements -->
											<div class=\"form-cube\"> 
											<h3><strong>BinLocation: </strong>" .$record['BinLocation']. 
											"</h3><br><h3><strong>PartName: </strong>" .$record['PartName'].
											"</h3><br><h3><strong>PartNo: </strong>" .$record['PartNo'].
											"</h3><br><h3><strong>AvailableQuantity: </strong>" .$record['Quantity'].
											
							"<br><br><a href=\"updatebybin2-add.php\" id=\"no-fill\" class=\"updateadd\">ADD(+)</a><br><br>

							<a href=\"updatebybin2-subtract.php\" id=\"no-fill\" class=\"updatesubtract\">SUBTRACT(-)</a>
							  
								</form>";	
								}
        
				else {
								echo " <div class=\"signup-container\"> 
									<div class=\"form-cube\"> <h1>
										Bin Location:" .$binlocation. "<br><br> is not available in database" . mysqli_error($conn). 
										"</h1></div></div>";
					 }
}



			else {
    echo "Connection failed: " . mysqli_connect_error();
}



?>
</body>
<?php include 'loading.php'; ?>
</html>