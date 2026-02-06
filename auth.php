<?php
header("Content-Type: application/json");
include 'db_config.php';

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data) {
    $action = $data['action'];
    $email = $conn->real_escape_string($data['email']);
    $password = $data['password']; // Note: In production, always use password_hash()

    if ($action === 'signup') {
        $name = $conn->real_escape_string($data['name']);
        
        // Check if email exists
        $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Email already registered!"]);
        } else {
            $sql = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($sql)) {
                echo json_encode(["status" => "success", "message" => "Account created! You can now login."]);
            }
        }
    } 
    
    elseif ($action === 'login') {
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Login Successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Wrong credentials!"]);
        }
    }
}
$conn->close();
?>