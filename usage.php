<?php
include 'common.php';
include 'connection.php';

// Fetch sales order numbers from the database
$query = "SELECT DISTINCT jobid FROM transactions";
$result = mysqli_query($conn, $query);
$salesOrderOptions = '';
while ($row = mysqli_fetch_assoc($result)) {
  $salesOrderNo = $row['jobid'];
  $salesOrderOptions .= "<option value='$salesOrderNo'>$salesOrderNo</option>";
}

// Fetch part numbers from the database
$query = "SELECT DISTINCT partno FROM transactions";
$result = mysqli_query($conn, $query);
$partNoOptions = '';
while ($row = mysqli_fetch_assoc($result)) {
  $partNo = $row['partno'];
  $partNoOptions .= "<option value='$partNo'>$partNo</option>";
}

// Fetch categories from the database
$query = "SELECT DISTINCT category FROM transactions";
$result = mysqli_query($conn, $query);
$categoryOptions = '';
while ($row = mysqli_fetch_assoc($result)) {
  $category = $row['category'];
  $categoryOptions .= "<option value='$category'>$category</option>";
}


?>

<html>
<head>
  <title>Usage Table</title>
  <style>
    /* CSS styles for the table and filters */
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      text-align: center;
      border: 1px solid black;
      padding: 8px;
    }

    th {
      background-color: #ff8a8a;
      height: 50px;
    }

    #filters {
      margin-bottom: 10px;
    }

    #filters label,
    #filters select,
    #filters input {
      margin: 5px;
    }

    #print-button {
      margin-bottom: 10px;
    }
	table {
		width:73%;
		margin-left:25%;
		margin-bottom:2%;
		padding-top:5%;	
	}

	#mainh1 {
		margin-top:-30%;
		margin-left:58%;
	
	}

	#fill-green {	
		margin-left:58%;
	}
	thead th {
  position: sticky;
  top: -1.7rem;
  z-index:10;

}
#up {
	
	margin-left: 97%;
position: sticky;
          bottom:0;

}
#down {
	margin-left: 97%;
	margin-bottom: 50%;
	z-index:1;
position: sticky;
          top:0;
}
#total_cost{
	
	margin-left:25%;
	font-size:20px;
	font-weight:bold;
	}
	
.printscrn{
	
	margin-left:75%;
	margin-top:-2%;
}

    @media print {
      table tr {
        background-color: #ff8a8a !important;
      }
    }
  </style>
</head>
<body>
  <div id="filters">
    <label for="sales_order">Sales Order No:</label>
    <select id="sales_order">
      <option value="">All</option>
      <?php echo $salesOrderOptions; ?>
    </select>

    <label for="part_no">Part No:</label>
    <select id="part_no">
      <option value="">All</option>
      <?php echo $partNoOptions; ?>
    </select>

    <label for="category">Category:</label>
    <select id="category">
      <option value="">All</option>
      <?php echo $categoryOptions; ?>
    </select>


    <label for="date_range">Date Range:</label>
    <input type="date" id="start_date" placeholder="Start Date">
    <input type="date" id="end_date" placeholder="End Date">

    <button id="fill" onclick="applyFilters()">Apply</button>
    <button id="fill" onclick="clearFilters()">Reset</button>
	<button id="fill-blue" ><a href="detailedusage.php" style="text-decoration:none;">Detailed Usage</a></button>
  </div>

  <h1 id="mainh1">Usage</h1>
<div id="total_cost"></div><div class="printscrn"><button id="fill-green" onclick="printTable()" >Print Table</button></div>
  <table id="jobs_table">
    <tr>
      <th>Sales Order No</th>
      <th>Part No</th>
      <th>Description</th>
      <th>Category</th>
	  <th>Quantity Used</th>
	  <th>Cost</th>
      <th>Date</th>
    </tr>
    <?php
    $query = "SELECT *
FROM (
    SELECT jobid, partno, partname, category, SUM(CASE WHEN type = 'used' THEN quantity ELSE -quantity END) AS quantity,round(quantity*PricePerUnit,2) as Cost, scandate
    FROM transactions
    GROUP BY jobid, partno, category
    ORDER BY scandate DESC
) AS subquery
WHERE quantity > 0;";
    $result = mysqli_query($conn, $query);
		echo"<a id=\"top\"><a id=\"down\" href=\"#bottom\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";
    while ($row = mysqli_fetch_assoc($result)) {
      $salesOrderNo = $row['jobid'];
      $partNo = $row['partno'];
      $partName = $row['partname'];
      $category = $row['category'];
      $cost = $row['Cost'];
	 $formattedTimestamp = date_format(date_create($row['scandate']), 'd/m/y H:i');
	  $used=$row['quantity'];

      $rowColor = 'white';

      echo "<tr style='background-color: $rowColor;'>
              <td>$salesOrderNo</td>
              <td>$partNo</td>
              <td>$partName</td>
              <td>$category</td>
              <td>$used</td>
              <td>$cost</td>
              <td>$formattedTimestamp</td>
            </tr>";
    }
	include 'loading.php';
	echo "</table><a id=\"bottom\"></a>
<a id=\"up\" href=\"#top\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>";
    ?>
  

  <script>
      window.onload = function() {
        applyFilters(); // Apply the initial filter
    };
var totalCost = 0; // Declare the total cost as a global variable

function applyFilters() {
  var salesOrder = document.getElementById("sales_order").value;
  var partNo = document.getElementById("part_no").value;
  var category = document.getElementById("category").value;
  var startDate = document.getElementById("start_date").value;
  var endDate = document.getElementById("end_date").value;

  var table = document.getElementById("jobs_table");
  var rows = table.getElementsByTagName("tr");

  totalCost = 0; // Reset the total cost

  for (var i = 1; i < rows.length; i++) {
    var row = rows[i];
    var salesOrderCell = row.cells[0];
    var partNoCell = row.cells[1];
    var categoryCell = row.cells[3];
    var dateCell = row.cells[6];
    var costCell = row.cells[5]; // Assuming the cost column is the 6th column (index 5)

    var salesOrderMatch = salesOrder === "" || salesOrderCell.innerText === salesOrder;
    var partNoMatch = partNo === "" || partNoCell.innerText === partNo;
    var categoryMatch = category === "" || categoryCell.innerText === category;

    // Format the date for comparison
    var dateString = dateCell.innerText;
    var dateParts = dateString.split('/');
    var day = parseInt(dateParts[0], 10);
    var month = parseInt(dateParts[1], 10) - 1;
    var year = parseInt(dateParts[2], 10) + 2000;
    var rowDate = new Date(year, month, day);

    var year = rowDate.getFullYear();
    var month = String(rowDate.getMonth() + 1).padStart(2, '0');
    var day = String(rowDate.getDate()).padStart(2, '0');
    var formattedDate = `${year}-${month}-${day}`;

    var dateMatch = (startDate === "" || startDate <= formattedDate) &&
                    (endDate === "" || endDate >= formattedDate);

    if (salesOrderMatch && partNoMatch && categoryMatch && dateMatch) {
      row.style.display = "";
      var cost = parseFloat(costCell.innerText); // Convert the cost value to a floating-point number
      totalCost += cost; // Add the cost to the total cost
    } else {
      row.style.display = "none";
    }
  }

  // Display the total cost in a separate element
  var totalCostElement = document.getElementById("total_cost");
  totalCostElement.innerText = "Total Cost: $" + totalCost.toFixed(2); // Format the total cost with 2 decimal places
}

// Rest of the code...


function clearFilters() {
  document.getElementById("sales_order").value = "";
  document.getElementById("part_no").value = "";
  document.getElementById("category").value = "";
  document.getElementById("start_date").value = "";
  document.getElementById("end_date").value = "";

  applyFilters();
}

function printTable() {
  var table = document.getElementById("jobs_table");
  var rows = table.getElementsByTagName("tr");

  // Create a new window and open it
  var printWindow = window.open("", "_blank");

  // Write the opening tags and styles to the new window
  printWindow.document.open();
  printWindow.document.write("<html><head><title>Print</title>");
  printWindow.document.write("<style>");
  printWindow.document.write("table { border-collapse: collapse; width: 100%; }");
  printWindow.document.write("th, td { text-align: center; border: 1px solid black; padding: 8px; }");
  printWindow.document.write("th { background-color: #ff8a8a; height: 50px; }");
  printWindow.document.write("</style>");
  printWindow.document.write("</head><body>");

  // Add the total cost to the div element
  var totalCostDiv = document.createElement("div");
  totalCostDiv.id = "total_cost";
  totalCostDiv.textContent = "Total Cost: $" + totalCost.toFixed(2); // Display total cost with two decimal places
  printWindow.document.body.appendChild(totalCostDiv);

  // Create a new table and copy the content with styles from the original table
  var printTable = document.createElement("table");
  printTable.innerHTML = table.innerHTML;

  // Apply the row background colors to the new table
  for (var i = 1; i < rows.length; i++) {
    var row = printTable.rows[i];
    var rowColor = window.getComputedStyle(rows[i]).backgroundColor;
    row.style.backgroundColor = rowColor;
  }

  // Append the new table to the new window
  printWindow.document.body.appendChild(printTable);

  // Write the closing tags to the new window
  printWindow.document.write("</body></html>");
  printWindow.document.close();

  // Print the new window
  printWindow.print();
}


  </script>
</body>
</html>
