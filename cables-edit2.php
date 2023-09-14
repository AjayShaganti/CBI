<?php
include 'common.php';
?>
<style>
* {
  box-sizing: border-box;
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

.tabs::before,
.tabs::after {
  content: "";
  display: table;
}

.tabs::after {
  clear: both;
}
#tab1 {
	//margin-left:0%;
	width:100%;	
}

#tab2 {
	margin-left:-11.45%;
	width:100%;
}
#tab3 {
	margin-left:-21.55%;
width:100%;
}
#tab4 {
	margin-left:-28.0%;
width:100%;
}
#tab5 {
	margin-left:-37.8%;
width:100%;
}
#lbt{
	padding-top: 0px;

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

  .tab-content {
    position:absolute;
    margin-top: 3%;
    padding: 1.618rem;
    background: #fff;
    color: #2c3e50;
    opacity: 0;
    transition: all 0.35s;
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

.form-cube {
	width:40%;
	//position:relative;
	
}

label {
	font-weight:bold;

}
#rad1 {
	padding-top:4%;
	margin-bottom:-12.35%;
	margin-left:-20%;
	//z-index:10;
}

#rad2 {
	//padding-top:2%;
	margin-top:-8.35%;
	margin-left:20%;
	//z-index:10;
}
.success {
  background-color: lightgreen;
}

.error {
  background-color: lightcoral;
}
.form-cube2 {
	display:none;
    width: 90%;
    max-width: 500px;
    position: relative;
    //background: #FFFCF9;
    margin: 2em auto;
    padding: 40px 60px 110px;
    border-radius: 20px;
    text-align: center;
    //box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
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
    position: relative;
    height: auto;
    margin-left: 5;
    padding: 1em;
  }
}
</style>
<div class="wrapper">
  <div class="tabs">
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-1"  class="tab-switch">
      <label for="tab-1" class="tab-label" onclick="act('tab1')">HYMOD & TOP HAT</label>
      <div id="tab1" class="tab-content">
<?php

				
								echo "
												
								<center><form method=\"post\" id=\"myForm\">
								<h1> Manage HYMOD/Top-Hat Data</h1>
											
							<br><br><a href=\"hmtp-add1.php\" id=\"no-fill\" class=\"manageusersadd\"><h2>ADD</h2></a><br><br>

							<a href=\"hmtp-delete1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>DELETE</h2></a><br><br>
							<a href=\"hmtp-edit1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>UPDATE</h2></a>
							  
								</form></center>";
?>
</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-2" class="tab-switch" >
      <label for="tab-2" class="tab-label" onclick="act('tab2')">KANBAN STOCK</label>
      <div id="tab2" class="tab-content">
<?php
								echo "
												
								<center><form method=\"post\" id=\"myForm\">
								<h1> Manage Kanban Data</h1>
											
							<br><br><a href=\"kanban-add1.php\" id=\"no-fill\" class=\"manageusersadd\"><h2>ADD</h2></a><br><br>

							<a href=\"kanban-delete1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>DELETE</h2></a><br><br>
							<a href=\"kanban-edit1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>UPDATE</h2></a>
							  
								</form></center>";
?>
</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-3" class="tab-switch" checked>
      <label for="tab-3" class="tab-label" onclick="act('tab3')">CABLES</label>
      <div id="tab3" class="tab-content">
        <?php
        include 'connection.php';

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $msg = ""; // Initialize the message variable
        $flag = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle the form submission
            // Retrieve the values from the form
            $binlocation = $_POST['binlocation'];

            $query = "SELECT * FROM cables WHERE BinLocation = ?";

            // Prepare the statement
            $stmt = mysqli_prepare($conn, $query);

            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "s", $binlocation);

            // Execute the query or throw an exception
            try {
                if (mysqli_stmt_execute($stmt)) {
                    // Fetch the data
                    $result = mysqli_stmt_get_result($stmt);

                    // Check if any rows were returned
                    if ($row = mysqli_fetch_assoc($result)) {
                        // Output the fetched data
                        $flag = 1;
                    } else {
                        $msg = "No data found for " . $binlocation . ".";
                        $flag = 0;
                    }
                } else {
                    throw new Exception(mysqli_error($conn));
                    $flag = 0;
                }
            } catch (Exception $e) {
                $msg = "Error: " . $e->getMessage();
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }

        if ($flag) {
            echo "<center><form method=\"post\" id=\"myForm\" action=\"\">";
            echo "<h3>" . $msg . "</h3>";
			echo "<table id=\"myTable\" style=\"width: 90% !important;\">";
            echo "<tr>";
            echo "<th>Bin Location</th>";
            echo "<th>Part Name</th>";
            echo "<th>Part No</th>";
            echo "<th>Length (Mts)</th>";
            echo "<th>Max Stock(No's)</th>";
            echo "<th>Min Stock(No's)</th>";
            echo "<th>Reorder Qty(No's)</th>";
            echo "<th>Reorder Qty(Mts)</th>";
            echo "<th>Last Updated</th>";
            echo "<th>Category</th>";
            echo "</tr>";
            echo "<tr>";
            $formattedTimestamp = date('d/m/y H:i', strtotime($row['LastUpdated']));
            if ($row) {
                echo "<td contenteditable='true' data-column='BinLocation'>" . $row['BinLocation'] . "</td>";
                echo "<td contenteditable='true' data-column='PartName'>" . $row['PartName'] . "</td>";
                echo "<td contenteditable='true' data-column='PartNo'>" . $row['PartNo'] . "</td>";
                echo "<td contenteditable='true' data-column='Length'>" . $row['Length'] . "</td>";
                echo "<td contenteditable='true' data-column='MaxStock'>" . $row['MaxStock'] . "</td>";
                echo "<td contenteditable='true' data-column='MinStock'>" . $row['MinStock'] . "</td>";
                echo "<td contenteditable='false' data-column='ReorderQty1'>" . $row['ReorderQty1'] . "</td>";
                echo "<td contenteditable='false' data-column='ReorderQty2'>" . $row['ReorderQty2'] . "</td>";
                echo "<td contenteditable='false' data-column='LastUpdated'>" . $formattedTimestamp . "</td>";
                echo "<td contenteditable='false' data-column='Category'>" . $row['Category'] . "</td>";
            } else {
                echo "<td colspan=\"10\">No data found.</td>";
            }
            echo "</tr>";
            echo "</table>";
            echo "<input type='hidden' name='row[BinLocation]' value='" . ($row ? $row['BinLocation'] : "") . "'>";
            echo "<input type='hidden' name='row[PartName]' value='" . ($row ? $row['PartName'] : "") . "'>";
            echo "<input type='hidden' name='row[PartNo]' value='" . ($row ? $row['PartNo'] : "") . "'>";
            echo "<input type='hidden' name='row[Length]' value='" . ($row ? $row['Length'] : "") . "'>";
            echo "<input type='hidden' name='row[MaxStock]' value='" . ($row ? $row['MaxStock'] : "") . "'>";
            echo "<input type='hidden' name='row[MinStock]' value='" . ($row ? $row['MinStock'] : "") . "'>";
            echo "<input type='hidden' name='row[ReorderQty1]' value='" . ($row ? $row['ReorderQty1'] : "") . "'>";
            echo "<input type='hidden' name='row[ReorderQty2]' value='" . ($row ? $row['ReorderQty2'] : "") . "'>";
            echo "<input type='hidden' name='row[LastUpdated]' value='" . ($row ? $row['LastUpdated'] : "") . "'>";
            echo "<input type='hidden' name='row[Category]' value='" . ($row ? $row['Category'] : "") . "'>";
            echo "</form><br><br><div id=\"message\"></div></center>";
            echo "<div id=\"newbin\" class=\"form-cube2\">";
            echo "<form method=\"post\" id=\"myForm\" action=\"cables-edit2.php\"><h3> Update Other bin </h3><br>";
            echo "<label> Bin Location: </label>";
            echo "<div class=\"input-field\" id=\"idFld1\">";
            echo "<input type=\"text\" id=\"binlocation\" length=\"5\" name=\"binlocation\" required>";
            echo "</div>";
            echo "<button id=\"fill\" class=\"signinBttn\" type=\"submit\">Fetch Data</button>";
            echo "</form></div>";
        } else {
            echo "<div class=\"form-cube\">";
            echo "<h2>" . $msg . "</h2><br>";
            echo "<form method=\"post\" id=\"myForm\" action=\"cables-edit2.php\">";
            echo "<label> Bin Location: </label>";
            echo "<div class=\"input-field\" id=\"idFld1\">";
            echo "<input type=\"text\" id=\"binlocation\" length=\"5\" name=\"binlocation\" required>";
            echo "</div>";
            echo "<button id=\"fill\" class=\"signinBttn\" type=\"submit\">Fetch Data</button>";
            echo "</form></div>";
        }
        ?>
	</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-4" class="tab-switch">
      <label for="tab-4" class="tab-label" onclick="act('tab4')">CONSUMABLES</label>
      <div id="tab4" class="tab-content">
<?php

				
								echo "
												
								<center><form method=\"post\" id=\"myForm\">
									<h1> Manage Consumables Data</h1>
											
							<br><br><a href=\"consumables-add1.php\" id=\"no-fill\" class=\"manageusersadd\"><h2>ADD</h2></a><br><br>

							<a href=\"consumables-delete1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>DELETE</h2></a><br><br>
							<a href=\"consumables-edit1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>UPDATE</h2></a>
							  
								</form></center>";
?>
	</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-5" class="tab-switch">
      <label for="tab-5" class="tab-label" onclick="act('tab5')">LABELS</label>
      <div id="tab5" class="tab-content">
<?php

				
								echo "
												
								<center><form method=\"post\" id=\"myForm\">
									<h1> Manage Labels Data</h1>
											
							<br><br><a href=\"labels-add1.php\" id=\"no-fill\" class=\"manageusersadd\"><h2>ADD</h2></a><br><br>

							<a href=\"labels-delete1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>DELETE</h2></a><br><br>
							<a href=\"labels-edit1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>UPDATE</h2></a>
							  
								</form></center>";
?>
	</div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
     window.onload = function() {
        act('tab3'); // Apply the initial filter
		
    };
function act(tab) {
  var elements = document.getElementsByClassName("tab-content");
  var elementsArray = Array.from(elements);
  for (var i = 0; i < elementsArray.length; i++) {
	  if(elementsArray[i].id===tab){
		elementsArray[i].style.display = "block";
		console.log("show"+elementsArray[i].id);
	  }
	  else{
		  
		  elementsArray[i].style.display = "none";
		  console.log("Hide"+elementsArray[i].id);
	  }
  }
}

$(document).ready(function() {
  $('td[contenteditable="true"]').blur(function() {
    var column = $(this).data('column');
    var value = $(this).text();
    var binlocation = '<?php echo $binlocation; ?>';
    var myDiv = document.getElementById("newbin");
    var cell = $(this);
    var messageDiv = $('#message');


    $.ajax({
      url: 'cables-edit3.php',
      method: 'POST',
      data: {
        column: column,
        value: value,
        binlocation: binlocation
      },
      success: function(response) {
        // Handle the response
        if (response.success) {
          // Data update was successful
          cell.addClass('success');
          cell.removeClass('error');
          messageDiv.html("Successfully updated column: " + column + " To " + value);
          console.log('Data updated successfully');
          myDiv.style.display = "block";
        } else {
          // Data update failed
          cell.addClass('error');
          cell.removeClass('success');
          messageDiv.html(value+"Failed to update column: " + column + " " + response.message);
          console.log('Data update failed');
          myDiv.style.display = "none";
        }
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  });
});

</script>
<?php include 'loading.php'; ?>