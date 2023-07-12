<?php
session_start();
include 'common.php';
?>

<style>
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
</style>

<?php include 'loading.php'; ?>

<?php
include 'connection.php';
$salesorder = $_POST['salesorder'];

$salesorder = $_POST['salesorder'];
$_SESSION['salesorder'] = $salesorder;

$query = "SELECT distinct name FROM managers order by name asc";
$result = mysqli_query($conn, $query);
$options1 = "<option value=''>Select</option>";
while ($row = mysqli_fetch_assoc($result)) {
    $columnValue = $row['name'];
    $options1 .= "<option value='$columnValue'>$columnValue</option>";
}

$query = "SELECT distinct fittername FROM fitters order by fittername asc";
$result = mysqli_query($conn, $query);
$options2 = "<option value=''>Select</option>";
while ($row = mysqli_fetch_assoc($result)) {
    $columnValue = $row['fittername'];
    $options2 .= "<option value=\"$columnValue\">$columnValue</option>";
}

$query = "SELECT distinct type FROM jobs order by type asc";
$result = mysqli_query($conn, $query);
$options3 = "<option value=''>Select</option>";
while ($row = mysqli_fetch_assoc($result)) {
    $columnValue = $row['type'];
    $options3 .= "<option value=\"$columnValue\">$columnValue</option>";
}

echo "<div class=\"form-cube1\">";
$query = "SELECT * FROM jobs WHERE jobid='$salesorder'";
$result = mysqli_query($conn, $query);
if ($result) {
    $record = mysqli_fetch_assoc($result);
    if ($record) {
        echo " <form method=\"post\" action=\"managejobs4-edit.php\">
                   <h3><strong>Sales Order: </strong>" . $record['jobid'] . "</h3>
                   <h3><strong>Project Manager: </strong>" . $record['projectmanager'] . "</h3>
                   
                   <h3><strong>Status: </strong>" . $record['currentstate'] . "</h3>
		   <h3><strong>Type: </strong>" . $record['type'] . "</h3>
                   <h3><strong>Last Updated: </strong>" . $record['LastUpdated'] . "</h3><br><br>
                   <h4>Only change the field that you want to update</h4>
                   
                   <div id=\"formfeild\" class=\"input-container\">
                       <label for=\"Salesorder\">Sales Order:</label>
                       <div class=\"input-field\" id=\"idFld\">
                           <input type=\"text\" name=\"salesorder\" id=\"salesorder\" maxlength=\"7\" placeholder=\"Sales order\">
                       </div>

                       <label for=\"status\">Project Manager:</label>
                       <select name=\"projectmanager\">"
                           . $options1 .
                       "</select>";

        // Check if multiple fitters are assigned to the same job
        $fittersQuery = "SELECT DISTINCT allocatedfitter FROM jobs WHERE jobid='$salesorder'";
        $fittersResult = mysqli_query($conn, $fittersQuery);

        if (mysqli_num_rows($fittersResult) >1) {
            echo "<label for=\"existingfitters\">Existing Fitters:</label>";
            echo "<select name=\"existingfitters\">";

            while ($fitterRow = mysqli_fetch_assoc($fittersResult)) {
                $fitterName = $fitterRow['allocatedfitter'];
                echo "<option value=\"$fitterName\">$fitterName</option>";
            }

            echo "</select>";
        }

		echo "<label for=\"fitter\">New Fitter:</label>
		<select name=\"fitter\">"
			. $options2 .
			"</select>";
		
		
        echo "<label for=\"status\">Status:</label>
              <select id=\"Status\" name=\"Status\">
                  <option value=\"\">Select</option>
                  <option value=\"NotAssigned\">Not Assigned</option>
                  <option value=\"InProgress\">In Progress</option>
                  <option value=\"Assigned\">Assigned</option>
                  <option value=\"Completed\">Completed</option>
                  <option value=\"Tested\">Tested</option>
              </select>
              
              <label for=\"type\">Type:</label>
      <select id=\"Type\" name=\"type\"><option value=\"\">Select</option><option value=\"HYMOD\">HYMOD</option><option value=\"ADVD\">ADVD</option><option value=\"ADVD+\">ADVD+</option><option value=\"GMP\">GMP</option><option value=\"HPR\">HPR</option><option value=\"HPS\">HPS</option>
      </select>
           </div>

           <div id=\"float-right\">
               <button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\">Update</button>
           </div>
       </form>";
    } else {
        echo " <form method=\"post\" action=\"managejobs3-edit.php\">
                <h4>Sales order: " . $salesorder . " doesn't exist</h4>
                <h2>Update a job</h2>
                <div id=\"formfeild\" class=\"input-container\">
                    <label for=\"Salesorder\">Sales Order:</label>
                    <div class=\"input-field\" id=\"idFld\">
                        <input type=\"text\" name=\"salesorder\" id=\"salesorder\" maxlength=\"7\" required=\"required\" placeholder=\"Sales order\">
                    </div>

                    <div id=\"float-right\">
                        <button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\">Update</button>
                    </div>
                </div>
            </form>";
    }
} else {
    echo "<h4>Something went wrong. Please refresh the page.</h4>";
}

// Close the database connection
mysqli_close($conn);
?>

<?php include 'loading.php'; ?>
</body>
