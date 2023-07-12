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
        }	
		img {
			margin-left: 0%;
			width: 140%;
			height: 200px;
		}
		.corpu-logo ul {
			margin-left: 15% !important;
			width:70%;
			margin-top:10%;
		}

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            text-align: center;
            /* border: 1px solid black; */
        }

        thead {
            background-color: #FFA6A7;
            color: #ffffff;
        }

        th {
            text-align: center;
            height: 50px;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody tr:nth-child(even) {
            background: #FFC6C7;
        }

        a {
            text-decoration: none;
            color: red;
        }
    </style>
    <?php include 'loading.php'; ?>
</head>
<body>
<header>
    <nav>
        <div class="corpu-logo">
            <img src="images/cbi-logo.png" alt="CorpU logo"/>
            <ul>
                <li>
                    <a href="Dashboard.php" id="select">
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="Stock.php" id="select">
                        <p>Stock</p>
                    </a>
                </li>
                <li>
                    <a href="updatebybin1.php" id="select">
                        <p>Update by BinLocation</p>
                    </a>
                </li>
                <li>
                    <a href="updatebypartno1.php" id="select">
                        <p>Update by PartNumber</p>
                    </a>
                </li>
                <li>
                    <a href="viewjobs1.php" id="select">
                        <p>View Jobs</p>
                    </a>
                </li>
                <li>
                    <a href="managejobs1.php" id="select">
                        <p>Manage Jobs</p>
                    </a>
                </li>
                <li>
                    <a href="Viewusers1.php" id="select">
                        <p>View Users</p>
                    </a>
                </li>
                <li>
                    <a href="manageusers1.php" id="select">
                        <p>Manage Users</p>
                    </a>
                </li>
                <li>
                    <a href="updatestock1.php" id="select">
                        <p>Update Stock Data</p>
                    </a>
                </li>
                <li>
                    <a href="usage.php" id="select">
                        <p>Usage</p>
                    </a>
                </li>
                <li>
                    <a href="reorder.php" id="select">
                        <p>Re-Order</p>
                    </a>
                </li>
                <li>
                    <a href="orderhistory.php" id="select">
                        <p>Order History</p>
                    </a>
                </li>
            </ul>
        </div>
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
    $msg = '';
    $fittername = $_GET["fittername"];

    if (isset($_GET["fittername"])) {
        $fittername = $_GET["fittername"];
        // Delete the record from the database
        $sql = "DELETE FROM fitters WHERE fittername = '$fittername'";
        if ($conn->query($sql) === TRUE) {
            $msg .= "Record deleted successfully.";
        } else {
            $msg .= "Error deleting record: " . $conn->error;
        }
    } else {

    }

    $sql = "SELECT fittername FROM fitters ORDER BY fittername ASC";
    $result = $conn->query($sql);
    ?>
    <div class="signup-container">
        <div class="form-cube">
            <h1>Delete Records</h1>
            <?php echo "<h4>" . $msg . "</h4><br>"; ?>
            <table id="lbt" border='2'>
                <tr>
                    <th>Fitter name</th>
                    <th>Action</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["fittername"] . "</td>";
                        echo "<td><a id='no-fill' href='manageusers3-delete.php?fittername=" . $row["fittername"] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No records found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
