<?php
session_start();
if (!isset($_SESSION['userID']) ||!isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}

include("dbconn.php");
$pdo = $conn;

// Get patient ID from session
$patientID = $_SESSION['userID'];

// Function to get appointment information based on patient ID
function getAppointmentInfo($pdo, $patientID) {
    try {
        $stmt = $pdo->prepare("SELECT a.*, d.doctorName, m.medName 
                              FROM appointment a 
                              LEFT JOIN doctor d ON a.doctorID = d.doctorID 
                              LEFT JOIN prescription p ON a.prescriptionID = p.prescriptionID 
                              LEFT JOIN medication m ON p.medSerialNumber = m.medSerialNumber 
                              WHERE a.patientID =?");
        $stmt->bindParam(1, $patientID, PDO::PARAM_STR);
        $stmt->execute();
        $appointments = $stmt->fetchAll();

        return $appointments;
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n"; // Debugging statement
        return false;
    }
}

$appointments = getAppointmentInfo($pdo, $patientID);

if ($appointments) {
   ?>
    <div class="appointment-list">
        <h2>Appointment List</h2>
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Appointment Date</th>
                <th>Time Slot</th>
                <th>Diagnosis</th>
                <th>Doctor Name</th>
                <th>Medicine Name</th>
                <th>Appointment Status</th>
				<th>Medical Certificate</th>
            </tr>
            <?php foreach ($appointments as $appointment) {?>
            <tr>
                <td><?= $appointment['appointmentID']?></td>
                <td><?= $appointment['appointmentDate']?></td>
                <td><?= $appointment['timeSlot']?></td>
                <td><?= $appointment['diagnosis']?></td>
                <td><?= $appointment['doctorName']?></td>
                <td><?= $appointment['medName']?></td>
                <td><?= $appointment['appointmentStatus']?></td>
				<td><a href="mcPatient.php?userType=patient&userID=<?= $patientID ?>&appointmentID=<?= $appointment['appointmentID'] ?>" class="edit-button">Download</a></td>
            </tr>
            <?php }?>
        </table>
    </div>
    <?php
} else {
    echo "No appointments found!";
}
?>