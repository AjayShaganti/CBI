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

// Fetch fitter names from the database
$query = "SELECT DISTINCT fittername FROM transactions";
$result = mysqli_query($conn, $query);
$fitterNameOptions = '';
while ($row = mysqli_fetch_assoc($result)) {
  $fitterName = $row['fittername'];
  $fitterNameOptions .= "<option value='$fitterName'>$fitterName</option>";
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

// Fetch types from the database
$query = "SELECT DISTINCT type FROM transactions";
$result = mysqli_query($conn, $query);
$typeOptions = '';
while ($row = mysqli_fetch_assoc($result)) {
  $type = $row['type'];
  $typeOptions .= "<option value='$type'>$type</option>";
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
	{
		overflow-x:hidden;
	}
    td {
      text-align: center;
      border: 1px solid black;
      padding: 8px;
	  overflow-y:auto;
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
		margin-top:-40%;
		margin-left:55%;
	
	}

	#fill-green {	
		margin-left:58%;
	}
thead th{
  position: sticky;
  top: 12.6rem;
  z-index:10;
  overflow-y:hidden;

}
#filters,#page-top-2{
  position: sticky;
  top: 0rem;
  z-index:10;
 // width:20%;
  //overflow-y:hidden;
}

	#up {
	
	margin-left: 98%;
position: sticky;
          bottom:0;

}
tbody{
    max-height: 650px; /* Adjust the height as needed */
   overflow-y: auto;
}
#down {
	margin-left: 98%;
	margin-bottom: 50%;
	z-index:1;
position: sticky;
          top:0;
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

    <label for="fitter_name">Fitter Name:</label>
    <select id="fitter_name">
      <option value="">All</option>
      <?php echo $fitterNameOptions; ?>
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

    <label for="type">Type:</label>
    <select id="type">
      <option value="">All</option>
      <?php echo $typeOptions; ?>
    </select>

    <label for="date_range">Date Range:</label>
    <input type="date" id="start_date" placeholder="Start Date">
    <input type="date" id="end_date" placeholder="End Date">

    <button id="fill" onclick="applyFilters()">Apply</button>
    <button id="fill" onclick="clearFilters()">Reset</button>
  </div>

 <br><br><h1 id="mainh1">Detailed Usage</h1><br><button id="fill-green" onclick="printTable()">Print Table</button><br>

  <table id="jobs_table"><thead>
    <tr>
      <th>Sales Order No</th>
      <th>Fitter Name</th>
      <th>Part No</th>
      <th>Category</th>
	  <th>Quantity Used</th>
      <th>Quantity Left<br>(In Stock)</th>
      <th>Used/Restored</th>
      <th>Date</th>
    </tr></thead>
    <?php
    $query = "SELECT * FROM transactions ORDER BY scandate desc";
    $result = mysqli_query($conn, $query);
	echo"<tbody><a id=\"top\"><a id=\"down\" href=\"#bottom\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";
    while ($row = mysqli_fetch_assoc($result)) {
      $salesOrderNo = $row['jobid'];
      $fitterName = $row['fittername'];
      $partNo = $row['partno'];
      $category = $row['category'];
      $type = $row['type'];
      //$scandate = $row['scandate'];
	   $formattedTimestamp = date('d/m/y H:i', strtotime($row['scandate']));
	  $used=$row['quantity'];
	  $left=$row['netquantity'];

      $rowColor = $type == 'used' ? 'orange' : 'green';

      echo "<tr style='background-color: $rowColor;'>
              <td>$salesOrderNo</td>
              <td>$fitterName</td>
              <td>$partNo</td>
              <td>$category</td>
              <td>$used</td>
              <td>$left</td>
			  <td>$type</td>
              <td>$formattedTimestamp</td>
            </tr>";
    }
	include 'loading.php';
		echo "</tbody></table><a id=\"bottom\"></a>
<a id=\"up\" href=\"#mainh1\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>";
    ?>


  <script>
    function applyFilters() {
      var salesOrder = document.getElementById("sales_order").value;
      var fitterName = document.getElementById("fitter_name").value;
      var partNo = document.getElementById("part_no").value;
      var category = document.getElementById("category").value;
      var type = document.getElementById("type").value;
      var startDate = document.getElementById("start_date").value;
      var endDate = document.getElementById("end_date").value;

      var table = document.getElementById("jobs_table");
      var rows = table.getElementsByTagName("tr");

      for (var i = 1; i < rows.length; i++) {
        var row = rows[i];
        var salesOrderCell = row.cells[0];
        var fitterNameCell = row.cells[1];
        var partNoCell = row.cells[2];
        var categoryCell = row.cells[3];
        var typeCell = row.cells[6];
        var dateCell = row.cells[7];

        var salesOrderMatch = salesOrder === "" || salesOrderCell.innerText === salesOrder;
        var fitterNameMatch = fitterName === "" || fitterNameCell.innerText === fitterName;
        var partNoMatch = partNo === "" || partNoCell.innerText === partNo;
        var categoryMatch = category === "" || categoryCell.innerText === category;
        var typeMatch = type === "" || typeCell.innerText === type;
        var dateMatch = (startDate === "" || startDate <= dateCell.innerText) &&
                        (endDate === "" || endDate >= dateCell.innerText);

        if (salesOrderMatch && fitterNameMatch && partNoMatch && categoryMatch && typeMatch && dateMatch) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      }
    }

    function clearFilters() {
      document.getElementById("sales_order").value = "";
      document.getElementById("fitter_name").value = "";
      document.getElementById("part_no").value = "";
      document.getElementById("category").value = "";
      document.getElementById("type").value = "";
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
