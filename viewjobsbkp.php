<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Applicant login page for CorpU">
  <meta name="keywords" content="CorpU, recruitment, IT">
  <meta name="author" content="Group 23">
  <title>CBi &#8211; Stock Management System</title>
  <link rel="stylesheet" type="text/css" href="styles/style.css">
  <link rel="icon" type="image/x-icon" href="images/game-fill.png">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/d7376949ab.js" crossorigin="anonymous"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    table {
      width: 70%;
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

    tbody tr:nth-child(odd) {
      background: #ffffff;
    }

    tbody tr:nth-child(even) {
      background: #FFC6C7;
    }

    #filters {
      width: 20%;
      margin-left: 3%;
    }

    #output {
      margin-left: 20%;
      margin-top: -25%;
      padding-bottom: 10%;
    }
  </style>
</head>

<body>
  <header>
    <nav>
      <div class="corpu-logo"><img src="images/cbi-logo.png" alt="CorpU logo"></div>
      <input type="checkbox" id="burger">
      <label for="burger" class="burgerbtn">
        <i class="ri-menu-line"></i>
      </label>
      <ul>
        <li><a href="Dashboard.html" id="select">
            <p>Dashboard</p>
          </a></li>
        <li><a href="Stock.php" id="select">
            <p>Stock</p>
          </a></li>
        <li><a href="updatebybin1.php" id="select">
            <p>Update by BinLocation</p>
          </a></li>
        <li><a href="updatebypartno1.php" id="select">
            <p>Update by PartNumber</p>
          </a></li>
        <li><a href="viewjobs1.php" id="select">
            <p>View Jobs</p>
          </a></li>
        <li><a href="managejobs1.php" id="select">
            <p>Manage Jobs</p>
          </a></li>
        <li><a href="Viewusers1.php" id="select">
            <p>View Users</p>
          </a></li>
        <li><a href="viewjobs1.php" id="select">
            <p>Manage Users</p>
          </a></li>
        <li><a href="updatestock1.php" id="select">
            <p>Update Stock Data</p>
          </a></li>
      </ul>
    </nav>
  </header>
  <div id="full-container">
    <section id="page-top-2">
      <h4 class="sub-header white-txt">Stock Management System</h4>
      <br>
      <p class="white-txt center">Welcome</p>
    </section>

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
          echo "<option value='$status'>$status</option>";
        }
        ?>
      </select>

      <label for="start_date">Start Date:</label>
      <input type="date" id="start_date">

      <label for="end_date">End Date:(Exclusive)</label>
      <input type="date" id="end_date">

      <button id="fill" onclick="applyFilters()">Apply</button>
    </div>

    <div id="output">
      <?php
      if ($conn) {
        $query = "SELECT * FROM jobs ORDER BY LastUpdated DESC";
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
                    <td class='project-manager'>{$record['projectmanager']}</td>
                    <td class='fitter'>{$record['allocatedfitter']}</td>
                    <td class='status'>{$record['currentstate']}</td>
                    <td class='last-updated'>{$record['LastUpdated']}</td>
                  </tr>";
          }

          echo "</tbody>
                </table></center>";
        } else {
          echo "No records found.";
        }

        mysqli_close($conn);
      } else {
        echo "Error connecting to the database.";
      }
      ?>
    </div>

    <script>
      function applyFilters() {
        var projectManager = document.getElementById("project_manager").value;
        var fitter = document.getElementById("fitter").value;
        var status = document.getElementById("status").value;
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;

        var rows = document.getElementById("lbt").getElementsByTagName("tbody")[0].getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
          var row = rows[i];
          var projectManagerCell = row.getElementsByClassName("project-manager")[0];
          var fitterCell = row.getElementsByClassName("fitter")[0];
          var statusCell = row.getElementsByClassName("status")[0];
          var lastUpdatedCell = row.getElementsByClassName("last-updated")[0];

          var hideRow = false;

          if (projectManager && projectManager !== projectManagerCell.innerHTML) {
            hideRow = true;
          }

          if (fitter && fitter !== fitterCell.innerHTML) {
            hideRow = true;
          }

          if (status && status !== statusCell.innerHTML) {
            hideRow = true;
          }

          if (startDate && startDate > lastUpdatedCell.innerHTML) {
            hideRow = true;
          }

          if (endDate && endDate <= lastUpdatedCell.innerHTML) {
            hideRow = true;
          }

          if (hideRow) {
            row.style.display = "none";
          } else {
            row.style.display = "";
          }
        }
      }
    </script>
  </div>
</body>
</html>
