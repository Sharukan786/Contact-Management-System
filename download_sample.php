<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="sample_contacts_template.csv"');

// Output CSV content
$output = fopen('php://output', 'w');

// Add headers
fputcsv($output, ['name', 'phone', 'email', 'address', 'category']);

// Add sample rows
fputcsv($output, ['John Doe', '9876543210', 'john@example.com', '123 Main Street', 'Friend']);
fputcsv($output, ['Jane Smith', '9123456789', 'jane@example.com', '456 Elm Avenue', 'Family']);

fclose($output);
exit;
?>
