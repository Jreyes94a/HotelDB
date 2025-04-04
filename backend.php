<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'HotelDB';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Booking Information
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_type = $_POST['room_type'];
    $number_of_guests = intval($_POST['number_of_guests']);

    // Payment Information
    $payment_method = $_POST['payment_method'];

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($check_in) || empty($check_out) || empty($room_type) || empty($number_of_guests) || empty($payment_method)) {
        echo "Error: Missing required fields.";
        $conn->close();
        exit;
    }

    // Get Room ID
    $room_id_query = "SELECT RoomID FROM Room JOIN RoomType ON Room.TypeID = RoomType.TypeID WHERE RoomType.Name = ?";
    $room_id_stmt = $conn->prepare($room_id_query);
    $room_id_stmt->bind_param("s", $room_type);
    $room_id_stmt->execute();
    $room_id_result = $room_id_stmt->get_result();
    if($room_id_row = $room_id_result->fetch_assoc()){
        $room_id = $room_id_row['RoomID'];
    } else {
        echo "Error: Room type not found.";
        $room_id_stmt->close();
        $conn->close();
        exit;
    }
    $room_id_stmt->close();

    // Get Price Per Night
    $price_per_night_query = "SELECT BasePrice FROM RoomType WHERE Name = ?";
    $price_per_night_stmt = $conn->prepare($price_per_night_query);
    $price_per_night_stmt->bind_param("s", $room_type);
    $price_per_night_stmt->execute();
    $price_per_night_result = $price_per_night_stmt->get_result();
    if($price_per_night_row = $price_per_night_result->fetch_assoc()){
        $price_per_night = $price_per_night_row['BasePrice'];
    } else {
        echo "Error: Price not found for room type.";
        $price_per_night_stmt->close();
        $conn->close();
        exit;
    }
    $price_per_night_stmt->close();

    // Calculate Total Price
    $checkin_date = new DateTime($check_in);
    $checkout_date = new DateTime($check_out);
    $interval = $checkin_date->diff($checkout_date);
    $number_of_nights = $interval->days;
    $total_price = $price_per_night * $number_of_nights;

    // Insert Guest
    $guest_query = "INSERT INTO Guest (FirstName, LastName, Email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE GuestID=LAST_INSERT_ID(GuestID)";
    $guest_stmt = $conn->prepare($guest_query);
    $guest_stmt->bind_param("sss", $first_name, $last_name, $email);
    $guest_stmt->execute();
    $guest_id = $conn->insert_id;
    $guest_stmt->close();

    // Check Room Availability
    $availabilityQuery = "SELECT COUNT(*) FROM Booking WHERE RoomID = ? AND ((Checkin <= ? AND Checkout >= ?) OR (Checkin <= ? AND Checkout >= ?))";
    $availabilityStmt = $conn->prepare($availabilityQuery);
    $availabilityStmt->bind_param("isss", $room_id, $check_out, $check_in, $check_in, $check_out);
    $availabilityStmt->execute();
    $availabilityResult = $availabilityStmt->get_result();
    $availabilityRow = $availabilityResult->fetch_assoc();
    if ($availabilityRow['COUNT(*)'] > 0) {
        echo "<p>Error: Room is not available for the selected dates.</p>";
        $availabilityStmt->close();
        $conn->close();
        exit;
    }
    $availabilityStmt->close();

    // Insert Booking
    $booking_query = "INSERT INTO Booking (GuestID, RoomID, Checkin, Checkout, NumberOfGuests, TotalPrice) VALUES (?, ?, ?, ?, ?, ?)";
    $booking_stmt = $conn->prepare($booking_query);
    $booking_stmt->bind_param("iissid", $guest_id, $room_id, $check_in, $check_out, $number_of_guests, $total_price);

    if ($booking_stmt->execute()) {
        $booking_id = $conn->insert_id; // Get the booking ID
        //Update room vacancy
        $vacancyQuery = "UPDATE Room SET Vacancy = FALSE WHERE RoomID = ?";
        $vacancyStmt = $conn->prepare($vacancyQuery);
        $vacancyStmt->bind_param("i", $room_id);
        $vacancyStmt->execute();
        $vacancyStmt->close();

        // Insert Payment
        $payment_query = "INSERT INTO Payment (BookingID, PaymentMethod, Amount, PaymentDate) VALUES (?, ?, ?, NOW())";
        $payment_stmt = $conn->prepare($payment_query);
        $payment_stmt->bind_param("isd", $booking_id, $payment_method, $total_price); // Use the calculated total price
        if ($payment_stmt->execute()) {
            echo "Booking and payment successful! Payment Method: " . htmlspecialchars($payment_method) . ", Price: " . htmlspecialchars($total_price);
        } else {
            echo "Error: Payment failed.";
        }
        $payment_stmt->close();
    } else {
        echo "Error: Booking failed.";
    }
    $booking_stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>