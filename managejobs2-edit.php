<?php include 'common.php'; ?>		<style>
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
  left: 550px;

}
		</style>    <?php include 'loading.php'; ?>

    <?php

echo " 		<div class=\"form-cube1\">
			
		<form method=\"post\" action=\"managejobs3-edit.php\">
		<h2>Update a job</h2><br>
                    <div id=\"formfeild\" class=\"input-container\">
                    
						 <label for=\"Salesorder\">Sales Order:</label>
					<div class=\"input-field\" id=\"idFld\"> 

                            <input type=\"text\" name=\"salesorder\" id=\"salesorder\" maxlength=\"7\" required=\"required\" placeholder=\"Sales order\"> 
                        </div>

                        
                        <div id=\"float-right\">
                            <button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\" >Update</button> 
                        </div>
                </form>
</div>
                  
";	
// Close the database connection
mysqli_close($connection);

?>
</body>
</html>