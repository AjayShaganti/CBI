<?php
// Define the database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "cbi";

// Define the root backup directory
$backupRootDirectory = '/opt/lampp/htdocs/CBi/Backup';

// Generate the subdirectory name with today's timestamp
$todayDirectory = date('Ymd');

// Define the output directory with the subdirectory path
$outputDirectory = $backupRootDirectory . '/' . $todayDirectory;

// Append a number to the directory name if it already exists
$counter = 1;
$uniqueDirectory = $outputDirectory;
while (is_dir($uniqueDirectory)) {
    $uniqueDirectory = $outputDirectory . '_' . $counter;
    $counter++;
}

// Ensure the output directory exists
if (!is_dir($uniqueDirectory)) {
    // Create the output directory if it doesn't exist
    mkdir($uniqueDirectory, 0777, true);

    // Set the permissions for the directory and its contents
    chmod($uniqueDirectory, 0777);
}

try {
    // Establish a database connection
    $connection = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($connection->connect_error) {
        throw new Exception("Connection failed: " . $connection->connect_error);
    }

    // Get the list of tables in the database
    $tablesQuery = "SHOW TABLES";
    $tablesResult = $connection->query($tablesQuery);
echo "\n ---------------------------------------------------------------------------------------------------------------------------------------------\n";
		echo "\nLogs for: $todayDirectory\n";
    // Loop through each table
    while ($tableRow = $tablesResult->fetch_row()) {
        $tableName = $tableRow[0];

        // Generate a unique filename for the CSV file with timestamp
        $filename = $uniqueDirectory . '/' . $tableName . '.csv';

        // Execute the SELECT ... INTO OUTFILE statement to export the table data
        $exportQuery = "SELECT * INTO OUTFILE '$filename' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM $tableName";
        $exportResult = $connection->query($exportQuery);

        // Check if the export query executed successfully
        if (!$exportResult) {
            throw new Exception("Error exporting table: $tableName");
        }
        
        echo "Table exported successfully: $tableName\n";
    }

    // Close the database connection
    $connection->close();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit;
}
?>
