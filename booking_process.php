<?php
// booking_process.php (Handles customer and booking information)

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Customer Information
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    // Booking Information
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_type = $_POST['room_type'];
    $number_of_guests = intval($_POST['number_of_guests']);

    // Validation for customer and booking data
    if (empty($first_name) || empty($last_name) || empty($email) || empty($check_in) || empty($check_out) || empty($room_type) || empty($number_of_guests)) {
        echo "Error: Missing customer or booking fields.";
        $conn->close();
        exit;
    }

    // Get Room ID
    $room_id_query = "SELECT Room.RoomID, RoomType.Name FROM Room LEFT JOIN RoomType ON Room.TypeID = RoomType.TypeID WHERE RoomType.Name = ?";
    $room_id_stmt = $conn->prepare($room_id_query);
    
   
    
    $room_id_stmt->bind_param("s", $room_type);
    $room_id_stmt->execute();
    
    if ($room_id_stmt->errno) {
        echo "MySQL Error: " . $room_id_stmt->error;
    }
    
    $room_id_result = $room_id_stmt->get_result();
    
    
    $row = $room_id_result->fetch_assoc();
    
    
    if ($row && $row['RoomID']) {
        $room_id = $row['RoomID'];
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
    $availabilityStmt->bind_param("issss", $room_id, $check_out, $check_in, $check_in, $check_out);
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

        // Pass booking_id and total_price to payment processing
        echo "Booking successful! Booking ID: " . $booking_id . ", Total Price: " . $total_price;
        echo "<form action='payment_process.php' method='POST'>";
        echo "<input type='hidden' name='booking_id' value='" . $booking_id . "'>";
        echo "<input type='hidden' name='total_price' value='" . $total_price . "'>";
        echo "<label for='payment_method'>Payment Method:</label>";
        echo "<select name='payment_method' id='payment_method' required>";
        echo " <option value='Credit Card'>Credit Card</option>";
        echo " <option value='PayPal'>PayPal</option>";
        echo " <option value='Cash'>Cash</option>";
        echo "</select><br><br>";
        echo "<button type='submit'>Proceed to Payment</button>";
        echo "</form>";

    } else {
        echo "Error: Booking failed.";
    }
    $booking_stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>