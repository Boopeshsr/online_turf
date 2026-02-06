<?php
header("Content-Type: application/json");
include 'db_config.php';

// 1. Get the JSON data sent from JavaScript
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data) {
    $date = $conn->real_escape_string($data['date']);
    $slot = $conn->real_escape_string($data['slot']);
    $turf = $conn->real_escape_string($data['turf']);

    // 2. Check if the slot is already booked for that date
    $check_query = "SELECT * FROM bookings WHERE booking_date = '$date' AND time_slot = '$slot'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "This slot is already booked!"]);
    } else {
        // 3. Insert the booking into the database
        $sql = "INSERT INTO bookings (turf_name, booking_date, time_slot) VALUES ('$turf', '$date', '$slot')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Booking confirmed successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "No data received"]);
}

$conn->close();
?>