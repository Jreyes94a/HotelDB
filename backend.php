

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'HotelDB';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error);
    echo "<p>Error: Could not connect to database. Please try again later.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "register":
            registerGuest($conn);
            break;
        case "book":
            bookRoom($conn);
            break;
        case "pay":
            processPayment($conn);
            break;
        default:
            echo "<p>Error: Invalid action.</p>";
    }
} else {
    echo "<p>Error: Action not specified.</p>";
}

$conn->close();

function registerGuest($conn) {
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['phone'], $_POST['email'])) {
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $email = $conn->real_escape_string($_POST['email']);

        $query = "INSERT INTO Guest (FirstName, LastName, Phone, Email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $first_name, $last_name, $phone, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<p>Guest registered successfully!</p>";
        } else {
            error_log("Guest registration failed: " . $stmt->error);
            echo "<p>Error: Guest registration failed.</p>";
        }
        $stmt->close();

    } else {
        echo "<p>Error: Missing required fields for guest registration.</p>";
    }
}

function bookRoom($conn) {
    if (isset($_POST['guest_id'], $_POST['room_id'], $_POST['check_in'], $_POST['check_out'])) {
        $guest_id = intval($_POST['guest_id']);
        $room_id = intval($_POST['room_id']);
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];

        //get the price from the database.
        $priceQuery = "SELECT BasePrice FROM RoomType JOIN Room ON RoomType.TypeID = Room.TypeID WHERE Room.RoomID = ?";
        $priceStmt = $conn->prepare($priceQuery);
        $priceStmt->bind_param("i", $room_id);
        $priceStmt->execute();
        $priceResult = $priceStmt->get_result();
        if($priceRow = $priceResult->fetch_assoc()){
            $price = $priceRow["BasePrice"];
        } else {
            echo "<p>Error: could not find room price.</p>";
            return;
        }
        $priceStmt->close();

        // Check room availability
        $availabilityQuery = "SELECT COUNT(*) FROM Booking WHERE RoomID = ? AND ((CheckIn <= ? AND Checkout >= ?) OR (CheckIn <= ? AND Checkout >= ?))";
        $availabilityStmt = $conn->prepare($availabilityQuery);
        $availabilityStmt->bind_param("isss", $room_id, $check_out, $check_in, $check_in, $check_out);
        $availabilityStmt->execute();
        $availabilityResult = $availabilityStmt->get_result();
        $availabilityRow = $availabilityResult->fetch_assoc();
        if ($availabilityRow['COUNT(*)'] > 0) {
            echo "<p>Error: Room is not available for the selected dates.</p>";
            return;
        }
        $availabilityStmt->close();

        $query = "INSERT INTO Booking (GuestID, RoomID, Checkin, Checkout, TotalPrice) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iissd', $guest_id, $room_id, $check_in, $check_out, $price);

        if ($stmt->execute()) {

            //Update room vacancy
            $vacancyQuery = "UPDATE Room SET Vacancy = FALSE WHERE RoomID = ?";
            $vacancyStmt = $conn->prepare($vacancyQuery);
            $vacancyStmt->bind_param("i", $room_id);
            $vacancyStmt->execute();
            $vacancyStmt->close();

            echo "<p>Room booked successfully!</p>";
        } else {
            error_log("Room booking failed: " . $stmt->error);
            echo "<p>Error: Room booking failed.</p>";
        }

        $stmt->close();

    } else {
        echo "<p>Error: Missing required fields for booking.</p>";
    }
}

function processPayment($conn) {
    if (isset($_POST['booking_id'], $_POST['amount'], $_POST['payment_method'])) {
        $booking_id = intval($_POST['booking_id']);
        $amount = floatval($_POST['amount']);
        $payment_method = $conn->real_escape_string($_POST['payment_method']);

        // Check if the booking exists
        $bookingCheckQuery = "SELECT BookingID FROM Booking WHERE BookingID = ?";
        $bookingCheckStmt = $conn->prepare($bookingCheckQuery);
        $bookingCheckStmt->bind_param("i", $booking_id);
        $bookingCheckStmt->execute();
        $bookingCheckResult = $bookingCheckStmt->get_result();
        if ($bookingCheckResult->num_rows == 0) {
            echo "<p>Error: Booking ID does not exist.</p>";
            return;
        }
        $bookingCheckStmt->close();

        $query = "INSERT INTO Payment (BookingID, Amount, PaymentDate, PaymentMethod) VALUES (?, ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ids', $booking_id, $amount, $payment_method);

        if ($stmt->execute()) {
            echo "<p>Payment successful!</p>";
        } else {
            error_log("Payment failed: " . $stmt->error);
            echo "<p>Error: Payment failed.</p>";
        }
        $stmt->close();

    } else {
        echo "<p>Error: Missing required fields for payment.</p>";
    }
}
?>
