<?php
session_start(); // Ensure this is at the very top before any output

include 'dbconn.php';

// Check if the user ID is set in the session
if (!isset($_SESSION['userID'])) {
    die("User not logged in.");
}

$patientID = $_SESSION['userID'];

try {
    $sql = "SELECT 
        medSerialNumber,
        medName,
        mfgDate,
        expDate,
        quantity,
        medFactory
    FROM 
        medication";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($result === false) {
        throw new Exception("Error getting result set: " . $stmt->errorInfo[2]);
    }
    
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MedicHub</title>
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
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
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

        .clinic-appointment {
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
    </style>
</head>
<body>
<input type="hidden" id="userID" value="<?php echo htmlspecialchars($_SESSION['userID']); ?>">
    <header>
        <h1>Welcome to MedicHub</h1>
        <nav>
            <ul>
                <li><a href="#" id="about-us-link">About Us</a></li>
                <li><a href="#" id="services-link">Services</a></li>
                <li><a href="#" id="contact-link">Contact</a></li>
                
            </ul>
        </nav>
    </header>

    <div class="container">
        <div id="appointment" class="clinic-appointment">
            <h2>Medical Records</h2>
            <table class="transparent-table">
                <thead>
                    <tr>
                        <th>SERIAL NUMBER</th>
                        <th>NAME</th>
                        <th>MFG. DATE</th>
                        <th>EXP</th>
                        <th>QUANTITY</th>
                        <th>MFG. FACTORY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && count($result) > 0) {
                        foreach ($result as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['medSerialNumber']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['medName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['mfgDate']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['expDate']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['medFactory']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
	
</body>
</html>