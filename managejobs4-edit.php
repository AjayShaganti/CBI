<?php
 session_start(); 
include 'common.php'; 
   
	$salesorder = $_SESSION['salesorder'];

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
</style>    <?php include 'loading.php'; ?>

    <?php
    include 'connection.php';
    $newsalesorder = $_POST['salesorder'];
    $projectmanager = $_POST['projectmanager'];
	$oldfitter=$_POST['existingfitters'];
    $newfitter = $_POST['fitter'];
    $status = $_POST['Status'];
    $type = $_POST['type'];
    $flag=1;

    if (!empty($newsalesorder) || !empty($projectmanager) || !empty($newfitter) || !empty($status) || !empty($type)) {
        // Construct the update query
        $updateQuery = "UPDATE jobs SET";
        $updateData = array();

        // Add the columns to the update query if they have data
        if (!empty($newsalesorder)) {
            $updateData[] = "jobid = '$newsalesorder'";
        }
        if (!empty($projectmanager)) {
            $updateData[] = "projectmanager = '$projectmanager'";
        }
        if (!empty($newfitter)) {
            $updateData[] = "allocatedfitter = '$newfitter'";
        }
        if (!empty($status)) {
            $updateData[] = "currentstate = '$status'";
        }
        if (!empty($type)) {
            $updateData[] = "type = '$type'";
        }


        // Check if any columns have data to update
        if (!empty($updateData)) {
            $updateQuery .= " " . implode(", ", $updateData);

            // Add the WHERE clause to specify the sales order to update
            $updateQuery .= " WHERE jobid = '$salesorder'";
			if(!empty($oldfitter))
			{
				  $updateQuery .= " and allocatedfitter='$oldfitter'";
			}

            try {
                $result = mysqli_query($conn, $updateQuery);

                if ($result) {
                    $msg = "Job $salesorder updated successfully.";
                } else {
                    if (mysqli_errno($conn) == 1062) {
                        $msg = "Duplicate entry for jobid. Please choose a different jobid.";
                    } else {
                        $msg = "Failed to update job $newsalesorder.";
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $msg = $e->getMessage();
                $flag = 0;
            }
        } else {
            $msg = "No columns provided for update.";
        }
    }

    $query = "SELECT * FROM jobs WHERE";
    $condition = array();
    if (empty($newsalesorder)) {
        $condition[] = "jobid = '$salesorder'";
    } else {
        $condition[] = "jobid = '$newsalesorder'";
    }
    $query .= " " . implode(" AND ", $condition);

    echo "<div class=\"form-cube1\">";
    echo "<form method=\"post\" action=\"managejobs3-edit.php\">";
    echo "<h4>" . $msg . "</h4><br>";

    if($flag){
        $result = mysqli_query($conn, $query);
        if ($result) {
            $record = mysqli_fetch_assoc($result);
            if ($record) {
                echo "<h2>After Updation</h2><br><h3><strong>Sales Order: </strong>" . $record['jobid'] . "</h3>";
                echo "<h3><strong>Project Manager: </strong>" . $record['projectmanager'] . "</h3>";
                //echo "<h3><strong>Fitter: </strong>" . $record['allocatedfitter'] . "</h3>";
                echo "<h3><strong>Status: </strong>" . $record['currentstate'] . "</h3>";
                echo "<h3><strong>Type: </strong>" . $record['type'] . "</h3>";
                echo "<h3><strong>LastUpdated: </strong>" . $record['LastUpdated'] . "</h3><br><br>";
                echo "<form method=\"post\" action=\"managejobs3-edit.php\">";
                echo "<h2>Update a job</h2>";
                echo "<div id=\"formfeild\" class=\"input-container\">";
                echo "<label for=\"Salesorder\">Sales Order:</label>";
                echo "<div class=\"input-field\" id=\"idFld\">";
                echo "<input type=\"text\" name=\"salesorder\" id=\"salesorder\" maxlength=\"7\" required=\"required\" placeholder=\"Sales order\">";
                echo "</div>";
                echo "<div id=\"float-right\">";
                echo "<button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\">Update</button>";
                echo "</div>";
                echo "</div>";
                echo "</form>";
            }
        } else {
            echo "<h4>Something went wrong. Please refresh the page.</h4>";
        }
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</div><?php include 'loading.php'; ?>
</body>
</html>
