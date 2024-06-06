<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['userType'])) {
    echo "Session variables not set!";
    exit;
}

include("dbconn.php");
$pdo = $conn;

$userType = $_POST['userType'];
$userID = $_POST['userID'];

if ($userType === 'doctor') {
    $diagnosis = $_POST['diagnosis'];
    $medName = $_POST['medName'];
    $appointmentStatus = $_POST['appointmentStatus'];

    // Check if variables are defined and not null
    if (!isset($diagnosis) || !isset($medName) || !isset($appointmentStatus)) {
        echo "One or more variables are not defined!";
        exit;
    }

    $stmt = $pdo->prepare("UPDATE doctor SET diagnosis =?, medName =?, appointmentStatus =? WHERE doctorID =?");
    $stmt->bindParam(1, $diagnosis, PDO::PARAM_STR);
    $stmt->bindParam(2, $medName, PDO::PARAM_STR);
    $stmt->bindParam(3, $appointmentStatus, PDO::PARAM_STR);
    $stmt->bindParam(4, $userID, PDO::PARAM_STR); // Note: You had 5, but it should be 4
    $stmt->execute();

    // Redirect to the profile page
    header("Location: profile.php?userType=doctor&userID=$userID");
    exit;
} else {
    echo "Invalid user type!";
}
?>
