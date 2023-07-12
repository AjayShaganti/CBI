<?php include 'common.php'; ?><style>
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
#cables{
    width:30px;
    heigth: 30px;
    text-decoration:none;
   font-size:15px;
   font-colour:Red;
margin-bottom:-5px;
}

    </style>
<?php
include 'connection.php';

if($conn) 
	{
	$query="select * from cables order by CAST(REGEXP_REPLACE(BinLocation, '[^0-9]', '') AS UNSIGNED)";
	
	$result=mysqli_query($conn,$query);
		if($result)
		{
			$record=mysqli_fetch_assoc($result);
			{if($record)
				{
				echo"<a id=\"cables\" href=\"Stock.php\"><--Back</a><center><h2 id=\"lbh\" >Available Stock</h2><br><br><br>";
				echo"<table id=\"lbt\" border='2'><tr><th>Bin Location</th><th>Description</th><th>Part Number</th><th>Reel Length(Mts)</th><th>Max Stock (No's)</th><th>Min Stock (No's)</th><th>Reorder Quantity(No's)</th><th>Reorder Quantity(Mts)</th><th>Last Updated</tr>";
				while($record)
					{echo" <tr>
						<td>{$record['BinLocation']}</td>
						<td>{$record['PartName']}</td>
						<td>{$record['PartNo']}</td>
						<td>{$record['Length']}</td>
						<td>{$record['MaxStock']}</td>
						<td>{$record['MinStock']}</td>
						<td>{$record['ReorderQty1']}</td>
						<td>{$record['ReorderQty2']}</td>
						<td>{$record['LastUpdated']}</td>
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
        </div>
    </body>
</html>