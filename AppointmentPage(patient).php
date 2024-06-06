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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicHub | Appointment View Patient</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f2f2f2;
            color: #262626;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: linear-gradient(-135deg, #c850c0, #4158d0);
            color: #fff;
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        header h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        nav {
            margin-top: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline-block;
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            background: #fff;
            color: #fff; /* Changed to white color */
            font-size: 18px;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            background-clip: text;
            -webkit-background-clip: text;
            background: linear-gradient(-135deg, #c850c0, #4158d0);
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        nav ul li .login-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        nav ul li .login-btn i {
            font-size: 20px;
            color: #fff;
            transition: color 0.3s ease;
        }

        nav ul li .login-btn i:hover {
            color: rgba(255, 255, 255, 0.7);
        }
		
		.sidebar {
            background-color: #fff;
            color: #fff;
            padding: 20px;
            width: 200px;
            border-radius: 10px;
        }

        .sidebar button {
            display: inline-block;
            width: 100%;
            background: #fff; /* Changed to white background */
            color: #fff; /* Changed to white color */
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            background-clip: text;
            -webkit-background-clip: text;
            background: linear-gradient(-135deg, #c850c0, #4158d0);
      outline: none;
        }

        .sidebar button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .appointment-list {
            background: linear-gradient(-135deg, #c850c0, #4158d0);
            color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .appointment h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .appointment p {
            font-size: 16px;
            line-height: 1.6;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            background-color: #4158d0;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #3448b7;
        }

        .service-img {
            width: 100%;
            max-width: 600px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            display: block;
        }

        .transparent-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .transparent-table th, .transparent-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
        }

        .transparent-table th {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .field {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .field button {
            color: #fff;
            border: none;
            padding-left: 0;
            margin-top: -10px;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(-135deg, #c850c0, #4158d0);
            transition: all 0.3s ease;
            margin-left: 10px;
        }
		.edit-btn {
		  background-color: #4CAF50; /* Green */
		  color: #fff;
		  padding: 10px 20px;
		  border: none;
		  border-radius: 5px;
		  cursor: pointer;
		  font-size: 16px;
		  font-weight: 500
		  text-transform: uppercase;
		  letter-spacing: 1px;
		  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.edit-btn:hover {
		  background-color: #3e8e41; /* Darker green on hover */
		  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}
		
		.top-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .top-buttons button {
            background: #fff; /* Changed to white background */
            color: #fff; /* Changed to white color */
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            background-clip: text;
            -webkit-background-clip: text;
            background: linear-gradient(-135deg, #c850c0, #4158d0);
        }

        .top-buttons button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    .header-logout{
      position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
    }
    .header-logout img {
            width: 30px;
            height: 30px;
            filter: invert(100%);
        }

        .header-logout img:hover {
            filter: brightness(50%);
        }
    </style>
</head>
<body>
<input type="hidden" id="userID" value="<?php echo $_SESSION['userID'];?>">
    <header>
        <h1>MEDICHUB</h1>
        <nav>
            <div class="header-logout" onclick="if (confirm('Are you sure you want to log out?')) window.location.href = 'logout.php'">
            <img src="https://cdn-icons-png.flaticon.com/128/3889/3889524.png" alt="Logout">
			</div>
        </nav>
    </header>
<?php
if ($appointments) {
   ?>
    <div class="container">
        <div id="appointment" class="appointment-list">
            <h2>Appointment List</h2>
            <table class="transparent-table">
				<thead>
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
			</thead>
			<tbody>
            <?php foreach ($appointments as $appointment) {?>
            <tr>
                <td><?= $appointment['appointmentID']?></td>
                <td><?= $appointment['appointmentDate']?></td>
                <td><?= $appointment['timeSlot']?></td>
                <td><?= $appointment['diagnosis']?></td>
                <td><?= $appointment['doctorName']?></td>
                <td><?= $appointment['medName']?></td>
                <td><?= $appointment['appointmentStatus']?></td>
				<td><button class="edit-btn" onclick="location.href='mcPatient.php?userType=patient&userID=<?= $patientID?>&appointmentID=<?= $appointment['appointmentID']?>'">Download</button></td>
            </tr>
            <?php }?>
			</table>
		</table>
    </div>
	
    <?php
} else {
    echo "No appointments found!";
}
?>
</tbody>
</div>
</body>

</html>
