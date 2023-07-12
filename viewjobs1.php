<?php include 'common.php'; ?>
<style>
#output{
	margin-top:-40%;
	}
</style>
    <div id="filters">
      <label for="project_manager">Project Manager:</label>
      <select id="project_manager">
        <option value="">All</option>
        <?php
        include 'connection.php';
        // Fetch project managers from the database
        $query = "SELECT DISTINCT projectmanager FROM jobs";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          $projectManager = $row['projectmanager'];
          echo "<option value='$projectManager'>$projectManager</option>";
        }
        ?>
      </select>

      <label for="fitter">Fitter:</label>
      <select id="fitter">
        <option value="">All</option>
        <?php
        // Fetch fitters from the database
        $query = "SELECT DISTINCT allocatedfitter FROM jobs";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          $fitter = $row['allocatedfitter'];
          echo "<option value='$fitter'>$fitter</option>";
        }
        ?>
      </select>


      <label for="status">Status:</label>
      <select id="status">
        <option value="">All</option>
        <?php
        // Fetch statuses from the database
        $query = "SELECT DISTINCT currentstate FROM jobs";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          $status = $row['currentstate'];
          $color = ''; // Initialize color variable

          // Set color based on the status
          if ($status == 'InProgress') {
            $color = '#ffff00'; // Yellow color for InProgress
          } elseif ($status == 'Assigned') {
            $color = '#ffc000'; // Orange color for Assigned
          } elseif ($status == 'Completed') {
            $color = '#04b0f0'; // Cyan color for Completed
          } elseif ($status == 'Tested') {
            $color = '#00ff80'; // Green color for Tested
          } elseif ($status == 'Not Assigned') {
            $color = '#ffffff'; // White color for Not Assigned
          }

          echo "<option value='$status' style='background-color: $color;'>$status</option>";
        }
        ?>
      </select>
      <label for="type">Type:</label>
      <select id="type">
        <option value="">All</option>
        <?php
        // Fetch types from the database
        $query = "SELECT DISTINCT type FROM jobs";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          $type = $row['type'];
          echo "<option value='$type'>$type</option>";
        }
        ?>
      </select>

      <label for="start_date">Start Date:</label>
      <input type="date" id="start_date">

      <label for="end_date">End Date:(Exclusive)</label>
      <input type="date" id="end_date">

      <button id="fill" onclick="applyFilters()">Apply</button>
      <button id="fill" onclick="clearFilters()">Clear Filters</button>
    </div>

    <div id="output">
      <?php
      if ($conn) {
        $query = "SELECT * FROM jobs ORDER BY LastUpdated DESC";
        $result = mysqli_query($conn, $query);

        if ($result) {
          echo "<center><h2 id=\"lbh\">JOBS</h2><br>";
          echo "<table id=\"lbt\" border='2'>
                  <thead>
                    <tr>
                      <th>Sales Order</th>
                      <th>Project Manager</th>
                      <th>Fitter</th>
                      <th>Status</th>
                      <th>Type</th>
                      <th>Last Updated</th>
                    </tr>
                  </thead>
                  <tbody>";

          while ($record = mysqli_fetch_assoc($result)) {
            $status = $record['currentstate'];
            $type = $record['type'];
            $color = ''; // Initialize color variable

            // Set color based on the status
            if ($status == 'InProgress') {
              $color = '#ffff00'; // Yellow color for InProgress
            } elseif ($status == 'Assigned') {
              $color = '#ffc000'; // Orange color for Assigned
            } elseif ($status == 'Completed') {
              $color = '#04b0f0'; // Cyan color for Completed
            } elseif ($status == 'Tested') {
              $color = '#00ff80'; // Green color for Tested
            } elseif ($status == 'Not Assigned') {
              $color = '#ffffff'; // White color for Not Assigned
            }

            echo "<tr id='$status' style='background-color: $color;'>
                    <td class='jobid'>{$record['jobid']}</td>
                    <td class='project-manager'>{$record['projectmanager']}</td>
                    <td class='fitter'>{$record['allocatedfitter']}</td>
                    <td class='status'>$status</td>
                    <td class='type'>$type</td>
                    <td class='last-updated'>{$record['LastUpdated']}</td>
                  </tr>";
          }

          echo "</tbody>
                </table><br><button id='fill-green' onclick='printTable()'>Print</button></center>";
        } else {
          echo "No records found.";
        }

        mysqli_close($conn);
      } else {
        echo "Error connecting to the database.";
      }
      ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.16/jspdf.plugin.autotable.min.js"></script>

    <script>
      function printTable() {
        var tableContent = document.getElementById("lbt").outerHTML;
        var newWindow = window.open("", "_blank");
        newWindow.document.write('<html><center><head><title>Print Table</title>');
        newWindow.document.write('<style>@media print {table {border-collapse: collapse;}');
        newWindow.document.write('table, th, td {text-align: center; border: 1px solid black;}');
        newWindow.document.write('th {text-align: center; background-color: #ff8a8a; height: 50px;}');
        newWindow.document.write('tr[data-status="InProgress"] { background-color: #ffff00 !important; }');
        newWindow.document.write('tr[data-status="Assigned"] { background-color: #ffc000 !important; }');
        newWindow.document.write('tr[data-status="Completed"] { background-color: #04b0f0 !important; }');
        newWindow.document.write('tr[data-status="Tested"] { background-color: #00ff80 !important; }');
        newWindow.document.write('tr[data-status="Not Assigned"] { background-color: #ffffff !important; }}');
        newWindow.document.write('</style></head><body>');
        newWindow.document.write('<h2>Jobs</h2>');
        newWindow.document.write(tableContent);
        newWindow.document.write('</body></center></html>');
        newWindow.document.close();

        newWindow.onload = function () {
          newWindow.print();
        };
      }

      function clearFilters() {
        document.getElementById("project_manager").value = "";
        document.getElementById("fitter").value = "";
        document.getElementById("status").value = "";
        document.getElementById("type").value = "";
        document.getElementById("start_date").value = "";
        document.getElementById("end_date").value = "";
        applyFilters();
      }

      function applyFilters() {
        var projectManager = document.getElementById("project_manager").value;
        var fitter = document.getElementById("fitter").value;
        var status = document.getElementById("status").value;
        var type = document.getElementById("type").value;
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;

        var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        for (var i = 0; i < rows.length; i++) {
          var row = rows[i];
          var projectManagerCell = row.getElementsByClassName("project-manager")[0];
          var fitterCell = row.getElementsByClassName("fitter")[0];
          var statusCell = row.getElementsByClassName("status")[0];
          var typeCell = row.getElementsByClassName("type")[0];
          var lastUpdatedCell = row.getElementsByClassName("last-updated")[0];

          var projectManagerText = projectManagerCell.textContent || projectManagerCell.innerText;
          var fitterText = fitterCell.textContent || fitterCell.innerText;
          var statusText = statusCell.textContent || statusCell.innerText;
          var typeText = typeCell.textContent || typeCell.innerText;
          var lastUpdatedText = lastUpdatedCell.textContent || lastUpdatedCell.innerText;

          var showRow = true;

          if (projectManager && projectManagerText !== projectManager) {
            showRow = false;
          }

          if (fitter && fitterText !== fitter) {
            showRow = false;
          }

          if (status && statusText !== status) {
            showRow = false;
          }

          if (type && typeText !== type) {
            showRow = false;
          }

          if (startDate && lastUpdatedText < startDate) {
            showRow = false;
          }

          if (endDate && lastUpdatedText >= endDate) {
            showRow = false;
          }

          if (showRow) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        }
      }
    </script>
	<?php include 'loading.php'; ?>
  </body>
</html>
