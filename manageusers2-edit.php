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
			
		}	.corpu-logo{
		margin-left:-20%;
		width:140%;
		height:200px;
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
  left: 600px;

}
		</style>

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
          </a></li>		<li><a href="orderhistory.php" id="select">
            <p>Order History</p>
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
<?php
include 'connection.php';
$query = "SELECT distinct fittername FROM fitters order by fittername asc";
$result = mysqli_query($conn, $query);
$options2 = '';
while ($row = mysqli_fetch_assoc($result)) {
	$columnValue = $row['fittername'];
	$options2 .= "<option value=\"$columnValue\">$columnValue</option>";
}
				
echo " 		<div class=\"form-cube1\">
		<form method=\"post\" action=\"manageusers3-edit.php\">
				<h2> Update Password </h2><br>
                    <div id=\"formfeild\" class=\"input-container\">
                   
						<label for=\"status\">Fitter Name:</label>
						<select name=\"fittername\" required>"
       						 .$options2."
						</select>
						<label for=\"usage\">Kanban Stock:</label>
						<select name=\"kanban\" ><option value=\"\"></option><option value=\"1\">Allow</option><option value=\"0\">Deny</option>
						</select>
												</select>
						<label for=\"usage\">Cables:</label>
						<select name=\"cables\" ><option value=\"\"></option><option value=\"1\">Allow</option><option value=\"0\">Deny</option>
						</select>
												</select>
						<label for=\"usage\">HYMOD/TopHat:</label>
						<select name=\"hymod\"><option value=\"\"></option><option value=\"1\">Allow</option><option value=\"0\">Deny</option>
						</select>

					<label for=\"password\">New Password:</label>
					<div class=\"input-field\" id=\"idFld\"> 

                            <input type=\"password\" name=\"password\" id=\"password\" maxlength=\"4\" placeholder=\"password\"> 
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
<?php include 'loading.php'; ?>    </body>
