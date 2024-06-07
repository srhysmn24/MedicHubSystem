

<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}

include("dbconn.php");
$pdo = $conn;

$userType = $_GET['userType'];
$userID = $_GET['userID'];
$appointmentID = $_GET['appointmentID'];

function getAppointmentInfo($pdo, $appointmentID) {
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT a.*, p.patientName, mn.medName, mc.mcSerialNumber, pr.prescriptionID
                              FROM appointment a
                              LEFT JOIN medicalcertificate mc ON a.appointmentID = mc.appointmentID
                              LEFT JOIN patient p ON a.patientID = p.patientID 
                              LEFT JOIN prescription pr ON a.prescriptionID = pr.prescriptionID 
                              LEFT JOIN medication mn ON pr.medSerialNumber = mn.medSerialNumber 
                              WHERE a.appointmentID =?");
        $stmt->bindParam(1, $appointmentID, PDO::PARAM_STR);
        $stmt->execute();
        $appointment = $stmt->fetch();
        if ($appointment) {
            return $appointment;
        } else {
            echo "Appointment not found for appointment ID $appointmentID!\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n";
        return false;
    }
}

if ($userType === 'doctor') {
    $appointment = getAppointmentInfo($pdo, $appointmentID);
    if ($appointment) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Appointment</title>
            <style>
                /* Add your CSS here */
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
                    <input type="hidden" name="appointmentID" value="<?php echo $appointmentID; ?>">
                    <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                    <label for="diagnosis">Diagnosis:</label>
                    <input type="text" id="diagnosis" name="diagnosis" value="<?php echo $appointment['diagnosis'];?>" required>
                    <label for="appointmentStatus">Appointment Status:</label>
                    <input type="text" id="appointmentStatus" name="appointmentStatus" value="<?php echo $appointment['appointmentStatus'];?>" required>
                    <label for="prescriptionID">Medicine Name:</label>
                    <select id="prescriptionID" name="prescriptionID" required>
						<option value="NULL" <?php if($appointment['prescriptionID'] == '-') echo 'selected'; ?>>-</option>
                        <option value="PR0001" <?php if($appointment['prescriptionID'] == 'PR0001-Paracetamol') echo 'selected'; ?>>PR0001-Paracetamol</option>
                        <option value="PR0002" <?php if($appointment['prescriptionID'] == 'PR0002-Ibuprofen') echo 'selected'; ?>>PR0002-Ibuprofen</option>
                        <option value="PR0003" <?php if($appointment['prescriptionID'] == 'PR0003-Amoxicillin') echo 'selected'; ?>>PR0003-Amoxicillin</option>
                        <option value="PR0004" <?php if($appointment['prescriptionID'] == 'PR0004-Methotrexate') echo 'selected'; ?>>PR0004-Methotrexate</option>
                        <option value="PR0005" <?php if($appointment['prescriptionID'] == 'PR0005-Diuretics') echo 'selected'; ?>>PR0005-Diuretics</option>
                        <option value="PR0006" <?php if($appointment['prescriptionID'] == 'PR0006-Tramadol') echo 'selected'; ?>>PR0006-Tramadol</option>
                        <option value="PR0007" <?php if($appointment['prescriptionID'] == 'PR0007-Fentanyl') echo 'selected'; ?>>PR0007-Fentanyl</option>
                        <option value="PR0008" <?php if($appointment['prescriptionID'] == 'PR0008-Oxycodone') echo 'selected'; ?>>PR0008-Oxycodone</option>
                        <option value="PR0009" <?php if($appointment['prescriptionID'] == 'PR0009-Trimethoprim') echo 'selected'; ?>>PR0009-Trimethoprim</option>
                        <option value="PR0010" <?php if($appointment['prescriptionID'] == 'PR0010-Funderparinex') echo 'selected'; ?>>PR0010-Funderparinex</option>
                    </select>
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
    echo "Invalid user type!";
}
?>
