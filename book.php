<?php
header("Content-Type: application/json");
include 'db_config.php';

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data) {
    $date = $conn->real_escape_string($data['date']);
    $slot = $conn->real_escape_string($data['slot']);
    $sport = $conn->real_escape_string($data['sport']);
    $turf = $conn->real_escape_string($data['turf']);

    // LOGIC: Check if this specific sport is already booked for this date and time
    $check_query = "SELECT * FROM bookings 
                    WHERE booking_date = '$date' 
                    AND time_slot = '$slot' 
                    AND sport_name = '$sport'";
    
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // ERROR: Send message back to JS to show the alert
        echo json_encode(["status" => "error", "message" => "This $sport slot is already booked for the selected time!"]);
    } else {
        // SUCCESS: Save the booking
        $sql = "INSERT INTO bookings (turf_name, sport_name, booking_date, time_slot) 
                VALUES ('$turf', '$sport', '$date', '$slot')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Booking confirmed for $sport!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        }
    }
}
$conn->close();
?>