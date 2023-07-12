<?php
include 'common.php';
?>

<style>
#cables {
    width: 30px;
    height: 30px;
    text-decoration: none;
    font-size: 15px;
    color: Red;
    margin-bottom: -5px;
}

#categoryTotals {
    display: none; /* Hide the categoryTotals table by default */
    margin-top: 20px;
    border-collapse: collapse;
}

#categoryTotals th,
#categoryTotals td {
    padding: 5px;
    border: 1px solid black;
}
</style>

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
        echo "<a id=\"cables\" href=\"cablestock.php\">Cables<img id= \"cables\"  src=\"images/cable.png\"/></a><center><h2 id=\"lbh\">Available Stock</h2><br><br><br>";

        echo "<div id=\"filters\">
                <label for=\"category\">Category:</label>
                <select id=\"category\" onchange=\"applyFilters()\">
                    <option value=\"TopHats\">TopHats</option>"; // Only include the desired category option(s) here

        $categories = array();
        mysqli_data_seek($result, 0); // Reset the result pointer

        while ($record = mysqli_fetch_assoc($result)) {
            $category = $record['Category'];
            if (!in_array($category, $categories)) {
                $categories[] = $category;
                if ($category !== "TopHats") { // Exclude the "TopHats" category from the dropdown
                    echo "<option value=\"$category\">$category</option>";
                }
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
    };

    function applyFilters() {
        var category = document.getElementById("category").value;
        var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        var total = 0; // Variable to calculate the category total

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var categoryCell = row.getElementsByTagName("td")[15];
            var categoryText = categoryCell.textContent || categoryCell.innerText;
            var showRow = true;

            if (category && categoryText !== category) {
                showRow = false;
            }

            if (showRow) {
                row.style.display = "";
                total += parseFloat(row.getElementsByTagName("td")[13].textContent || 0);
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
        printWindow.document.write('<style>' + getComputedStyle(categoryTotalsTable).cssText + '</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Available Stock</h2>');
        printWindow.document.write('<h3>Total: ' + categoryTotal + '</h3>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write(categoryTotalsTable.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
