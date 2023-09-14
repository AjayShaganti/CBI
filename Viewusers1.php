<?php include 'common.php'; ?>
<style>
      table {
        width: 80%;
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
    </style>
<?php
include 'connection.php';

if($conn) 
	{
	$query="select * from fitters ";
	
	$result=mysqli_query($conn,$query);
		if($result)
		{
			$record=mysqli_fetch_assoc($result);
			{if($record)
				{
				echo"<center><h2 id=\"lbh\" >USERS/FITTERS</h2><br><br><br>";
				echo"<table id=\"lbt\" border='2'><tr><th>Fitter Name</th><th>Password</th><th>Kanban/Consumable/Lables</th><th>Cables</th><th>HYMOD/TopHat</th><th>Last Updated</tr>";
				while($record)
					{  if($record['kanban']){$a1='Allowed';}else{$a1='Not Allowed';}
						if($record['cables']){$a2='Allowed';}else{$a2='Not Allowed';}
						if($record['hmtp']){$a3='Allowed';}else{$a3='Not Allowed';}
						
						
						echo" <tr>
						<td>{$record['fittername']}</td>
						<td>{$record['password']}</td>
						<td>".$a1."</td>
						<td>".$a2."</td>
						<td>".$a3."</td>
						<td>{$record['lastupdated']}</td>
						</tr>";

					 $record=mysqli_fetch_assoc($result);
					 }
					 echo"</table><center>";
				}
			}
		}
	}

mysqli_close($conn);

?>
<?php include 'loading.php'; ?>
        </div>
    </body>
</html>