<?php
include 'connection.php';

// Retrieve the filter values from the GET parameters
$projectManager = $_GET['project_manager'] ?? '';
$fitter = $_GET['fitter'] ?? '';
$status = $_GET['status'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

// Construct the SQL query based on the selected filters
$query = "SELECT * FROM jobs WHERE 1=1";
if (!empty($projectManager)) {
  $query .= " AND projectmanager = '$projectManager'";
}
if (!empty($fitter)) {
  $query .= " AND allocatedfitter = '$fitter'";
}
if (!empty($status)) {
  $query .= " AND currentstate = '$status'";
}
if (!empty($fromDate)) {
  $query .= " AND LastUpdated >= '$fromDate'";
}
if (!empty($toDate)) {
  $query .= " AND LastUpdated <= '$toDate'";
}

// Execute the filtered query
$result = mysqli_query($conn, $query);

if ($result) {
  echo "<center><h2 id=\"lbh\">JOBS</h2><br><br><br>";
  echo "<table id=\"lbt\" border='2'>
        <thead>
          <tr>
            <th>Sales Order</th>
            <th>Project Manager</th>
            <th>Fitter</th>
            <th>Status</th>
            <th>Last Updated</th>
          </tr>
        </thead>
        <tbody>";

  while ($record = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td class='jobid'>{$record['jobid']}</td>
            <td>{$record['projectmanager']}</td>
            <td>{$record['allocatedfitter']}</td>
            <td>{$record['currentstate']}</td>
            <td>{$record['LastUpdated']}</td>
          </tr>";
  }
  echo "</tbody></table></center>";
} else {
  echo "Error retrieving filtered data from the database.";
}
?>
