<style>
  #cables {
    width: 30px;
    height: 30px;
    text-decoration: none;
    font-size: 15px;
    color: red;
    margin-bottom: -5px;
  }

  .reorder {
    width: 90%;
    padding: 10%;
    margin-left: 1%;
    margin-right: 1%;
  }
  #filters{
	  margin-left:10%;
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
<script>
        function disableEnterKey(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                return false;
            }
        }
</script>
<?php
include 'common.php';
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



echo "<center><div id=\"filters\">
<label for=\"status\">Status:</label>
<select id=\"status\" onchange=\"applyFilters()\">
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

    echo "<table class=\"reorder\" id=\"lbt\" border='2'>
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
<tbody>";

mysqli_data_seek($reorderResult, 0); // Reset the result pointer
$PNo = [];
$Pname = [];
$BackOrder = [];
$i = 0;
while ($record = mysqli_fetch_assoc($reorderResult)) {
  $PNo[$i] = $record['PartNo'];
  $Pname[$i] = $record['PartName'];
  $BackOrder[$i]=$record['BackOrder'];
  
  $statusClass = 'status-' . str_replace(' ', '', $record['Status']);
  echo "<tr class=\"$statusClass\">
    <td>{$record['ModifiedBinLocation']}</td>
    <td>{$record['PartName']}</td>
    <td>{$record['PartNo']}</td>
    <td>{$record['Category']}</td>
    <td>
        <form id='updateForm_$i' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='requiredQty_$i' value='{$record['Required']}'/>
            <button id='no-fill' type='button' onclick=\"updateRequiredQty('$PNo[$i]', $i)\">Update</button>
        </form>
    </td>
    <td>{$record['Received']}</td>
    <td>{$record['BackOrder']}</td>
    <td>
        <button id=\"no-fill\" onclick=\"fullOrderRecieved('{$PNo[$i]}',$BackOrder[$i])\">Fully Received</button>
    </td><td>{$record['Status']}</td>
    <td>
        <form id='updateStatusForm_$i' onkeydown=\"disableEnterKey(event)\">
            <select id='status_$i'>
                <option value='To be Ordered'>To be Ordered</option>
                <option value='Sent'>Sent</option>
            </select>
            <button id='no-fill' type='button' onclick=\"updateStatus('$PNo[$i]', $i)\">Update</button>
        </form>
    </td>
    <td>
        <form id='purchaseOrderForm_$i' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='purchaseOrder_$i' value='{$record['PurchaseOrder']}'/>
            <button id='no-fill' type='button' onclick=\"updatePurchaseOrder('$PNo[$i]', $i)\">Update</button>
        </form>
    </td>
    <td>
        <button id=\"no-fill\" onclick=\"promptUser('{$PNo[$i]}',$BackOrder[$i])\">Partially Received</button>
    </td>
    <td>{$record['LastUpdated']}</td>
    <td>
        <form id='commentsForm_$i' onkeydown=\"disableEnterKey(event)\">
            <input type='text' id='comments_$i' value='{$record['Comments']}'/>
            <button id='no-fill' type='button' onclick=\"updateComments('$PNo[$i]', $i)\">Update</button>
        </form>
    </td>
<td>
        <a id=\"fill-white\" onclick=\"deleteRow('$PNo[$i]')\">&#10060;</a>
    </td>
  </tr>";
  $i++;
}

echo "</tbody>
</table>";

    echo "<br><button id=\"fill-green\" onclick=\"printTable();\">Print</button>";

    echo "</center>";
  } else {
    echo "Error executing the query: " . mysqli_error($conn);
  }

  mysqli_close($conn);
} else {
  echo "Error connecting to the database.";
}
?>

<script>
    window.onload = function() {
        applyFilters(); // Apply the initial filter
    }

function applyFilters() {
  var category = document.getElementById("category").value;
  var status = document.getElementById("status").value;
  var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");

  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    var statusCell = row.getElementsByTagName("td")[9];
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

function deleteRow(partNo) {
    var confirmed = confirm('Are you sure you want to delete this row?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'deleterowcables.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response from the PHP page
            var response = xhr.responseText;
            alert(response);
            location.reload();
        }
    };

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo);
    xhr.send(data);
}

function fullOrderRecieved(partNo, quantity) {


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
    xhr.open('POST', 'fullOrderRecievedcables.php', true);
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
    var confirmed = confirm("Are you sure that you have received " + newQuantity + "?");

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Send the data to a PHP page
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "partialOrderRecievedcables.php", true);
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
    var requiredQtyInput = document.getElementById('requiredQty_' + rowIndex);
    var requiredQty = requiredQtyInput.value;

    // Display a confirmation prompt with the new value
    var confirmed = confirm('Are you sure you want to update the Required Quantity to ' + requiredQty + '?');

    if (!confirmed) {
        return; // Exit the function if not confirmed
    }

    // Prepare the data to send
    var data = 'partNo=' + encodeURIComponent(partNo) +
               '&requiredQty=' + encodeURIComponent(requiredQty);

    // Send the data to the PHP page
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updaterequiredcables.php', true);
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
    var statusSelect = document.getElementById('status_' + rowIndex);
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
    xhr.open('POST', 'updateorderstatuscables.php', true);
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
    var purchaseOrderInput = document.getElementById('purchaseOrder_' + rowIndex);
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
    xhr.open('POST', 'updatepurchaseordercables.php', true);
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
    var commentsInput = document.getElementById('comments_' + rowIndex);
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
    xhr.open('POST', 'updatecommentscables.php', true);
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
    var table = document.getElementById("lbt");
    var windowContent = '<html><head><title>Print Table</title>';
    windowContent += '<style>' + getComputedStyle(table).cssText + '</style>';
    windowContent += '<style>table { width: 100%; border-collapse: collapse; } th, td { text-align: center; border: 1px solid black; } th {background-color: #ff8a8a;height: 50px;}</style>';
    windowContent += '</head><body>';

    // Remove buttons from each row
    var rows = table.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        var buttons = rows[i].getElementsByTagName('button');
        for (var j = 0; j < buttons.length; j++) {
            buttons[j].style.display = 'none';
        }
    }

    windowContent += table.outerHTML;
    windowContent += '</body></html>';

    var printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.open();
    printWindow.document.write(windowContent);
    printWindow.document.close();
    printWindow.print();
}
</script>
