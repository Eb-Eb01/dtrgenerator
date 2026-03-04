<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection
$conn = new mysqli("localhost", "root", "", "api_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Path of local Excel file
$filePath = "uploads/attendance.xlsx";

// Load Excel file
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

foreach ($rows as $index => $row) {

    // Skip header row
    if ($index == 0) continue;

    $firstname   = $row[0];
    $lastname = $row[1];
    $department = $row[2];
    $date = date('Y-m-d', strtotime($row[3]));
    $time = date('H:i:s', strtotime($row[4]));

    $stmt = $conn->prepare("INSERT INTO attendance (firstname, lastname, department, date, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $department, $date, $time);
    $stmt->execute();
}

echo "DTR Imported Successfully!";
header("Location:Dashboard/DTR.php");

?>