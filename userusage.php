<?php
include 'connection.php';
session_start();
$fittername = $_SESSION['fittername'];
$password = $_SESSION['password'];

$msg = '';
$flag = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobid = $_POST['jobid'];
    $_SESSION['jobid'] = $jobid;
    $query = "SELECT jobid,BinLocation, partno, partname,fittername, category, SUM(CASE WHEN type = 'used' THEN quantity ELSE -quantity END) AS quantity, scandate
    FROM transactions
    where jobid='$jobid'
    GROUP BY jobid,partno, category;";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $flag = 1;
    } else {
        $msg = 'Query failed: ' . mysqli_error($conn);
        $flag = 0;
    }
}
?>

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
        h6 {
            color: red;
        }

        #header {
            /* background-image: url('path/to/your/image.jpg'); */
        }

        body {
            font-family: 'Jost', sans-serif;
            margin: 0;
            padding: 0;
        }

        section {
            background-color: #007bff;
            padding: 20px;
            text-align: center;
        }

        .white-txt {
            color: #fff;
            font-size: 18px;
            margin: 0;
        }

        .center {
            text-align: center;
        }

        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
           height: auto;
        }

        .form-cube {
            width: auto;
            max-width: 400px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
			margin-top:0%;
        }

        .form-cube h2,
        .form-cube h3 {
            color: red;
            text-align: center;
            margin: 0 0 20px 0;
        }

        .form-cube h5 {
            margin: 0;
        }

        .form-cube label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .form-cube select,
        .form-cube input[type="text"],
        .form-cube input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-cube .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .form-cube .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
            color: #888;
        }

        .form-cube button {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .ri-logout-circle-line {
            display: block;
            font-size: 24px;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
		.usage {
			width:auto;
			border-collapse:collapse;
			text-align: center;
	
		}
		td,tr {
			padding:10px;
		}

        @media only screen and (max-width: 600px) {
            .form-cube {
                width: 100%;
                max-width: 100%;
                padding: 10px;
            }

            .form-cube h2,
            .form-cube h3 {
                font-size: 20px;
            }

            .form-cube h5,
            .form-cube label,
            .form-cube select,
            .form-cube input[type="text"],
            .form-cube input[type="password"] {
                font-size: 14px;
            }

            .ri-logout-circle-line {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>


    <?php
	
    if ($flag) {
        echo "<br><center>Logged in as: <strong>".$fittername."</strong><br>
		Job ID: <strong>".$jobid."</strong><br>
        <form method=\"post\" id=\"myForm\" action=\"userused.php\">
            <div class=\"signup-container\">
                <!-- Box container containing elements -->
                <div class=\"form-cube\">
                    <table class=\"usage\" id=\"lbt\" border='2'>
                        <thead>
                            <tr>
                                <th>Bin Location</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Fitter Name</th>
                            </tr>
                        </thead>
                        <tbody>";

        while ($record = mysqli_fetch_assoc($result)) {
            echo "<tr class=\"$statusClass\">
                    <td>{$record['BinLocation']}</td>
                    <td>{$record['partname']}</td>
                    <td>{$record['quantity']}</td>
                    <td>{$record['fittername']}</td>
                </tr>";
        }

        echo "</tbody>
            </table></center><br><br><strong><a href=\"validateuser.php?password=" . urlencode($password) . "\" style=\"margin-left:46.5%;\"> &#x1F50D Scan New Item</a><strong>
            <br><br>
            <center><a id=\"\" class=\"ri-logout-circle-line\" href=\"userlogin.html\">Logout</a></center>
            </div>
        </div>
    </form>";
    } else {
        echo "Something went wrong. Please contact the administrator.";
    }
    ?>
</body>

</html>
