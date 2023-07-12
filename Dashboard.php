<?php include 'common.php'; ?>
            <!-- Section with applicants and courses containers --> 
	<form method="post" id="myForm" action="processorder.php">
	<br><br>
	<div class="signup-container"> 
            <!-- Box container containing elements -->
            <div class="form-cube"> 
                <h1 id="heading">Scan/Enter the Part Number</h1>
		<div class="input-field" id="idFld"> 
        <input type="text" id="barcodeInput" name="barcode" autofocus>
                        </div>
</form>
<?php include 'loading.php'; ?>
        </div>
    </body>
</html>