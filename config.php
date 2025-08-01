
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'sql306.infinityfree.com'; // MySQL Hostname
$dbname = 'if0_38681044_file_management';      // Replace XXX with your actual database name
$user = 'if0_38681044';            // MySQL Username
$pass = 'dE1Q2NYFfZa5Y';           // MySQL Password (replace with your real password)

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
