<?php include 'common.php'; ?>    <style>
        .update1 {
            margin-left: 10%;
        }
        h4 {
            color: red;
        }
        form {
            width: 60%;
            margin: 0 auto;
            position: relative;
            top: -130px;
            left: 0px;
        }
        .form-cube1 {
            background: #FFFCF9;
            width: 30%;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            position: relative;
            top: 250px;
            left: 450px;
        }
    </style>    <?php include 'loading.php'; ?>
    <?php
    include 'connection.php';
    session_start();
    $salesorder = $_POST['salesorder'];
    $projectmanager = $_POST['projectmanager'];
    $fitter = $_POST['fitter'];
    $status = $_POST['Status'];
    $type = $_POST['type'];
	$msg='';

// ...

try {
    if ($conn) {
        $updateQuery = "INSERT INTO `jobs`(`jobid`, `projectmanager`, `type`,`allocatedfitter`, `currentstate`, `LastUpdated`) 
                        VALUES ('$salesorder', '$projectmanager','$type', '$fitter', '$status', '$currentDateTime')";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            $msg = "<h3>Job " . $salesorder . " added successfully</h3>";
        } else {
            throw new Exception("<h3>Failed to add job</h3>");
        }
    } else {
        throw new Exception("<h3>Connection failed: " . mysqli_connect_error(). "</h3>");
    }
} catch (Exception $e) {
    $msg = " <h4>Error: " . $e->getMessage(). "</h4><h5> If you are trying to update this job <a href=\"http://3.231.173.240/CBi/managejobs2-edit.php\">Clickme</a></h4>";
}
 

// ...



$query = "SELECT distinct name FROM managers ORDER BY name ASC";
$result = mysqli_query($conn, $query);
$options1 = '';
while ($row = mysqli_fetch_assoc($result)) {
  $columnValue = $row['name'];
  $options1 .= "<option value='$columnValue'>$columnValue</option>";
}

$query = "SELECT distinct fittername FROM fitters ORDER BY fittername ASC";
$result = mysqli_query($conn, $query);
$options2 = '<option value=\"NotAssigned\">Not Assigned</option>';
while ($row = mysqli_fetch_assoc($result)) {
  $columnValue = $row['fittername'];
  $options2 .= "<option value=\"$columnValue\">$columnValue</option>";
}

$query = "SELECT distinct type FROM jobs ORDER BY type ASC";
$result = mysqli_query($conn, $query);
$options3 = '';
while ($row = mysqli_fetch_assoc($result)) {
  $columnValue = $row['type'];
  $options3 .= "<option value=\"$columnValue\">$columnValue</option>";
}


	echo "<div class=\"form-cube1\">
        <form method=\"post\" action=\"managejobs3-add.php\">
		$msg<br>
		
    <div id=\"formfeild\" class=\"input-container\">
      <label for=\"Salesorder\">Sales Order:</label>
      <div class=\"input-field\" id=\"idFld\">
        <input type=\"text\" name=\"salesorder\" id=\"salesorder\" maxlength=\"7\" required=\"required\" placeholder=\"Sales order\">
      </div>
      
      <label for=\"status\">Project Manager:</label>
      <select name=\"projectmanager\" required>"
        .$options1."
      </select>

      <label for=\"status\">Fitter Name:</label>
      <select name=\"fitter\" required>"
        .$options2."
      </select>
      
      <label for=\"status\">Status:</label>
      <select id=\"Status\" name=\"Status\">
		<option value=\"NotAssigned\">Not Assigned</option>
        <option value=\"InProgress\">In Progress</option>
        <option value=\"Assigned\">Assigned</option>
        <option value=\"Completed\">Completed</option>
        <option value=\"Tested\">Tested</option>
      </select>

      <label for=\"status\">Type:</label>
      <select id=\"Type\" name=\"type\"><option value=\"HYMOD\">HYMOD</option><option value=\"ADVD\">ADVD</option><option value=\"ADVD+\">ADVD+</option><option value=\"GMP\">GMP</option><option value=\"HPR\">HPR</option><option value=\"HPS\">HPS</option>
       
      </select>
    </div>


    <div id=\"float-right\">
      <button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\">Add</button>
    </div>
  </form>
</div>";

    // Close the database connection
    mysqli_close($conn);
    ?>
</div>
<?php include 'loading.php'; ?></body>
</html>
