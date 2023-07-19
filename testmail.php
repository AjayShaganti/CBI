<?php
include 'common.php';
echo " <script src=\"https://unpkg.com/xlsx/dist/xlsx.full.min.js\"></script>";
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
.reorder1 {
	//margin-left:-12%;
	width:10%;
}
.reorder2 {
    width: 96%;
    padding: 10%;
    margin-left: 1%;
    margin-right: 30%;
}
#tab2 {
	margin-left:-12%;
	width:100%;
}
#tab3 {
	margin-left:-18%;
width:95%;
}
#tab4 {
	margin-left:-28%;
width:100%;
}
#tab5 {
	margin-left:-22%;
width:100%;
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

table {
  width: 10%;
  border-collapse: collapse;
  padding-top: 0px;
  
}

thead th {
  position: sticky;
  top: -1.7rem;
  z-index:10;

}

    #lbt2,#lbt3,#lbt4 th {
        position: sticky;
          top: -1.7rem;
  z-index:10;


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
	
	margin-left: 99%;
	margin-top: 50%;
position: sticky;
          bottom:0;

}
#down {
	margin-left: 99%;
	margin-bottom: 50%;
	z-index:15;
position: sticky;
          top:0;

	
}


  .tab-content {
    max-height: 650px; /* Adjust the height as needed */
   overflow-y: auto;
    position: absolute;
    z-index: 1;
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
  /* Add CSS for different status colors */
  .status-ToBeOrdered {
    background-color: #fff;
  }

  .status-Sent {
    background-color: #ffff00;
  }

  .status-Ordered {
    background-color: #04b0f0;
  }

  .status-PartiallyReceived {
    background-color: #00ff80;
  }


</style>
<!-- Add the loading animation element -->


<div class="wrapper">
  <div class="tabs">
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-1" checked class="tab-switch">
      <label for="tab-1" class="tab-label">HYMOD & TOP HAT</label>
      <div class="tab-content" id="tab-1">
<?php
include 'connection.php';

$msg = '';
$sql = "
  INSERT INTO reorderhmtp
  SELECT BinLocation, PartName, PartNo, Category, Quantity AS Available, (Max-Min) AS Required, 0 AS Received, (Max-Min) AS BackOrder, 'To be Ordered' AS Status, 'NA' AS PurchaseOrder, NOW() AS LastUpdated, 'NA' AS Comments FROM tophathymod
  WHERE PartNo NOT IN (
    SELECT PartNo FROM reorderhmtp
  ) AND Quantity <= Min
";

try {
  $result = $conn->query($sql);
  if ($result === false) {
    throw new Exception('Error: ' . $conn->error);
  } else {
    $rowCount = $conn->affected_rows;
    $msg = $rowCount . ' new item/s added.';
  }
} catch (Exception $e) {
  $msg = $e->getMessage();
}


if ($conn) {
  $query = "SELECT *,
    CONCAT(
      BinLocation,
      CASE
        WHEN RIGHT(BinLocation, 2) REGEXP '^[0-9]+0$' THEN ''
        ELSE ''
      END
    ) AS ModifiedBinLocation
    FROM reorderhmtp
    ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED),
    LENGTH(BinLocation),
    BinLocation";

  $reorderResult = mysqli_query($conn, $query);

  if ($reorderResult) {
    echo "
    <center>
    <h2 id=\"lbh\">HYMOD & Top-Hat</h2>
    <br>
    <h3>" . $msg . "<br><br></h3>";

echo "<div id=\"filters\">
<label for=\"category\">Category:</label>
<select id=\"category\" onchange=\"applyFilters1()\">
<option value=\"Top Hat\">Top Hat</option></center>"; // Only include the desired category option(s) here

$categories = array();
mysqli_data_seek($reorderResult, 0); // Reset the result pointer

while ($record = mysqli_fetch_assoc($reorderResult)) {
  $category = $record['Category'];
  if (!in_array($category, $categories)) {
    $categories[] = $category;
    if ($category !== "Top Hat") { // Exclude the "TopHats" category from the dropdown
      echo "<option value=\"$category\">$category</option>";
    }
  }
}

echo "</select>
</div>";

echo "<button id=\"fill-green\" onclick=\"printTable1();\">Print</button><div id=\"filters\">
<label for=\"status\">Status:</label>
<select id=\"status\" onchange=\"applyFilters1()\">
<option value=\"\">All</option>
"; // Include the default "All" option here

$statuses = array();
mysqli_data_seek($reorderResult, 0); // Reset the result pointer

while ($record = mysqli_fetch_assoc($reorderResult)) {
  $status = $record['Status'];
  if (!in_array($status, $statuses)) {
    $statuses[] = $status;
    echo "<option value=\"$status\">$status</option>";
  }
}

echo "</select>
</div><br><button id=\"fill-blue\" onclick=\"exportToExcel1()\">Export to Excel</button>";

    echo "<table class=\"reorder1\" id=\"lbt1\" border='2'>
<thead>
<tr>
    <th>Bin Location</th>
    <th>Description</th>
    <th>Part Number</th>
    <th>Category</th>
    <th>Available Quantity</th>
    <th>Required Quantity</th>
    <th>Received Quantity</th>
    <th>Back Order</th>
    <th>Fully Received</th>
    <th> Current Status</th>
	<th>Update Status</th>
    <th>Purchase Order</th>
    <th>Partially Received</th>
    <th>Last Updated</th>
    <th>Comments</th>
</tr>
</thead>
<tbody><a id=\"top1\"><a id=\"down\" href=\"#bottom1\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";

mysqli_data_seek($reorderResult, 0); // Reset the result pointer
$PNo1 = [];
$Pname1 = [];
$BackOrder1 = [];
$i1 = 0;
while ($record = mysqli_fetch_assoc($reorderResult)) {
	 $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
  $PNo1[$i1] = $record['PartNo'];
  $Pname1[$i1] = $record['PartName'];
  $BackOrder1[$i1]=$record['BackOrder'];
  
  $statusClass = 'status-' . str_replace(' ', '', $record['Status']);
  echo "<tr class=\"$statusClass\">
    <td>{$record['ModifiedBinLocation']}</td>
    <td>{$record['PartName']}</td>
    <td>{$record['PartNo']}</td>
    <td>{$record['Category']}</td>
    <td>{$record['Available']}</td>
    <td>
        <form id='updateForm_$i1' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='requiredQty1_$i1' value='{$record['Required']}'/>
            <button id='no-fill' type='button' onclick=\"updateRequiredQty1('$PNo1[$i1]', $i1)\">Update</button>
        </form>
    </td>
    <td>{$record['Received']}</td>
    <td>{$record['BackOrder']}</td>
    <td>
        <button id=\"no-fill\" onclick=\"fullOrderReceived1('{$PNo1[$i1]}',$BackOrder1[$i1])\">Fully Received</button>
    </td><td>{$record['Status']}</td>
    <td>
        <form id='updateStatusForm_$i1' onkeydown=\"disableEnterKey(event)\">
            <select id='status1_$i1'>
                <option value='To be Ordered'>To be Ordered</option>
                <option value='Sent'>Sent</option>

            </select>
            <button id='no-fill' type='button' onclick=\"updateStatus1('$PNo1[$i1]', $i1)\">Update</button>
        </form>
    </td>
    <td>
        <form id='purchaseOrderForm_$i1' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='purchaseOrder1_$i1' value='{$record['PurchaseOrder']}'/>
            <button id='no-fill' type='button' onclick=\"updatePurchaseOrder1('$PNo1[$i1]', $i1)\">Update</button>
        </form>
    </td>
    <td>
        <button id=\"no-fill\" onclick=\"promptUser1('{$PNo1[$i1]}',$BackOrder1[$i1])\">Partially Received</button>
    </td>
    <td>{$formattedTimestamp}</td>
    <td>
        <form id='commentsForm_$i1' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='comments1_$i1' value='{$record['Comments']}'/>
            <button id='no-fill' type='button' onclick=\"updateComments1('$PNo1[$i1]', $i1)\">Update</button>
        </form>
    </td>
  </tr>";
  $i1++;
}

echo "</tbody>
</table>";
               echo "<a id=\"bottom1\"></a>
<a id=\"up\" href=\"#top1\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>";

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
      <input type="radio" name="css-tabs" id="tab-2" class="tab-switch">
      <label for="tab-2" class="tab-label">KANBAN STOCK</label>
      <div class="tab-content" id="tab2" >
<?php
include 'connection.php';

$msg = '';
$sql = "
  INSERT INTO reorder
  SELECT BinLocation, PartName, PartNo, Category, Quantity AS Available, (Max-Min) AS Required, 0 AS Received, (Max-Min) AS BackOrder, 'To be Ordered' AS Status, 'NA' AS PurchaseOrder, NOW() AS LastUpdated, 'NA' AS Comments FROM stock
  WHERE PartNo NOT IN (
    SELECT PartNo FROM reorder
  ) AND Quantity <= Min
";

try {
  $result = $conn->query($sql);
  if ($result === false) {
    throw new Exception('Error: ' . $conn->error);
  } else {
    $rowCount = $conn->affected_rows;
    $msg = $rowCount . ' new items added.';
  }
} catch (Exception $e) {
  $msg = $e->getMessage();
}

if ($conn) {
  $query = "SELECT *,
    CONCAT(
      BinLocation,
      CASE
        WHEN RIGHT(BinLocation, 2) REGEXP '^[0-9]+0$' THEN ''
        ELSE ''
      END
    ) AS ModifiedBinLocation
    FROM reorder
    ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED),
    LENGTH(BinLocation),
    BinLocation";

  $reorderResult = mysqli_query($conn, $query);

  if ($reorderResult) {
    echo "
    <center>
    <h2 id=\"lbh\">Re-Order</h2>
    <br>
    <h3>" . $msg . "<br><br>";



echo "<div id=\"filters\">
<label for=\"status\">Status:</label>
<select id=\"status\" onchange=\"applyFilters()\">
<option value=\"\">All</option>"; // Include the default "All" option here

$statuses = array();
mysqli_data_seek($reorderResult, 0); // Reset the result pointer

while ($record = mysqli_fetch_assoc($reorderResult)) {
  $status = $record['Status'];
  $formattedTimestamp = date('d/m/y H:i', strtotime($record['LastUpdated']));
  if (!in_array($status, $statuses)) {
    $statuses[] = $status;
    echo "<option value=\"$status\">$status</option>";
  }
}

echo "</select>
</div><button id=\"fill-green\" onclick=\"printTable();\">Print</button>";
echo "</center>";
    echo "<table class=\"reorder2\" id=\"lbt2\" border='2'>
<thead>
<tr>
    <th>Bin Location</th>
    <th>Description</th>
    <th>Part Number</th>
    <th>Category</th>
    <th>Available Quantity</th>
    <th>Required Quantity</th>
    <th>Received Quantity</th>
    <th>Back Order</th>
    <th>Fully Received</th>
    <th>Current Status</th>
	<th>Update Status</th>
    <th>Purchase Order</th>
    <th>Partially Received</th>
    <th>Last Updated</th>
    <th>Comments</th>
</tr>
</thead>
<tbody><a id=\"top2\"><a id=\"down\" href=\"#bottom2\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";

mysqli_data_seek($reorderResult, 0); // Reset the result pointer
$PNo2 = [];
$Pname2 = [];
$BackOrder2 = [];
$i2 = 0;
while ($record = mysqli_fetch_assoc($reorderResult)) {
  $PNo2[$i2] = $record['PartNo'];
  $Pname2[$i2] = $record['PartName'];
  $BackOrder2[$i2]=$record['BackOrder'];
  
  $statusClass = 'status-' . str_replace(' ', '', $record['Status']);
  echo "<tr class=\"$statusClass\">
    <td>{$record['ModifiedBinLocation']}</td>
    <td>{$record['PartName']}</td>
    <td>{$record['PartNo']}</td>
    <td>{$record['Category']}</td>
    <td>{$record['Available']}</td>
    <td>
        <form id='updateForm_$i2' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='requiredQty2_$i2' value='{$record['Required']}'/>
            <button id='no-fill' type='button' onclick=\"updateRequiredQty('$PNo2[$i2]', $i2)\">Update</button>
        </form>
    </td>
    <td>{$record['Received']}</td>
    <td>{$record['BackOrder']}</td>
    <td>
        <button id=\"no-fill\" onclick=\"fullOrderReceived('{$PNo2[$i2]}',$BackOrder2[$i2])\">Fully Received</button>
    </td><td>{$record['Status']}</td>
    <td>
        <form id='updateStatusForm_$i2' onkeydown=\"disableEnterKey(event)\">
            <select id='status2_$i2'>
                <option value='To be Ordered'>To be Ordered</option>
                <option value='Sent'>Sent</option>
            </select>
            <button id='no-fill' type='button' onclick=\"updateStatus('$PNo2[$i2]', $i2)\">Update</button>
        </form>
    </td>
    <td>
        <form id='purchaseOrderForm2_$i2' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='purchaseOrder2_$i2' value='{$record['PurchaseOrder']}'/>
            <button id='no-fill' type='button' onclick=\"updatePurchaseOrder('$PNo2[$i2]', $i2)\">Update</button>
        </form>
    </td>
    <td>
        <button id=\"no-fill\" onclick=\"promptUser('{$PNo2[$i2]}',$BackOrder2[$i2])\">Partially Received</button>
    </td>
    <td>{$formattedTimestamp}</td>
    <td>
        <form id='commentsForm_$i2' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='comments2_$i2' value='{$record['Comments']}'/>
            <button id='no-fill' type='button' onclick=\"updateComments('$PNo2[$i2]', $i2)\">Update</button>
        </form>
    </td>
  </tr>";
  $i2++;
}

echo "</tbody>
</table>";
 echo "<a id=\"bottom2\"></a>
<a id=\"up\" href=\"#top2\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>";
    echo "<br>";

    
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

$msg = '';

if ($conn) {
  $query = "SELECT *,
    CONCAT(
      BinLocation,
      CASE
        WHEN RIGHT(BinLocation, 2) REGEXP '^[0-9]+0$' THEN ''
        ELSE ''
      END
    ) AS ModifiedBinLocation
    FROM reordercables
    ORDER BY CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED),
    LENGTH(BinLocation),
    BinLocation";

  $reorderResult = mysqli_query($conn, $query);

  if ($reorderResult) {
    echo "
    <center>
    <h2 id=\"lbh\">Re-Order (Cables)</h2>
    <br>
    <h3>" . $msg . "<br><br>";



echo "<div id=\"filters\">
<label for=\"status\">Status:</label>
<select id=\"status\" onchange=\"applyFilters3()\">
<option value=\"\">All</option>"; // Include the default "All" option here

$statuses = array();
mysqli_data_seek($reorderResult, 0); // Reset the result pointer

while ($record = mysqli_fetch_assoc($reorderResult)) {
  $status = $record['Status'];
  if (!in_array($status, $statuses)) {
    $statuses[] = $status;
    echo "<option value=\"$status\">$status</option>";
  }
}

echo "</select>
</div></center>";
    echo "<br><button id=\"fill-green\" onclick=\"printTable3();\">Print</button>";

    echo "<table class=\"reorder\" id=\"lbt3\" border='2'>
<thead>
<tr>
    <th>Bin Location</th>
    <th>Description</th>
    <th>Part Number</th>
    <th>Category</th>
    <th>Required Quantity</th>
    <th>Received Quantity</th>
    <th>Back Order</th>
    <th>Fully Received</th>
    <th> Current Status</th>
	<th>Update Status</th>
    <th>Purchase Order</th>
    <th>Partially Received</th>
    <th>Last Updated</th>
    <th>Comments</th>
     <th>Delete</th>
</tr>
</thead>
<tbody><a id=\"top3\"><a id=\"down\" href=\"#bottom3\"><i style='font-size:24px; color:#A9A9A9' class='fas'>&#xf13a;</i></a>";

mysqli_data_seek($reorderResult, 0); // Reset the result pointer
$PNo3 = [];
$Pname3 = [];
$BackOrder3 = [];
$i3 = 0;
while ($record = mysqli_fetch_assoc($reorderResult)) {
  $PNo3[$i3] = $record['PartNo'];
  $Pname3[$i3] = $record['PartName'];
  $BackOrder3[$i3]=$record['BackOrder'];
  
  $statusClass = 'status-' . str_replace(' ', '', $record['Status']);
  echo "<tr class=\"$statusClass\">
    <td>{$record['ModifiedBinLocation']}</td>
    <td>{$record['PartName']}</td>
    <td>{$record['PartNo']}</td>
    <td>{$record['Category']}</td>
    <td>
        <form id='updateForm_$i3' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='requiredQty3_$i3' value='{$record['Required']}'/>
            <button id='no-fill' type='button' onclick=\"updateRequiredQty3('$PNo3[$i3]', $i3)\">Update</button>
        </form>
    </td>
    <td>{$record['Received']}</td>
    <td>{$record['BackOrder']}</td>
    <td>
        <button id=\"no-fill\" onclick=\"fullOrderReceived3('{$PNo3[$i3]}',$BackOrder3[$i3])\">Fully Received</button>
    </td><td>{$record['Status']}</td>
    <td>
        <form id='updateStatusForm_$i3' onkeydown=\"disableEnterKey(event)\">
            <select id='status3_$i3'>
                <option value='To be Ordered'>To be Ordered</option>
                <option value='Sent'>Sent</option>
            </select>
            <button id='no-fill' type='button' onclick=\"updateStatus3('$PNo3[$i3]', $i3)\">Update</button>
        </form>
    </td>
    <td>
        <form id='purchaseOrderForm_$i3' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='purchaseOrder3_$i3' value='{$record['PurchaseOrder']}'/>
            <button id='no-fill' type='button' onclick=\"updatePurchaseOrder3('$PNo3[$i3]', $i3)\">Update</button>
        </form>
    </td>
    <td>
        <button id=\"no-fill\" onclick=\"promptUser3('{$PNo3[$i3]}',$BackOrder3[$i3])\">Partially Received</button>
    </td>
    <td>{$record['LastUpdated']}</td>
    <td>
        <form id='commentsForm_$i3' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='comments3_$i3' value='{$record['Comments']}'/>
            <button id='no-fill' type='button' onclick=\"updateComments3('$PNo3[$i3]', $i3)\">Update</button>
        </form>
    </td>
<td>
        <a id=\"fill-white\" onclick=\"deleteRow3('$PNo3[$i3]')\">&#10060;</a>
    </td>
  </tr>";
  $i3++;
}

echo "</tbody>
</table>";
 echo "<a id=\"bottom3\"></a>
<a id=\"up\" href=\"#top3\"><i style='font-size:24px;color:#A9A9A9' class='fas'>&#xf139;</i></a>";

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
      <input type="radio" name="css-tabs" id="tab-4" class="tab-switch">
      <label for="tab-4" class="tab-label">CONSUMABLES</label>
      <div id="tab4" class="tab-content">
<?php

?>
	</div>
    </div>
		   <div class="tab">
      <input type="radio" name="css-tabs" id="tab-5" class="tab-switch">
      <label for="tab-5" class="tab-label">LABELS</label>
      <div id="tab5" class="tab-content">
<?php

?>
	</div>
    </div>
  </div>
</div>


<script>
// Wait for the page to fully load
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

// Add click event listeners to tab switches
tabSwitches.forEach(function(tabSwitch) {
  tabSwitch.addEventListener('click', function() {
    // Update the activeTab variable and store it in localStorage
    activeTab = this.getAttribute('id');
    localStorage.setItem('activeTab', activeTab);
  });
});





        function disableEnterKey(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                return false;
            }
        }
		
		    window.onload = function() {
        applyFilters1();
		applyFilters(); // Apply the initial filter
		applyFilters3(); // Apply the initial filter
    }

function applyFilters1() {
  var category1 = document.getElementById("category").value;
  var status1 = document.getElementById("status").value;
  var rows1 = document.getElementById("lbt1").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
  var columnsToRemove1 = [8, 10, 12]; // Adjust the indices as needed

  // Loop through the rows and remove the specified columns
  for (var i1 = 0; i1 < rows1.length; i1++) {
    var cells1 = rows1[i1].getElementsByTagName("td");
    for (var j1 = columnsToRemove1.length - 1; j1 >= 0; j1--) {
      var columnIndex1 = columnsToRemove1[j1];
      cells1[columnIndex1].remove();
    }
  }

  for (var i1 = 0; i1 < rows1.length; i1++) {
    var row1 = rows1[i1];
    var categoryCell1 = row1.getElementsByTagName("td")[3];
    var statusCell1 = row1.getElementsByTagName("td")[9];
    var categoryText1 = categoryCell1.textContent || categoryCell1.innerText;
    var statusText1 = statusCell1.textContent || statusCell1.innerText;
    var showRow1 = true;

    if (category1 && categoryText1 !== category1) {
      showRow1 = false;
    }

    if (status1 && statusText1 !== status1) {
      showRow1 = false;
    }

    if (showRow1) {
      row1.style.display = "";
    } else {
      row1.style.display = "none";
    }
  }
}


function updateRequiredQty1(partNo1, rowIndex1) {
    var requiredQtyInput1 = document.getElementById('requiredQty1_' + rowIndex1);
    var requiredQty1 = requiredQtyInput1.value;
//alert(requiredQtyInput1,requiredQty1);
    // Display a confirmation prompt with the new value
    var confirmed1 = confirm('Are you sure you want to update requiredQty to ' + requiredQty1 + '?');

    if (!confirmed1) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data1 = 'partNo=' + encodeURIComponent(partNo1) +
               '&requiredQty=' + encodeURIComponent(requiredQty1);

    // Send the data to the PHP page
    var xhr1 = new XMLHttpRequest();
    xhr1.open('POST', 'updaterequired1.php', true);
    xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState === 4 && xhr1.status === 200) {
            // Process the response from the PHP page
            var response1 = xhr1.responseText;
            alert(response1);

            // Refresh the page
            location.reload();
        }
    };
    xhr1.send(data1);
}



function fullOrderReceived1(partNo1, quantity1) {
    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you have received ' + quantity1 + ' of ' + partNo1 + '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data1 = 'partNo=' + encodeURIComponent(partNo1) +
               '&quantity=' + encodeURIComponent(quantity1);

    // Send the data to the PHP page
    var xhr1 = new XMLHttpRequest();
    xhr1.open('POST', 'fullOrderReceived1.php', true);
    xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState === 4 && xhr1.status === 200) {
            // Process the response from the PHP page
            var response1 = xhr1.responseText;
            alert(response1);

            // Refresh the page
            location.reload();
        }
    };
    xhr1.send(data1);
}

function updateStatus1(partNo1, rowIndex1) {
    var statusSelect1 = document.getElementById('status1_' + rowIndex1);
    var status1 = statusSelect1.value;

    // Display a confirmation prompt with the new value
    var confirmed1 = confirm('Are you sure you want to update the Status to ' + status1 + '?');

    if (!confirmed1) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data1 = 'partNo=' + encodeURIComponent(partNo1) +
               '&status=' + encodeURIComponent(status1);

    // Send the data to the PHP page
    var xhr1 = new XMLHttpRequest();
    xhr1.open('POST', 'updateorderstatus1.php', true);
    xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState === 4 && xhr1.status === 200) {
            // Process the response from the PHP page
            var response1 = xhr1.responseText;
            alert(response1);
            location.reload();
        }
    };
    xhr1.send(data1);
}

function updatePurchaseOrder1(partNo1, rowIndex1) {
    var purchaseOrderInput1 = document.getElementById('purchaseOrder1_' + rowIndex1);
    var purchaseOrder1 = purchaseOrderInput1.value;

    // Display a confirmation prompt with the new value
    var confirmed1 = confirm('Are you sure you want to update the Purchase Order to ' + purchaseOrder1 + '?');

    if (!confirmed1) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data1 = 'partNo=' + encodeURIComponent(partNo1) +
               '&purchaseOrder=' + encodeURIComponent(purchaseOrder1);

    // Send the data to the PHP page
    var xhr1 = new XMLHttpRequest();
    xhr1.open('POST', 'updatepurchaseorder1.php', true);
    xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState === 4 && xhr1.status === 200) {
            // Process the response from the PHP page
            var response1 = xhr1.responseText;
            alert(response1);
            location.reload();
        }
    };
    xhr1.send(data1);
}

function promptUser1(partNo1, oldQuantity1) {
    var newQuantity1 = prompt("For: " + partNo1 + "\nPartial Ordered Quantity:");
    var confirmed1 = confirm("Are you sure you want to update the Received Quantity to " + newQuantity1 + "?");

    if (!confirmed1) {
        return; // Exit the function if not confirmed
    }

    // Send the data to a PHP page
    var xhr1 = new XMLHttpRequest();
    xhr1.open("POST", "partialOrderReceived1.php", true);
    xhr1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState === 4 && xhr1.status === 200) {
            // Process the response from the PHP page
            var response1 = xhr1.responseText;
            alert(response1);
            // Refresh the page
            location.reload();
        }
    };

    // Prepare the data to send
    var data1 = 'partNo=' + encodeURIComponent(partNo1) +
                '&newQuantity=' + encodeURIComponent(newQuantity1) +
                '&oldQuantity=' + encodeURIComponent(oldQuantity1);
    xhr1.send(data1);
}

function updateComments1(partNo1, rowIndex1) {
    var commentsInput1 = document.getElementById('comments1_' + rowIndex1);
    var comments1 = commentsInput1.value;

    // Display a confirmation prompt with the new value
    var confirmed1 = confirm('Are you sure you want to update the Comments to ' + comments1 + '?');

    if (!confirmed1) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data1 = 'partNo=' + encodeURIComponent(partNo1) +
                '&comments=' + encodeURIComponent(comments1);

    // Send the data to the PHP page
    var xhr1 = new XMLHttpRequest();
    xhr1.open('POST', 'updatecomments1.php', true);
    xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr1.onreadystatechange = function() {
        if (xhr1.readyState === 4 && xhr1.status === 200) {
            // Process the response from the PHP page
            var response1 = xhr1.responseText;
            alert(response1);
            location.reload();
        }
    };
    xhr1.send(data1);
}

function printTable1() {
  var table = document.getElementById("lbt1");
  var windowContent = '<html><head><title>Print Table</title>';
  windowContent += '<style>' + getComputedStyle(table).cssText + '</style>';
  windowContent += '<style>table { width: 100%; border-collapse: collapse; } th, td { text-align: center; border: 1px solid black; } th {background-color: #ff8a8a;height: 50px;} .no-print,#no-fill { display: none; }</style>';
  windowContent += '</head><body>';

  // Retrieve the table rows
  var rows = table.rows;
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].cells;
    for (var j = cells.length - 1; j >= 0; j--) {
      // Check if the current cell index is 8 or 10
      if (j === 8 || j === 10 || j===12) {
        // Add a class to exempt the column from printing
        cells[j].classList.add('no-print');
      }
    }
  }

  windowContent += table.outerHTML;
  windowContent += '</body></html>';

  var printWindow = window.open('', '', '');
  printWindow.document.open();
  printWindow.document.write(windowContent);
  printWindow.document.close();
  printWindow.print();
}

function exportToExcel1() {
  var table = document.getElementById("lbt1");

  // Clone the table and remove unwanted columns and buttons
  var clonedTable = table.cloneNode(true);
  var columnsToRemove = [8, 10, 12]; // Indices of the columns to remove

  var rows = clonedTable.getElementsByTagName("tr");
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");
    for (var j = columnsToRemove.length - 1; j >= 0; j--) {
      var columnIndex = columnsToRemove[j];
      cells[columnIndex].parentNode.removeChild(cells[columnIndex]);
    }

    var buttons = rows[i].getElementsByTagName("button");
    for (var k = buttons.length - 1; k >= 0; k--) {
      buttons[k].parentNode.removeChild(buttons[k]);
    }
  }

  var wrapperDiv = document.createElement("div");
  wrapperDiv.appendChild(clonedTable);

  var wrapperHtml = wrapperDiv.innerHTML;

  var excelWindow = window.open('', '', '');
  excelWindow.document.open();
  excelWindow.document.write('<html><head><title>Excel Export</title></head><body>');
  excelWindow.document.write(wrapperHtml);
  excelWindow.document.write('</body></html>');
  excelWindow.document.close();

  // Clean up resources
  wrapperDiv = null;
  clonedTable = null;
  columnsToRemove = null;
}


function applyFilters() {
  var category = document.getElementById("category").value;
  var status = document.getElementById("status").value;
  var rows = document.getElementById("lbt2").getElementsByTagName("tbody")[0].getElementsByTagName("tr");

  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    //var categoryCell = row.getElementsByTagName("td")[3];
    var statusCell = row.getElementsByTagName("td")[9];
    //var categoryText = categoryCell.textContent || categoryCell.innerText;
    var statusText = statusCell.textContent || statusCell.innerText;
    var showRow = true;


    if (status && statusText !== status) {
      showRow = false;
    }

    if (showRow) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  }
}


function fullOrderReceived(partNo, quantity) {


    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you have recieved '+quantity+' of '+partNo+ '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&quantity=' + encodeURIComponent(quantity);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fullOrderReceived.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);

            // Refresh the page
            location.reload();
        }
    };
    xhr.send(data);
}

function promptUser(partNo, oldQuantity) {
    var newQuantity = prompt("For: " + partNo + "\nPartial Ordered Quantity:");
    var confirmed = confirm("Are you sure you want to update the Received Quantity to " + newQuantity + "?");

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Send the data to a PHP page
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "partialOrderReceived.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);
            // Refresh the page
            location.reload();
        }
    };

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&newQuantity=' + encodeURIComponent(newQuantity) +
			   '&oldQuantity=' + encodeURIComponent(oldQuantity) ; 
    xhr.send(data);
}

function updateRequiredQty(partNo, rowIndex) {
    var requiredQtyInput = document.getElementById('requiredQty2_' + rowIndex);
    var requiredQty = requiredQtyInput.value;

    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to update requiredQty to ' + requiredQty + '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&requiredQty=' + encodeURIComponent(requiredQty);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updaterequired.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);

            // Refresh the page
            location.reload();
        }
    };
    xhr.send(data);
}

function updateStatus(partNo, rowIndex) {
    var statusSelect = document.getElementById('status2_' + rowIndex);
    var status = statusSelect.value;

    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to update the Status to ' + status + '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&status=' + encodeURIComponent(status);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateorderstatus.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);
			location.reload();
        }
    };
    xhr.send(data);
}

function updatePurchaseOrder(partNo, rowIndex) {
    var purchaseOrderInput = document.getElementById('purchaseOrder2_' + rowIndex);
    var purchaseOrder = purchaseOrderInput.value;

    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to update the Purchase Order to ' + purchaseOrder + '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&purchaseOrder=' + encodeURIComponent(purchaseOrder);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updatepurchaseorder.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() { 
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);
            location.reload();
        }
    };
    xhr.send(data);
}

function updateComments(partNo, rowIndex) {
    var commentsInput = document.getElementById('comments2_' + rowIndex);
    var comments = commentsInput.value;

    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to update the Comments to ' + comments + '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&comments=' + encodeURIComponent(comments);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updatecomments.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {             // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);
location.reload();
        }
    };
    xhr.send(data);
}

function printTable() {
  var table = document.getElementById("lbt2");
  var windowContent = '<html><head><title>Print Table</title>';
  windowContent += '<style>' + getComputedStyle(table).cssText + '</style>';
  windowContent += '<style>table { width: 100%; border-collapse: collapse; } th, td { text-align: center; border: 1px solid black; } th {background-color: #ff8a8a;height: 50px;} .no-print,#no-fill { display: none; }</style>';
  windowContent += '</head><body>';

  // Retrieve the table rows
  var rows = table.rows;
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].cells;
    for (var j = cells.length - 1; j >= 0; j--) {
      // Check if the current cell index is 8 or 10
      if (j === 8 || j === 10 || j===12) {
        // Add a class to exempt the column from printing
        cells[j].classList.add('no-print');
      }
    }
  }

  windowContent += table.outerHTML;
  windowContent += '</body></html>';

  var printWindow = window.open('', '', '');
  printWindow.document.open();
  printWindow.document.write(windowContent);
  printWindow.document.close();
  printWindow.print();
}

function applyFilters3() {
  var category3 = document.getElementById("category").value;
  var status3 = document.getElementById("status").value;
  var rows3 = document.getElementById("lbt3").getElementsByTagName("tbody")[0].getElementsByTagName("tr");

  for (var i = 0; i < rows3.length; i++) {
    var row3 = rows3[i];
    var statusCell3 = row3.getElementsByTagName("td")[9];
    var statusText3 = statusCell3.textContent || statusCell3.innerText;
    var showRow3 = true;

    if (status3 && statusText3 !== status3) {
      showRow3 = false;
    }

    if (showRow3) {
      row3.style.display = "";
    } else {
      row3.style.display = "none";
    }
  }
}

function deleteRow3(partNo3) {
  var confirmed3 = confirm('Are you sure you want to delete this row?');

  if (!confirmed3) {
    return; // Exit the function if not confirmed
  }

  // Send the data to the PHP page
  var xhr3 = new XMLHttpRequest();
  xhr3.open('POST', 'deleterowcables.php', true);
  xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr3.onreadystatechange = function() {
    if (xhr3.readyState === 4 && xhr3.status === 200) {
      // Process the response from the PHP page
      var response3 = xhr3.responseText;
      alert(response3);
      location.reload();
    }
  };

  // Prepare the data to send
  var data3 = 'partNo=' + encodeURIComponent(partNo3);
  xhr3.send(data3);
}

function fullOrderReceived3(partNo3, quantity3) {
  // Display a confirmation prompt with the new value
  var confirmed3 = confirm('Are you sure you have received ' + quantity3 + ' of ' + partNo3 + '?');

  if (!confirmed3) {
    return; // Exit the function if not confirmed
  }

  // Prepare the data to send
  var data3 = 'partNo=' + encodeURIComponent(partNo3) +
    '&quantity=' + encodeURIComponent(quantity3);

  // Send the data to the PHP page
  var xhr3 = new XMLHttpRequest();
  xhr3.open('POST', 'fullOrderReceivedcables.php', true);
  xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr3.onreadystatechange = function() {
    if (xhr3.readyState === 4 && xhr3.status === 200) {
      // Process the response from the PHP page
      var response3 = xhr3.responseText;
      alert(response3);

      // Refresh the page
      location.reload();
    }
  };
  xhr3.send(data3);
}

function promptUser3(partNo3, oldQuantity3) {
  var newQuantity3 = prompt("For: " + partNo3 + "\nPartial Ordered Quantity:");
  var confirmed3 = confirm("Are you sure that you have received " + newQuantity3 + "?");

  if (!confirmed3) {
    return; // Exit the function if not confirmed
  }

  // Send the data to a PHP page
  var xhr3 = new XMLHttpRequest();
  xhr3.open("POST", "partialOrderReceivedcables.php", true);
  xhr3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr3.onreadystatechange = function() {
    if (xhr3.readyState === 4 && xhr3.status === 200) {
      // Process the response from the PHP page
      var response3 = xhr3.responseText;
      alert(response3);
      // Refresh the page
      location.reload();
    }
  };

  // Prepare the data to send
  var data3 = 'partNo=' + encodeURIComponent(partNo3) +
    '&newQuantity=' + encodeURIComponent(newQuantity3) +
    '&oldQuantity=' + encodeURIComponent(oldQuantity3);
  xhr3.send(data3);
}

function updateRequiredQty3(partNo3, rowIndex3) {
	 //alert(partNo3);
  var requiredQtyInput3 = document.getElementById('requiredQty3_' + rowIndex3);
  var requiredQty3 = requiredQtyInput3.value;

  // Display a confirmation prompt with the new value
  var confirmed3 = confirm('Are you sure you want to update the Required Quantity to ' + requiredQty3 + '?');

  if (!confirmed3) {
    return; // Exit the function if not confirmed
  }

  // Prepare the data to send
  var data3 = 'partNo=' + encodeURIComponent(partNo3) +
    '&requiredQty=' + encodeURIComponent(requiredQty3);

  // Send the data to the PHP page
  var xhr3 = new XMLHttpRequest();
  xhr3.open('POST', 'updaterequiredcables.php', true);
  xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr3.onreadystatechange = function() {
    if (xhr3.readyState === 4 && xhr3.status === 200) {
      // Process the response from the PHP page
      var response3 = xhr3.responseText;
      alert(response3);

      // Refresh the page
      location.reload();
    }
  };
  xhr3.send(data3);
}

function updateStatus3(partNo3, rowIndex3) {
    var statusSelect3 = document.getElementById('status3_' + rowIndex3);
    var status3 = statusSelect3.value;

    // Display a confirmation prompt with the new value
    var confirmed3 = confirm('Are you sure you want to update the Status to ' + status3 + '?');

    if (!confirmed3) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data3 = 'partNo=' + encodeURIComponent(partNo3) +
               '&status=' + encodeURIComponent(status3);

    // Send the data to the PHP page
    var xhr3 = new XMLHttpRequest();
    xhr3.open('POST', 'updateorderstatuscables.php', true);
    xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr3.onreadystatechange = function() {
        if (xhr3.readyState === 4 && xhr3.status === 200) {
            // Process the response from the PHP page
            var response3 = xhr3.responseText;
            alert(response3);
			location.reload();
        }
    };
    xhr3.send(data3);
}

function updatePurchaseOrder3(partNo3, rowIndex3) {
    var purchaseOrderInput3 = document.getElementById('purchaseOrder3_' + rowIndex3);
    var purchaseOrder3 = purchaseOrderInput3.value;

    // Display a confirmation prompt with the new value
    var confirmed3 = confirm('Are you sure you want to update the Purchase Order to ' + purchaseOrder3 + '?');

    if (!confirmed3) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data3 = 'partNo=' + encodeURIComponent(partNo3) +
               '&purchaseOrder=' + encodeURIComponent(purchaseOrder3);

    // Send the data to the PHP page
    var xhr3 = new XMLHttpRequest();
    xhr3.open('POST', 'updatepurchaseordercables.php', true);
    xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr3.onreadystatechange = function() { 
        if (xhr3.readyState === 4 && xhr3.status === 200) {
            // Process the response from the PHP page
            var response3 = xhr3.responseText;
            alert(response3);
            location.reload();
        }
    };
    xhr3.send(data3);
}


function updateComments3(partNo3, rowIndex3) {
    var commentsInput3 = document.getElementById('comments3_' + rowIndex3);
    var comments3 = commentsInput3.value;

    // Display a confirmation prompt with the new value
    var confirmed3 = confirm('Are you sure you want to update the Comments to ' + comments3 + '?');

    if (!confirmed3) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data3 = 'partNo=' + encodeURIComponent(partNo3) +
               '&comments=' + encodeURIComponent(comments3);

    // Send the data to the PHP page
    var xhr3 = new XMLHttpRequest();
    xhr3.open('POST', 'updatecommentscables.php', true);
    xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr3.onreadystatechange = function() {
        if (xhr3.readyState === 4 && xhr3.status === 200) {             // Process the response from the PHP page
            var response3 = xhr3.responseText;
            alert(response3);
			location.reload();
        }
    };
    xhr3.send(data3);
}

function printTable3() {
  var table3 = document.getElementById("lbt3");
  var windowContent3 = '<html><head><title>Print Table</title>';
  windowContent3 += '<style>' + getComputedStyle(table3).cssText + '</style>';
  windowContent3 += '<style>table { width: 100%; border-collapse: collapse; } th, td { text-align: center; border: 1px solid black; } th {background-color: #ff8a8a;height: 50px;} .no-print,#no-fill { display: none; }</style>';
  windowContent3 += '</head><body>';

  // Retrieve the table rows
  var rows3 = table3.rows;
  for (var i3 = 0; i3 < rows3.length; i3++) {
    var cells3 = rows3[i3].cells;
    for (var j3 = cells3.length - 1; j3 >= 0; j3--) {
      // Check if the current cell index is 8 or 10
      if (j3 === 7 || j3 === 9 || j3===11 || j3===14) {
        // Add a class to exempt the column from printing
        cells3[j3].classList.add('no-print');
      }
    }
  }

  windowContent3 += table3.outerHTML;
  windowContent3 += '</body></html>';

  var printWindow3 = window.open('', '', '');
  printWindow3.document.open();
  printWindow3.document.write(windowContent3);
  printWindow3.document.close();
  printWindow3.print();
}



</script>