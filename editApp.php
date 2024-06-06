<?php
session_start();
if (!isset($_SESSION['userID']) ||!isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}

include("dbconn.php");
$pdo = $conn;

$userType = $_GET['userType'];
$userID = $_GET['userID'];

function getAppInfo($pdo, $doctorID) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM doctor WHERE doctorID =?");
        $stmt->bindParam(1, $doctorID, PDO::PARAM_STR);
        $stmt->execute();
        $doctor = $stmt->fetch();
        if ($doctor) {
            return $doctor;
        } else {
            echo "Doctor with ID $doctorID not found in the database!\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n";
        return false;
    }
}

function getAppointmentInfo($pdo, $doctorID) {
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT a.*, p.patientName, mn.medName, mc.mcSerialNumber
                              FROM appointment a
                              LEFT JOIN medicalcertificate mc ON a.appointmentID = mc.appointmentID
                              LEFT JOIN patient p ON a.patientID = p.patientID 
                              LEFT JOIN prescription pr ON a.prescriptionID = pr.prescriptionID 
                              LEFT JOIN medication mn ON pr.medSerialNumber = mn.medSerialNumber 
                              WHERE a.doctorID =?");
        $stmt->bindParam(1, $doctorID, PDO::PARAM_STR);
        $stmt->execute();
        $appointment = $stmt->fetch();
        if ($appointment) {
            return $appointment;
        } else {
            echo "Appointment not found for doctor ID $doctorID!\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n";
        return false;
    }
}

if ($userType === 'doctor') {
    $doctor = getAppInfo($pdo, $userID);
    if ($doctor) {
        $appointment = getAppointmentInfo($pdo, $userID);
        if ($appointment) {
           ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Edit Appointment</title>
                <style>
                    
                </style>
            </head>
            <body>
                <header>
                    <h1>Welcome to MedicHub</h1>
                    <nav>
                        <ul>
                            <li><a href="homePage.html#about-us">About Us</a></li>
                            <li><a href="homePage.html#services">Services</a></li>
                            <li><a href="homePage.html#contact">Contact</a></li>
                        </ul>
                    </nav>
                </header>
                <div class="appointment-card">
                    <h2>Edit Appointment</h2>
                    <form method="POST" action="updateApp.php">
                    <label for="diagnosis">Diagnosis:</label>
                    <input type="text" id="diagnosis" name="diagnosis" value="<?php echo $appointment['diagnosis'];?>" required>
                    <label for="appointmentStatus">Appointment Status:</label>
                    <input type="text" id="appointmentStatus" name="appointmentStatus" value="<?php echo $appointment['appointmentStatus'];?>" required>
                    <label for="medName">Medicine Name:</label>
                    <input type="text" id="medName" name="medName" value="<?php echo $appointment['medName'];?>" required>
                    <button type="submit" class="submit-button">SUBMIT</button>
                    </form>
                </div>
            </body>
        </html>
        <?php
        } else {
            echo "Appointment not found!";
        }
    } else {
        echo "Doctor not found!";
    }
} else {
    echo "Invalid user type!";
}
?>