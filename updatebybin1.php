<?php include 'common.php'; ?>
		<style>
		.update1{
		margin-left:10%;
			
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

            <!-- Section with applicants and courses containers --> 
	<form method="post" id="myForm" action="updatebybin2.php">
	<br><br>
	<div class="signup-container"> 
            <!-- Box container containing elements -->
            <div class="form-cube"> 
                <h1 id="heading">Scan/Enter the Bin Location</h1>
		<div class="input-field" id="idFld"> 
        <input type="text" id="barcodeInput" name="binlocation" autofocus>
                        </div>
</form>
        </div><?php include 'loading.php'; ?>
    </body>
</html>