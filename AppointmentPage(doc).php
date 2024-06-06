<?php
session_start();
if (!isset($_SESSION['userID']) ||!isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}

include("dbconn.php");
$pdo = $conn;

// Get doctor ID from session
$doctorID = $_SESSION['userID'];

// Function to get appointment information based on doctor ID
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
        $appointments = $stmt->fetchAll();

        return $appointments;
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage(). "\n"; // Debugging statement
        return false;
    }
}

$appointments = getAppointmentInfo($pdo, $doctorID);

if ($appointments) {
   ?>
    <div class="appointment-list">
        <h2>Appointment List</h2>
        <table>
            <tr>
                <th>Appointment Status</th>
                <th>Appointment ID</th>
				<th>Patient ID</th>
                <th>Patient Name</th>
                <th>Appointment Date</th>
                <th>Time Slot</th>
                <th>Diagnosis</th>
                <th>Prescription</th>
				<th>MC ID</th>
        <th>Edit</th>
            </tr>
            <?php foreach ($appointments as $appointment) {?>
            <tr>
                <td><?= $appointment['appointmentStatus']?></td>
                <td><?= $appointment['appointmentID']?></td>
				<td><?= $appointment['patientID']?></td>
				<td><?= $appointment['patientName']?></td>
				<td><?= $appointment['appointmentDate']?></td>
                <td><?= $appointment['timeSlot']?></td>
                <td><?= $appointment['diagnosis']?></td>
                <td><?= $appointment['medName']?></td>
				<td><?= $appointment['mcSerialNumber']?></td>
                <td><a href="editApp.php?userType=doctor&userID=<?= $doctorID ?>&appointmentID=<?= $appointment['appointmentID'] ?>" class="edit-button">EDIT APPOINTMENT</a></td>
            </tr>
            <?php }?>
        </table>
    </div>
    <?php
} else {
    echo "No appointments found!";
}
?>