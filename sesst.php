<?php
session_start();

if (isset($_SESSION['jobid'])) {
    // Session variable 'jobid' exists
    $jobid = $_SESSION['jobid'];
    echo "Job ID: " . $jobid;
} else {
    // Session variable 'jobid' does not exist
    echo "Job ID not found.";
}
?>
