<?php include 'common.php'; ?>
<style>
  table {
    border-collapse: collapse;
  }
  table,
  th,
  td {
    text-align: center;
    border: 1px solid black;
  }
  thead {
    background-color: #FFA6A7;
    color: #ffffff;
  }
  th {
    text-align: center;
    height: 50px;
  }
#tablediv {
    width: 100%;
    margin-left:10%;
    margin-top:-23%;

}
  tbody tr:nth-child(odd) {
    background: #ffffff;
  }
  tbody tr:nth-child(even) {
    background: #FFC6C7;
  }
h2 {
     margin-left:55%;
		}
  #filters
{

margin-left:-80%;
} 
  /* Print styles */
  @media print {
    body * {
      visibility: hidden;
    }
    #printTable,
    #printTable * {
      visibility: visible;
    }
    #printTable {
      position: absolute;
      left: 1.5;
      top: 0;
    }
  }
</style>


  <h2>Order History</h2>
<center>
  <?php
  include 'connection.php';

  // Retrieve filter options from the database
  $filterCategoryQuery = "SELECT DISTINCT Category FROM orderhistory";
  $filterCategoryResult = mysqli_query($conn, $filterCategoryQuery);
  $filterCategoryOptions = mysqli_fetch_all($filterCategoryResult, MYSQLI_ASSOC);

  // Apply filters
  $category = $_GET['category'] ?? '';
  $partNo = $_GET['partNo'] ?? '';
  $startDate = $_GET['startDate'] ?? '';
  $endDate = $_GET['endDate'] ?? '';

  $query = "SELECT * FROM orderhistory WHERE Category LIKE '%$category%' AND PartNo LIKE '%$partNo%'";

  if (!empty($startDate) && !empty($endDate)) {
    $query .= " AND LastUpdated BETWEEN '$startDate' AND '$endDate'";
  }

  $query .= " ORDER BY LastUpdated DESC";

  $result = mysqli_query($conn, $query);

  if ($result) {
    // Filter form
    echo "<div id=\"filters\" ><form method=\"get\" action=\"\">
            <label for=\"category\">Category:</label>
            <select id=\"category\" name=\"category\">
              <option value=\"\">All</option>";
    foreach ($filterCategoryOptions as $option) {
      $selected = ($option['Category'] == $category) ? 'selected' : '';
      echo "<option value=\"{$option['Category']}\" $selected>{$option['Category']}</option>";
    }
    echo "</select>
            <label for=\"partNo\">Part Number:</label>
            <input class=\"input-field\" id=\"idFld\" type=\"text\" id=\"partNo\" name=\"partNo\" value=\"$partNo\">
            <label for=\"startDate\">Start Date:</label>
            <input class=\"input-field\" id=\"idFld\" type=\"date\" id=\"startDate\" name=\"startDate\" value=\"$startDate\">
            <label for=\"endDate\">End Date:</label>
            <input class=\"input-field\" id=\"idFld\"  type=\"date\" id=\"endDate\" name=\"endDate\" value=\"$endDate\">
            <br><button id=\"fill\"><input type=\"submit\" value=\"Filter  \"></button>
          </form></div><br>";

    // Table
    echo "<div id=\"tablediv\" ><table id=\"printTable\"><thead><tr><th>Bin Location</th><th>Description</th><th>Part Number</th><th>Category</th><th>Ordered</th><th>Purchase Order</th><th>Comments</th><th>Last Updated</tr></thead><tbody>";
    while ($record = mysqli_fetch_assoc($result)) {
      echo "<tr>
              <td>{$record['BinLocation']}</td>
              <td>{$record['PartName']}</td>
              <td>{$record['PartNo']}</td>
              <td>{$record['Category']}</td>
              <td>{$record['Ordered']}</td>
              <td>{$record['PurchaseOrder']}</td>
              <td>{$record['Comments']}</td>
              <td>{$record['LastUpdated']}</td>
            </tr>";
    }
    echo "</tbody></table>";

    // Print button
    echo "<br><button class=\"printbtn\" id=\"fill-green\" onclick=\"window.print();\">Print</button></div>";
  }

  mysqli_close($conn);
  ?>
  <?php include 'loading.php'; ?>
</center>
