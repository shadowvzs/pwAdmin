<?php
session_start(); // Start the session

//$config = [ // those configs coming from outside, from the server installer. Here is just the sample data.
//    'host' => 'localhost',
//    'user' => 'admin',
//    'pass' => 'admin', // Replace with your database password
//    'name' => 'pw',
//    'gold' => '1000000000',
//];

$config = [];

error_reporting(E_ALL);
ini_set('display_errors', 1);

function mysqli_result($res, $row = 0, $col = 0)
{
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
        mysqli_data_seek($res, $row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        return $resrow[$col] ?? false;
    }
    return false;
}

// Function to generate a random captcha code
function generateCaptchaCode($length = 4)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captchaCode = '';
    for ($i = 0; $i < $length; $i++) {
        $captchaCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $captchaCode;
}

// Function to generate captcha image
function generateCaptchaImage($captchaCode)
{
    $captchaImage = imagecreatefrompng('captcha-background.png'); // Replace with your captcha background image
    $captchaTextColor = imagecolorallocate($captchaImage, 255, 255, 255);
    imagestring($captchaImage, 5, 10, 5, $captchaCode, $captchaTextColor);

    // Output the captcha image as base64
    ob_start();
    imagepng($captchaImage);
    $captchaImageData = ob_get_clean();

    return $captchaImageData;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $link = new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);

    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }

    $Login = trim(strtolower($_POST['login']));
    $Pass = trim(strtolower($_POST['passwd']));
    $Repass = trim(strtolower($_POST['repasswd']));
    $Email = trim($_POST['email']);
    $EnteredCaptcha = trim($_POST['captcha']);

    $alerts = [];

    // Check captcha
    if (false){
    #if ($EnteredCaptcha !== $_SESSION['captcha']) {
    #    $alerts[] = "Captcha code is incorrect.";
    #} elseif (empty($Login) || empty($Pass) || empty($Repass) || empty($Email)) {
    #    $alerts[] = "All fields are required.";
    #} elseif (!preg_match("/^[0-9a-zA-Z_-]+$/", $Login)) {
    #    $alerts[] = "Invalid login format. Use only alphanumeric characters, hyphens, and underscores.";
    #} elseif (!preg_match("/^[0-9a-zA-Z_-]+$/", $Pass)) {
    #    $alerts[] = "Invalid password format. Use only alphanumeric characters, hyphens, and underscores.";
    #} elseif (!preg_match("/^[0-9a-zA-Z_-]+$/", $Repass)) {
    #    $alerts[] = "Invalid retry password format. Use only alphanumeric characters, hyphens, and underscores.";
    #} elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    #    $alerts[] = "Invalid E-Mail Format.";
    #} elseif (strlen($Login) < 4 || strlen($Login) > 10) {
    #    $alerts[] = "The login must be between 4 and 10 characters.";
    } else {
        $stmt = $link->prepare("SELECT name FROM users WHERE name=?");
        $stmt->bind_param("s", $Login);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $alerts[] = "Login <b>$Login</b> already exists in the database.";
        } elseif (strlen($Pass) < 4 || strlen($Pass) > 10) {
            $alerts[] = "The password must be between 4 and 10 characters.";
        } elseif (strlen($Repass) < 4 || strlen($Repass) > 10) {
            $alerts[] = "Repeat password must be between 4 and 10 characters.";
        } elseif (strlen($Email) < 4 || strlen($Email) > 25) {
            $alerts[] = "E-Mail must be between 4 and 25 characters.";
        } else {
            $stmt = $link->prepare("SELECT name FROM users WHERE name=?");
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $alerts[] = "E-Mail <b>$Email</b> already exists in the database.";
            } elseif ($Pass !== $Repass) {
                $alerts[] = "Passwords do not match.";
            } else {
                $Salt = base64_encode(md5($Login . $Pass, true));
                $stmt = $link->prepare("CALL adduser(?, ?, '0', '0', '0', '0', ?, '0', '0', '0', '0', '0', '0', '0', NULL, '', ?)");
                $stmt->bind_param("ssss", $Login, $Salt, $Email, $Salt);
                $stmt->execute();

                $mysqlresult = mysqli_query($link, "SELECT * FROM `users` WHERE `name`='$Login'");
                $User_ID = mysqli_result($mysqlresult, 0, 'ID');
                $stmt = $link->prepare("CALL usecash(?, ?, 0, ?, 0, ?, 1, @error)");
                $stmt->bind_param("iiii", $User_ID, $Zone_ID, $A_ID, $config['gold']);
                $stmt->execute();

                $alerts[] = "Account name <b>$Login</b> successfully registered. User ID: $User_ID. If Gold was applied, it will be available in 5-10 minutes.";
            }
        }
    }
    $stmt = null; // Initialize $stmt
    // Display alerts
    foreach ($alerts as $alert) {
        $alertClass = 'info'; // Use 'info' class for neutral color
        echo "<div class='alert alert-dismissible alert-$alertClass mt-3'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                $alert
              </div>";
    }

    // Clear the captcha code from the session after form submission
    unset($_SESSION['captcha']);

    // No need to close $stmt again here
    // $stmt->close();  // Commented out to prevent the error

    $link->close(); // Close the connection here
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration | Perfect World</title>
    <meta http-equiv="content-type" content="text/html" charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #343a40; /* Dark background color */
            color: #ffffff; /* Light text color */
        }

        .container {
            background-color: #454d55; /* Dark container background color */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            background-color: #555d66; /* Dark input background color */
            color: #ffffff; /* Light input text color */
        }

        .btn-primary {
            background-color: #007bff; /* Primary button color */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
            border-color: #0056b3;
        }

        .alert {
            border-radius: 0; /* Remove border-radius for alerts */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <form id="register" action="<?= $_SERVER['PHP_SELF']; ?>" method="post" class="col-md-6 offset-md-3">
            <h3 class="mb-4 text-center">Registration on the server</h3>
            <h3 class="text-center">Perfect World Server Name Here</h3>

            <div class="form-group">
                <label for="login">Login:</label>
                <input class="form-control" type="text" name="login" id="login" required>
            </div>
            <div class="form-group">
                <label for="passwd">Password:</label>
                <input class="form-control" type="password" name="passwd" id="passwd" required>
            </div>
            <div class="form-group">
                <label for="repasswd">Repeat password:</label>
                <input class="form-control" type="password" name="repasswd" id="repasswd" required>
            </div>
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input class="form-control" type="email" name="email" id="email" required>
            </div>

            <!-- Display the captcha image -->
            <div class="form-group">
                <label for="captcha">Captcha:</label>
                <?php
                // Generate captcha code and store it in the session
                $_SESSION['captcha'] = generateCaptchaCode();

                // Output the captcha image directly in the HTML
                // echo '<img src="data:image/png;base64,' . base64_encode(generateCaptchaImage($_SESSION['captcha'])) . '" alt="Captcha Image">';
                // ?>
                <!--
                <input class="form-control" type="text" name="captcha" id="captcha" required>
                -->
            </div>

            <button class="btn btn-primary btn-block" type="submit" name="submit">Registration</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 text-center">
        <p>Created by shiank98 for RageZone</p>
    </footer>
</body>

</html>
