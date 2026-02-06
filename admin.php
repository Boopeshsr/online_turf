<?php
include 'db_config.php';

// Fetch all bookings sorted by the most recent date
$sql = "SELECT * FROM bookings ORDER BY booking_date DESC, time_slot ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TurfLegends</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { height: 100vh; background: #1b5e20; color: white; padding: 20px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 d-none d-md-block sidebar">
            <h4 class="fw-bold mb-4">Turf Admin</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#" class="nav-link text-white border-bottom">Bookings</a></li>
                <li class="nav-item mb-2"><a href="index.html" class="nav-link text-white">View Site</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Manage Bookings</h2>
                <button onclick="window.location.reload()" class="btn btn-outline-success btn-sm">Refresh Data</button>
            </div>

            <div class="card p-4">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Turf Name</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Booked On</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>#{$row['id']}</td>
                                        <td><span class='fw-bold'>{$row['turf_name']}</span></td>
                                        <td>" . date('d M Y', strtotime($row['booking_date'])) . "</td>
                                        <td><span class='badge bg-info text-dark'>{$row['time_slot']}</span></td>
                                        <td>" . date('Y-m-d H:i', strtotime($row['created_at'])) . "</td>
                                        <td><span class='status-badge bg-success text-white'>Confirmed</span></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No bookings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>