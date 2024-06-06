<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">

<style>

</style>
</head>
<div class="container">

            <?php
            session_start();
			if (!isset($_SESSION['userID']) ||!isset($_SESSION['userType'])) {
			echo "Session variables not set!";
			exit;
			}

			include("dbconn.php");
			$pdo = $conn;

			// Get admin ID from session
			$adminID = $_SESSION['userID'];
			
			// Function to get doctor information based on admin ID
			function getDoctorInfo($pdo, $adminID) {
			try {
			$stmt = $pdo->prepare("SELECT * FROM doctor d ");
			$stmt->execute();
			$doctors = $stmt->fetchAll();

			return $doctors;
			} catch (PDOException $e) {
				echo "Error: ". $e->getMessage(). "\n"; // Debugging statement
			return false;
			}
		}
			
			$doctors = getDoctorInfo($pdo, $adminID);

		if ($doctors) {;
		?>
		<div class="doctor-list">
        <h2>Doctor Profile List</h2>
        <table>
            <tr>
                <th>Doctor ID</th>
                <th>Doctor Name</th>
				<th>Doctor No IC</th>
                <th>Doctor Speciality</th>
                <th>Availability</th>
            </tr>
            <?php foreach ($doctors as $row) {?>
			  <tr>
				<td><?php echo $row["doctorID"];?></td>
				<td><?php echo $row["doctorName"];?></td>
				<td><?php echo $row["doctorNRIC"];?></td>
				<td><?php echo $row["doctorSpeciality"];?></td>
				<td><?php echo $row["availability"];?></td>
			  </tr>
			<?php }?>
        </table>
    </div>
    <?php
} 
?>
