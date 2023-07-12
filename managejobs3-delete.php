<?php include 'common.php'; ?>
<style>
		.update1{
		margin-left:10%;
			
		}
		h4{
		color:red;}
		form {
			width:60%;
			margin:0 auto;
			  position: relative;
			  top: -130px;
  			left: 0px;


			}
.form-cube1{
    background: #FFFCF9;
	width: 30%;
    border-radius: 20px;
    text-align: center;  
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
  position: relative;
  top: 250px;
  left: 450px;

}
		</style>    <?php include 'loading.php'; ?>
<?php
include 'connection.php';
$salesorder = $_POST['salesorder'];


$query = "SELECT * FROM jobs WHERE jobid='$salesorder'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Sales order exists
    $deleteQuery = "DELETE FROM jobs WHERE jobid='$salesorder'";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        $msg = "Sales order $salesorder deleted successfully.";
    } else {
        $msg = "Failed to delete sales order $salesorder.";
        // You can also throw an exception or handle the error in a different way
        // throw new Exception("Failed to delete sales order.");
    }
} else {
    // Sales order does not exist
    $msg = "Sales order $salesorder does not exist.";
    // You can also throw an exception or handle the error in a different way
    // throw new Exception("Sales order does not exist.");
}



				
echo " 		<div class=\"form-cube1\">

		<form method=\"post\" action=\"managejobs3-delete.php\">
					<h4>" .$msg. "</h4><h2> Delete a job</h2>
					<br>
                    <div id=\"formfeild\" class=\"input-container\">
                    
						 <label for=\"Salesorder\">Sales Order:</label>
					<div class=\"input-field\" id=\"idFld\"> 

                            <input type=\"text\" name=\"salesorder\" id=\"salesorder\" maxlength=\"7\" required=\"required\" placeholder=\"Sales order\"> 
                        </div>

                        
                        <div id=\"float-right\">
                            <button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\" >Delete</button> 
                        </div>
                </form>
</div>
                  
";	
// Close the database connection
mysqli_close($connection);

?><?php include 'loading.php'; ?>
    </body>
</html>