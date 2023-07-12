<?php
include 'common.php';
?>
<style>
* {
  box-sizing: border-box;
}

.wrapper {
  padding-top: 10.5%;
  width: 100%;
  margin: 0 auto;
}

.tabs {
  position: relative;
  margin: 2rem 0;
  background: #FF5E60;
  height: auto;
}

.tabs::before,
.tabs::after {
  content: "";
  display: table;
}

.tabs::after {
  clear: both;
}
#tab2 {
	margin-left:-12%;
}
#tab3 {
	margin-left:-15%;
}

.tab {
  float: left;
}

.tab-switch {
  display: none;
}
h5 {
color:red;
}

.tab-label {
  position: relative;
  display: block;
  line-height: 2.75em;
  height: 3em;
  padding: 0 1.618em;
  background: #FF5E60;
  border-right: 0.125rem solid #fff;
  color: #fff;
  cursor: pointer;
  top: 0;
  transition: all 0.5s;
}

.tab-label:hover {
  top: -0.25rem;
  transition: top 0.25s;
}

.tab-content {
  height: 12rem;
  position: absolute;
  z-index: 1;
  top: 2.75em;
  margin-right: 0%;
  padding: 1.618rem;
  background: #fff;
  color: #2c3e50;
  opacity: 0;
  transition: all 0.35s;
}

.tab-switch:checked + .tab-label {
  background: #fff;
  color: #2c3e50;
  border-bottom: 0;
  border-right: 0.125rem solid #fff;
  transition: all 0.35s;
  z-index: 1;
  top: -0.0625rem;
}

.tab-switch:checked + label + .tab-content {
  z-index: 2;
  opacity: 1;
  transition: all 0.35s;
}

#categoryTotals {
  display: none;
  margin-top: 20px;
  border-collapse: collapse;
}

#categoryTotals th,
#categoryTotals td {
  padding: 5px;
  border: 1px solid black;
}

/* Responsive Styling */
@media screen and (max-width: 768px) {
  .wrapper {
    padding-top: 20%;
  }

  .tabs {
    height: auto;
    padding-bottom: 2em;
  }

  .tab-label {
    line-height: 2em;
    height: 2.5em;
    padding: 0 1em;
  }

  .tab-content {
    position: static;
    height: auto;
    margin-right: 0;
    padding: 1em;
  }
}
</style>
<div class="wrapper">
  <div class="tabs">
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-1" checked class="tab-switch">
      <label for="tab-1" class="tab-label">HYMOD & TOP HAT</label>
      <div class="tab-content">
<?php
include 'connection.php';

$total = 0;
$categoryTotals = array();

if ($conn) {
    $query = "SELECT *,
              CONCAT(
                BinLocation,
                CASE
                  WHEN RIGHT(BinLocation, 2) REGEXP '^[0-9]+0$' THEN ''
                     ELSE ''
                END
              ) AS ModifiedBinLocation
              FROM tophathymod
              ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED),
                       LENGTH(BinLocation),
                       BinLocation;";

    $result = mysqli_query($conn, $query);
    if ($result) {

echo "<center><h1>Hymod & Top-Hat</h1><br><div id=\"filters\">
    <label for=\"category\">Category:</label>
    <select id=\"category\" onchange=\"applyFilters()\">
        <option value=\"\">All Categories</option>"; // Include an option for all categories

$categories = array();
mysqli_data_seek($result, 0); // Reset the result pointer

while ($record = mysqli_fetch_assoc($result)) {
    $category = $record['Category'];
    if (!in_array($category, $categories)) {
        $categories[] = $category;
        echo "<option value=\"$category\">$category</option>";
    }
}

echo "</select>
      </div>";
        echo "<table id=\"lbt\" border='2'>
                <thead>
                    <tr>
                        <th>Bin Location</th>
                        <th>Description</th>
                        <th>Part Number</th>
                        <th>Supplier</th>
                        <th>Max Stock Level</th>
                        <th>Min Stock Level</th>
                        <th>Re-Order Quantity</th>
                        <th>Purchase Price</th>
                        <th>Price For No.of Units</th>
                        <th>Price Per Unit</th>
                        <th>Total Stock</th>
                        <th>Total Value</th>
                        <th>Last Updated</th>
                        <th>Category</th>
                        <th>Usage Limit</th>
                    </tr>
                </thead>
                <tbody>";

        mysqli_data_seek($result, 0); // Reset the result pointer

        while ($record = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$record['ModifiedBinLocation']}</td>
                    <td>{$record['PartName']}</td>
                    <td>{$record['PartNo']}</td>
                    <td>{$record['Supplier']}</td>
                    <td>{$record['Max']}</td>
                    <td>{$record['Min']}</td>
                    <td>{$record['ReOrderQty']}</td>
                    <td>{$record['PurchasePrice']}</td>
                    <td>{$record['Units']}</td>
                    <td>{$record['PricePerUnit']}</td>
                    <td>{$record['Quantity']}</td>
                    <td>{$record['TotalValue']}</td>
                    <td>{$record['LastUpdated']}</td>
                    <td>{$record['Category']}</td>
                    <td>{$record['Limit']}</td>
                  </tr>";
            $total += floatval($record['TotalValue']);
            $category = $record['Category'];

            if (isset($categoryTotals[$category])) {
                $categoryTotals[$category] += floatval($record['TotalValue']);
            } else {
                $categoryTotals[$category] = floatval($record['TotalValue']);
            }
        }

        echo "</tbody>
              </table>";

        echo "<table id=\"categoryTotals\">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($categoryTotals as $category => $categoryTotal) {
            echo "<tr>
                    <td>{$category}</td>
                    <td>{$categoryTotal}</td>
                  </tr>";
        }

        echo "</tbody>
              </table>";

        echo "<h3 id=\"total\">Total: <span id=\"categoryTotal\"></span></h3>"; // Placeholder for displaying total dynamically

        echo "<br><button id=\"fill-green\" onclick=\"printTable();\">Print</button>";

        echo "";
    } else {
        echo "Error executing the query: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Error connecting to the database.";
}
?>
</div>
    </div></center>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-2" class="tab-switch">
      <label for="tab-2" class="tab-label">KANBAN STOCK</label>
      <div id="tab2" class="tab-content"><?php
include 'connection.php';

$total = 0;
$categoryTotals = array();

if ($conn) {
    $query = "SELECT *,
              CONCAT(
                BinLocation,
                CASE
                  WHEN RIGHT(BinLocation, 2) REGEXP '^[0-9]+0$' THEN ''
                     ELSE ''
                END
              ) AS ModifiedBinLocation
              FROM stock
              ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED),
                       LENGTH(BinLocation),
                       BinLocation;";

    $result = mysqli_query($conn, $query);
    if ($result) {
		echo "<center><br><h1> Kanban Stock </h1><br>";

        echo "<table class=\"table2\" id=\"lbt2\" border='2'>
                <thead>
                    <tr>
                        <th>Bin Location</th>
                        <th>Description</th>
                        <th>Part Number</th>
                        <th>Refill Quantity</th>
                        <th>Max Stock Level</th>
                        <th>Min Stock Level</th>
                        <th>Re-Order Quantity</th>
                        <th>Purchase Price</th>
                        <th>Price For No.of Units</th>
                        <th>Price Per Unit</th>
                        <th>BF16 Back Qty</th>
                        <th>3rd Stock Qty</th>
                        <th>Total Stock</th>
                        <th>Total Value</th>
                        <th>Last Updated</th>
                        <th>Category</th>
                        <th>Usage Limit</th>
                    </tr>
                </thead>
                <tbody>";

        mysqli_data_seek($result, 0); // Reset the result pointer

        while ($record = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$record['ModifiedBinLocation']}</td>
                    <td>{$record['PartName']}</td>
                    <td>{$record['PartNo']}</td>
                    <td>{$record['RefillQty']}</td>
                    <td>{$record['Max']}</td>
                    <td>{$record['Min']}</td>
                    <td>{$record['ReOrderQty']}</td>
                    <td>{$record['PurchasePrice']}</td>
                    <td>{$record['Units']}</td>
                    <td>{$record['PricePerUnit']}</td>
                    <td>{$record['BF16Back']}</td>
                    <td>{$record['3rdStock']}</td>
                    <td>{$record['Quantity']}</td>
                    <td>{$record['TotalValue']}</td>
                    <td>{$record['LastUpdated']}</td>
                    <td>{$record['Category']}</td>
                    <td>{$record['Limit']}</td>
                  </tr>";
            $total += floatval($record['TotalValue']);
            $category = $record['Category'];

        }

        echo "</tbody>
              </table>";


        echo "<h3 id=\"total\">Total:".$total." <span class=\"categoryTotal2\" id=\"categoryTotal2\"></span></h3>"; // Placeholder for displaying total dynamically

        echo "<br><button id=\"fill-green\" onclick=\"printTable2($total);\">Print</button>";

        echo "</center>";
    } else {
        echo "Error executing the query: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Error connecting to the database.";
}
?>
</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-3" class="tab-switch">
      <label for="tab-3" class="tab-label">CABLES</label>
      <div id="tab3" class="tab-content">
<?php
include 'connection.php';

if($conn) 
	{
	$query="select * from cables order by CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED)";
	
	$result=mysqli_query($conn,$query);
		if($result)
		{
			$record=mysqli_fetch_assoc($result);
			{if($record)
				{
				echo"<center><h1>Cables</h1><br><h5>***This data is for reference and does not track or contains information about the available quantity of the cables***</h5><br>
				<table id=\"lbt3\" border='2'><tr><th>Bin Location</th><th>Description</th><th>Part Number</th><th>Reel Length(Mts)</th><th>Max Stock (No's)</th><th>Min Stock (No's)</th><th>Reorder Quantity(No's)</th><th>Reorder Quantity(Mts)</th><th>Last Updated</tr>";
				while($record)
					{echo" <tr>
						<td>{$record['BinLocation']}</td>
						<td>{$record['PartName']}</td>
						<td>{$record['PartNo']}</td>
						<td>{$record['Length']}</td>
						<td>{$record['MaxStock']}</td>
						<td>{$record['MinStock']}</td>
						<td>{$record['ReorderQty1']}</td>
						<td>{$record['ReorderQty2']}</td>
						<td>{$record['LastUpdated']}</td>
					 </tr>";

					 $record=mysqli_fetch_assoc($result);
					 }
					 echo"</table><center>";
				}
			}
		}
	}
echo "<br><button id=\"fill-green\" onclick=\"printTable3();\">Print</button>";


mysqli_close($conn);

?>



	</div>
    </div>
  </div>
</div>
<script>
    window.onload = function() {
        applyFilters(); // Apply the initial filter
    };

    function applyFilters() {
        var category = document.getElementById("category").value;
        var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        var total = 0; // Variable to calculate the category total

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var categoryCell = row.getElementsByTagName("td")[13];
            var categoryText = categoryCell.textContent || categoryCell.innerText;
            var showRow = true;

            if (category && categoryText !== category) {
                showRow = false;
            }

            if (showRow) {
                row.style.display = "";
                total += parseFloat(row.getElementsByTagName("td")[12].textContent || 0);
            } else {
                row.style.display = "none";
            }
        }
	document.getElementById("categoryTotal").textContent = total.toFixed(2); // Update the category total
        document.getElementById("total").style.display = ""; // Display the total section
        document.getElementById("categoryTotals").style.display = "none"; // Hide the categoryTotals table

    }

    function printTable() {
        var table = document.getElementById("lbt").cloneNode(true);
        var categoryTotalsTable = document.getElementById("categoryTotals").cloneNode(true);
        var categoryTotal = document.getElementById("categoryTotal").textContent;

        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt{ border-collapse: collapse;}' + getComputedStyle(categoryTotalsTable).cssText + '</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Available Stock</h2>');
        printWindow.document.write('<h3>Total: ' + categoryTotal + '</h3>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write(categoryTotalsTable.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    function printTable2(total) {
        var table = document.getElementById("lbt2").cloneNode(true);
        var categoryTotal = total;
        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt2{ border-collapse: collapse;}' + '</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Available Stock</h2>');
        printWindow.document.write('<h3>Total: ' + categoryTotal + '</h3>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write(categoryTotalsTable.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    function printTable3() {
        var table = document.getElementById("lbt3").cloneNode(true);
a
        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt3{ border-collapse: collapse;}' + '</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }


</script>