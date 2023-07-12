<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBi | Stock Management System</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/game-fill.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d7376949ab.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .update1 {
            margin-left: 10%;
        }
        h4 {
            color: red;
        }	.corpu-logo{
		margin-left:-20%;
		width:140%;
		height:200px;
		}

        form {
            width: 60%;
            margin: 0 auto;
            position: relative;
            top: -130px;
            left: 0px;
        }
        .form-cube1 {
            background: #FFFCF9;
            width: 30%;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            position: relative;
            top: 250px;
            left: 550px;
        }
    </style>
</head>
<body>
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
            <li><a href="updatestock1.php" id="select"><p>Update Stock Data</p></a></li>
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
<div id="full-container">
    <section id="page-top-2">
        <h4 class="sub-header white-txt">Stock Management System</h4>
        <br>
        <p class="white-txt center">Welcome</p>
    </section>
    <?php
    include 'connection.php';
    session_start();
    $fittername = $_POST['fittername'];
    $password = $_POST['password'];
	$msg='';

// ...

try {
    if ($conn) {
        $updateQuery = "INSERT INTO `fitters`(`fittername`, `password`,`LastUpdated`) 
                        VALUES ('$fittername', '$password', '$currentDateTime')";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            $msg = "<h3>New Fitter " . $fittername . " added successfully</h3>";
        } else {
            throw new Exception("<h3>Failed to add fitter</h3>");
        }
    } else {
        throw new Exception("<h3>Connection failed: " . mysqli_connect_error(). "</h3>");
    }
} catch (Exception $e) {
    $msg = " <h4>Error: " . $e->getMessage(). "</h4><h5> If you are trying to update this fitter info <a href=\"http://3.231.173.240/CBi/manageusers2-edit.php\">Clickme</a></h4>";
}

// ...


	echo "<div class=\"form-cube1\">
        <form method=\"post\" action=\"manageusers3-add.php\">
		$msg<br>
		";
    ?>

 

<h2>Add another User</h2>
<div id="formfeild" class="input-container">
	<label for="Fitter Name">Fitter Name:</label>
	<div class="input-field" id="idFld">
		<input type="text" name="fittername" id="fittername" maxlength="15" required="required" placeholder="Fitter Name"> 
                        </div>
		<label for="Password">Password:</label>
		<div class="input-field" id="idFld">
			<input type="password" name="password" id="password" maxlength="4" required="required" placeholder="password"> 
                        </div>
			<div id="float-right">
				<button id="fill" class="signinBttn" type="submit" value="submit">Add</button>
			</div>
		</form>
	</div>

    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>
</div>
<?php include 'loading.php'; ?></body>
</html>
