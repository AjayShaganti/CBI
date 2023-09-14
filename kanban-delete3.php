<?php

error_reporting(E_ALL & ~E_NOTICE); // Disable notices
session_start();
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
      <label for="tab-1" class="tab-label">HYMOD & TOP HAT</label>
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
      <input type="radio" name="css-tabs" id="tab-2" class="tab-switch" checked>
      <label for="tab-2" class="tab-label">KANBAN STOCK</label>
      <div id="tab2" class="tab-content">
<?php
include 'connection.php';
$flag=0;

$msg = ""; // Initialize the message variable
$flag=0;

$binlocation = $_SESSION['binlocation'];

$query = "DELETE FROM stock WHERE BinLocation = ?";

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

// Bind the parameter
mysqli_stmt_bind_param($stmt, "s", $binlocation);

// Execute the query or throw an exception
try {
    if (mysqli_stmt_execute($stmt)) {
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        if ($affectedRows > 0) {
           $msg= "Data related to ".$binlocation." deleted successfully";
            $flag = 1;
        } else {
            // No matching records found
            $flag = 0;
			$msg= "Something went wrong please try again. If problem persists contact admin";
        }
    } else {
        throw new Exception(mysqli_error($conn));
    }
} catch (Exception $e) {
    $msg = "Error: " . $e->getMessage();
}

// Close the statement
mysqli_stmt_close($stmt);



								if($flag){
									echo "	<div class=\"form-cube\"> 
								<h2>".$msg."</h2><br>
								<form method=\"post\" id=\"myForm\" action=\"kanban-delete2.php\">
									<label> Bin Location: </label>
									<div class=\"input-field\" id=\"idFld1\"> 
									<input type=\"text\" id=\"binloaction\" length=\"5\" name=\"binlocation\" required></div>
									<button id=\"fill\" class=\"signinBttn\" type=\"submit\" value=\"submit\" >Fetch Data</button>
								</form></div>";
								}
								else {
									echo "	<div class=\"form-cube\"> 
								<h2>".$msg."</h2><br>
								</div>";
								}
									
?>
</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-3" class="tab-switch">
      <label for="tab-3" class="tab-label">CABLES</label>
      <div id="tab3" class="tab-content">
<?php

				
								echo "
												
								<center><form method=\"post\" id=\"myForm\">
									<h1> Manage Cables Data</h1>
											
							<br><br><a href=\"cables-add1.php\" id=\"no-fill\" class=\"manageusersadd\"><h2>ADD</h2></a><br><br>

							<a href=\"cables-delete1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>DELETE</h2></a><br><br>
							<a href=\"cables-edit1.php\" id=\"no-fill\" class=\"manageuserssubtract\"><h2>UPDATE</h2></a>
							  
								</form></center>";
?>
	</div>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-4" class="tab-switch">
      <label for="tab-4" class="tab-label">CONSUMABLES</label>
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
      <label for="tab-5" class="tab-label">LABELS</label>
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
<script>

</script>
<?php include 'loading.php'; ?>