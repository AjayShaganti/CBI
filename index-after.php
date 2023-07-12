<?php include 'common.php'; ?><?php

include 'connection.php';
session_start();
$partno=$_SESSION['partno'];
$exists=0;
if ($conn) {
    $query = "SELECT PartName,Quantity FROM stock WHERE PartNo = '$partno'";
    $result = mysqli_query($conn, $query);
	        if ($result) {
            $record = mysqli_fetch_assoc($result);
            if ($record) {
				
               $quantity=$record['Quantity'];
			   $partname=$record['PartName'];
			   $exists=1;
            }
        }
		
}
?>
			<?php if ($exists){echo "<h3><center> No's:" .$quantity. " " .$partname. " Left</center></h3>";} else echo "<h2><center>PartNo is not available in Database</center></h2>";?>
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
        </div>
    </body>
</html>