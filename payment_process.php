<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'Hotel_DB';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['total_price'];

    // Get current date/time
    $payment_date = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    $payment_query = "INSERT INTO Payment (BookingID, PaymentMethod, Amount, PaymentDate) VALUES (?, ?, ?, ?)";
    $payment_stmt = $conn->prepare($payment_query);
    $payment_stmt->bind_param("isds", $booking_id, $payment_method, $amount, $payment_date);

    if ($payment_stmt->execute()) {
        echo "Payment inserted successfully.";
    } else {
        echo "MySQL Error: " . $payment_stmt->error;
    }

    $payment_stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>