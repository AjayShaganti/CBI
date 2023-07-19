<?php
include 'common.php';
?>
<style>
* {
  //box-sizing: border-box;
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
#fill-green{
 	
	margin-left:90%;
	//position: sticky;
        //z-index: 20;
	  //top: -0.0625rem;


	}
#scrollToTopButton {
  position: fixed;
  bottom: 20px;
  right: 20px;
  display: none;
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
	width:100%;
}
#tab3 {
	margin-left:-22%;
width:100%;
}
#tab4 {
	margin-left:-28%;
width:100%;
}
#tab5 {
	margin-left:-38%;
width:100%;
}
#lbt{
	padding-top: 0px;

}
.tab {
  float: left;
}

.tab-switch {
  display: none;
}
h4 {
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

table {
  width: 100%;
  border-collapse: collapse;
  padding-top: 0px;
  
}

thead th {
  position: sticky;
  top: -1.7rem;
  z-index:10;

}

    #lbt3 th {
        position: sticky;
          top: -1.7rem;
  z-index:5;
    }   

	#lbt4 th {
        position: sticky;
          top: -1.7rem;
  z-index:6;
    }  

	#lbt5 th {
        position: sticky;
          top: -1.7rem;
  z-index:7;
    }
    .highlighted {
        background-color:#708090;
    }
tbody td {
  overflow-x: hidden;
}

tbody td,
thead th {
  padding: 10px;
}
#top1 {
	  //position: sticky;
          //top:0;

	}
	
#up {
	
	margin-left: 100%;
position: sticky;
          bottom:0;

}
#down {
	margin-left: 100%;
	margin-bottom: 50%;
	z-index:1;
position: sticky;
          top:0;

	
}


  .tab-content {
    max-height: 650px; /* Adjust the height as needed */
   overflow-y: auto;
    //height: auto; /* Remove the fixed height to allow dynamic height */
    position: absolute;
    z-index: 1;
    //top: 2.75em;
    margin-right: 0%;
    padding: 1.618rem;
    background: #fff;
    color: #2c3e50;
    opacity: 0;
    transition: all 0.35s;
	overflow-x:hidden;
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
#filters {
	 margin-left:-0.5%;

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

echo "<center><div id=\"top1\"><h1>HYMOD & TOP HAT</h1><button id=\"fill-green\" onclick=\"printTable();\">Print</button>
<div id=\"filters\">
    <label for=\"category\">Category:</label>
    <select id=\"category\" onchange=\"applyFilters()\">
        <option value=\"\">All Categories</option>"; // Include an option for all categories

$categories = array();
mysqli_data_seek($result, 0); // Reset the result pointer

while ($record = mysqli_fetch_assoc($result)) {
    $category = $record['Category'];
    if (!in_array($category, $categories)) {
        $categories[] = $category;
        echo "<option value=\"$category\">$category</option> ";
    }
}
echo "</select>
      </div>";
 echo"Value:<strong><h3 id=\"totalval\"></h3></strong>";
        echo "</div><table id=\"lbt\" border='2'>
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
                </thead><div id=\"tdata\">
                <tbody><a id=\"top\"><a id=\"down\" href=\"#bottom\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";

        mysqli_data_seek($result, 0); // Reset the result pointer

        while ($record = mysqli_fetch_assoc($result)) {
 $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
            echo "<tr onclick=\"highlightRow(this)\">
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
                    <td>{$formattedTimestamp}</td>
                    <td>{$record['Category']}</td>
                    <td>{$record['Limit']}</td>
                  </tr>";
		if($record['Category']=='HYMOD'){$hymodtotal+=$record['TotalValue'];}
		if($record['Category']=='Top Hat'){$tophattotal+=$record['TotalValue'];}

        }


        echo "</tbody>
              </table>";

               echo "<a id=\"bottom\"></a>
<a id=\"up\" href=\"#top\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>";

        //echo "HM=".$hymodtotal."TH=".$tophattotal;
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
      <div id="tab2" class="tab-content">
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
              FROM stock
              ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED),
                       LENGTH(BinLocation),
                       BinLocation;";

    $result = mysqli_query($conn, $query);
    if ($result) {
		echo "<center><br><h1> KANBAN STOCK </h1><button id=\"fill-green\" onclick=\"printTable2();\">Print</button>";

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
                        <th>Availability<br>of 3rd Stock</th>
                    </tr>
                </thead>
                <tbody id=\"data\"><a id=\"top2\"><a id=\"down\" href=\"#bottom2\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";

        mysqli_data_seek($result, 0); // Reset the result pointer

        while ($record = mysqli_fetch_assoc($result)) {
 $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
 if($record['special']){
	 $tsa='Not Available';
 }
 else{
	 $tsa='Available';
 }
            echo "<tr onclick=\"highlightRow(this)\">
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
                    <td>{$formattedTimestamp}</td>
                    <td>{$record['Category']}</td>
                    <td>{$tsa}</td>
                  </tr>";
            $total += floatval($record['TotalValue']);
            $category = $record['Category'];

        }

        echo "<p>Total:</p><h3>$".round($total,2)."</h3><br></tbody>
              </table>";


      
        echo "<a id=\"bottom2\"></a>
<a id=\"up\" href=\"#top2\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>
";

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

if ($conn) {
    $query = "SELECT * FROM cables ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED)";
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        $record = mysqli_fetch_assoc($result);
        if ($record) {
            echo "<center><h1>CABLES</h1><br><h4>***This data is for reference and does not track or contain information about the available quantity of the cables***</h4><br><br><button id=\"fill-green\" onclick=\"printTable3();\">Print</button>
                <table id=\"lbt3\" border='2'>
                    <tr>
                        <th>Bin Location</th>
                        <th>Description</th>
                        <th>Part Number</th>
                        <th>Reel Length (Mts)</th>
                        <th>Max Stock (No's)</th>
                        <th>Min Stock (No's)</th>
                        <th>Reorder Quantity (No's)</th>
                        <th>Reorder Quantity (Mts)</th>
                        <th>Last Updated</th>
                    </tr><a id=\"top3\"><a id=\"down\" href=\"#bottom3\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";
            while ($record) {
 $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
                echo "</a><tr onclick=\"highlightRow(this)\">
                        <td>{$record['BinLocation']}</td>
                        <td>{$record['PartName']}</td>
                        <td>{$record['PartNo']}</td>
                        <td>{$record['Length']}</td>
                        <td>{$record['MaxStock']}</td>
                        <td>{$record['MinStock']}</td>
                        <td>{$record['ReorderQty1']}</td>
                        <td>{$record['ReorderQty2']}</td>
                        <td>{$formattedTimestamp}</td>
                    </tr>";

                $record = mysqli_fetch_assoc($result);
            }
            echo "</table>";
        }
    }
}

echo "<a id=\"bottom3\"></a>
<a id=\"up\" href=\"#top3\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>
</center>";

mysqli_close($conn);
?>



	</div>
    </div>
	   <div class="tab">
      <input type="radio" name="css-tabs" id="tab-4" class="tab-switch">
      <label for="tab-4" class="tab-label">CONSUMABLES</label>
      <div id="tab4" class="tab-content">
<?php
include 'connection.php';

if ($conn) {
    $query = "SELECT * FROM consumables ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED)";
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        $record = mysqli_fetch_assoc($result);
        if ($record) {
            echo "<center><h1>CONSUMABLES</h1><br><h4>***This data is for reference and does not track the available quantity of the items***</h4><br><br><button id=\"fill-green\" onclick=\"printTable4();\">Print</button>
                <table id=\"lbt4\" border='2'>
                    <tr>
                        <th>Bin Location</th>
                        <th>Description</th>
                        <th>Part Number</th>
						<th>Supplier</th>
                        <th>Max Stock</th>
                        <th>Min Stock</th>
                        <th>Reorder Quantity</th>
                        <th>Order Unit Spec</th>
                        <th>Pack Quantity</th>
						<th>Purchase Price</th>
                        <th>Price per Unit</th>
                        <th>Quantity</th>
                        <th>Total Value</th>						
                        <th>Last Updated</th>
                    </tr><a id=\"top4\"><a id=\"down\" href=\"#bottom4\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";
            while ($record) {
 $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
                echo "</a><tr onclick=\"highlightRow(this)\">
                        <td>{$record['BinLocation']}</td>
                        <td>{$record['PartName']}</td>
                        <td>{$record['PartNo']}</td>
						<td>{$record['Supplier']}</td>
                        <td>{$record['Max']}</td>
                        <td>{$record['Min']}</td>
                        <td>{$record['ReOrderQty']}</td>
                        <td>{$record['OrderUnitSpec']}</td>
                        <td>{$record['PackQty']}</td>
						<td>{$record['PurchasePrice']}</td>
                        <td>{$record['PricePerUnit']}</td>
                        <td>{$record['Quantity']}</td>
                        <td>{$record['TotalValue']}</td>
                        <td>{$formattedTimestamp}</td>
                    </tr>";

                $record = mysqli_fetch_assoc($result);
            }
            echo "</table>";
        }
    }
}

echo "<a id=\"bottom4\"></a>
<a id=\"up\" href=\"#top4\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>
</center>";

mysqli_close($conn);
?>
	</div>
    </div>
		   <div class="tab">
      <input type="radio" name="css-tabs" id="tab-5" class="tab-switch">
      <label for="tab-5" class="tab-label">LABELS</label>
      <div id="tab5" class="tab-content">
<?php
include 'connection.php';

if ($conn) {
    $query = "SELECT * FROM labels ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED)";
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        $record = mysqli_fetch_assoc($result);
        if ($record) {
            echo "<center><h1>LABELS</h1><br><h4>***This data is for reference and does not track the available quantity of the items***</h4><br><br><button id=\"fill-green\" onclick=\"printTable5();\">Print</button>
                <table id=\"lbt5\" border='2'>
                    <tr>
                        <th>Bin Location</th>
                        <th>Description</th>
                        <th>Part Number</th>
						<th>Supplier</th>
                        <th>Max Stock</th>
                        <th>Min Stock</th>
                        <th>Reorder Quantity</th>
                        <th>Order Unit Spec</th>
                        <th>Pack Quantity</th>
						<th>Purchase Price</th>
                        <th>Price per Unit</th>
                        <th>Quantity</th>
                        <th>Total Value</th>						
                        <th>Last Updated</th>
                    </tr><a id=\"top5\"><a id=\"down\" href=\"#bottom5\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";
            while ($record) {
 $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
                echo "</a><tr onclick=\"highlightRow(this)\">
                        <td>{$record['BinLocation']}</td>
                        <td>{$record['PartName']}</td>
                        <td>{$record['PartNo']}</td>
						<td>{$record['Supplier']}</td>
                        <td>{$record['Max']}</td>
                        <td>{$record['Min']}</td>
                        <td>{$record['ReOrderQty']}</td>
                        <td>{$record['OrderUnitSpec']}</td>
                        <td>{$record['PackQty']}</td>
						<td>{$record['PurchasePrice']}</td>
                        <td>{$record['PricePerUnit']}</td>
                        <td>{$record['Quantity']}</td>
                        <td>{$record['TotalValue']}</td>
                        <td>{$formattedTimestamp}</td>
                    </tr>";

                $record = mysqli_fetch_assoc($result);
            }
            echo "</table>";
        }
    }
}

echo "<a id=\"bottom5\"></a>
<a id=\"up\" href=\"#top5\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>
</center>";

mysqli_close($conn);
?>
	</div>
    </div>
	
	
  </div>
</div>

<script>

window.addEventListener('load', function() {
  // Hide the loading animation after the page has loaded
  var loadingAnimation = document.getElementById('loading-animation');
  loadingAnimation.style.display = 'none';
});



// Get all tab switches
var tabSwitches = document.querySelectorAll('.tab-switch');

// Retrieve the active tab from localStorage
var activeTab = localStorage.getItem('activeTab');

// Activate the stored active tab (if any)
if (activeTab) {
  var activeTabSwitch = document.getElementById(activeTab);
  if (activeTabSwitch) {
    activeTabSwitch.checked = true;
  }
}
    window.onload = function() {
        applyFilters(); // Apply the initial filter
    };

    function applyFilters() {
        var category = document.getElementById("category").value;
        var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
       // var total = document.getElementById("totalval");
var hm= Number('<?php echo round($hymodtotal,2); ?>');
var th=Number('<?php echo round($tophattotal,2); ?>');




var categoryTotal = 0;

if (category === 'HYMOD') {
    categoryTotal = hm;
} else if (category === 'Top Hat') {
    categoryTotal = th;
} else {
    categoryTotal = th + hm;
}
		document.getElementById("totalval").innerHTML = "$"+categoryTotal;


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
                           } else {
                row.style.display = "none";
            }
        }

    }

    function printTable() {
	var category = document.getElementById("category").value;
        var table = document.getElementById("lbt").cloneNode(true);
	var categoryTotal=0;
var hm= Number('<?php echo round($hymodtotal,2); ?>');
var th=Number('<?php echo round($tophattotal,2); ?>');


		
		if(category==='HYMOD')
		{
        	categoryTotal = hm;

		}
		else if(category==='Top Hat')
		{
			categoryTotal = th;

		}
		else {
			categoryTotal = Number(th)+Number(hm);

		}

        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt{ border-collapse: collapse; text-align:center;} th { background-color: #FF6978;}'  + '</style>');
        printWindow.document.write('</head><center><body>');
        printWindow.document.write('<h2>Available Stock</h2>');
        printWindow.document.write('<h3>Total: $' + categoryTotal + '</h3>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></center></html>');
        printWindow.document.close();
        printWindow.print();
    }

    function printTable2(total) {
        var table = document.getElementById("lbt2").cloneNode(true);
        var categoryTotal = Number('<?php echo round($total,2); ?>');
        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt2{ border-collapse: collapse; text-align:center;} th { background-color: #FF6978;}' + '</style>');
        printWindow.document.write('</head><center><body>');
        printWindow.document.write('<h2>Available Stock</h2>');
        printWindow.document.write('<h3>Total: $' + categoryTotal + '</h3>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write(categoryTotalsTable.outerHTML);
        printWindow.document.write('</body></center></html>');
        printWindow.document.close();
        printWindow.print();
    }
    function printTable3() {
        var table = document.getElementById("lbt3").cloneNode(true);

        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt3{ border-collapse: collapse; text-align:center;} th { background-color: #FF6978;}' + '</style>');
        printWindow.document.write('</head><center><body>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></center></html>');
        printWindow.document.close();
        printWindow.print();
    }
    function printTable4() {
        var table = document.getElementById("lbt4").cloneNode(true);

        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt4{ border-collapse: collapse; text-align:center;} th { background-color: #FF6978;}' + '</style>');
        printWindow.document.write('</head><center><body>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></center></html>');
        printWindow.document.close();
        printWindow.print();
    }

    function printTable5() {
        var table = document.getElementById("lbt5").cloneNode(true);

        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>' + getComputedStyle(table).cssText + '</style>');
        printWindow.document.write('<style> #lbt5{ border-collapse: collapse; text-align:center;} th { background-color: #FF6978;}' + '</style>');
        printWindow.document.write('</head><center><body>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></center></html>');
        printWindow.document.close();
        printWindow.print();
    }
function setupRowClickListeners() {
  var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
  for (var i = 0; i < rows.length; i++) {
    rows[i].addEventListener("click", function() {
      highlightRow(this); // Pass the clicked row as an argument
    });
  }
}

function highlightRow(row) {
  var currentlyHighlighted = document.getElementsByClassName("highlighted");
  if (currentlyHighlighted.length > 0) {
    currentlyHighlighted[0].classList.remove("highlighted");
  }
  row.classList.add("highlighted");
}</script>