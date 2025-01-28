<!DOCTYPE html>
<html lang="en">
<?php
require("auth.php");
checkLoggedIn($db);

$error = '';
$success = '';

// Function to generate a random password
function generatePassword($length = 8) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

// Function to send OTP via SMS
function sendOTP($password, $contact_number) {
    $sms = "Your Gallery Cafe Staff Login details: Contact Number: $contact_number and your password is $password";
    $user = "94763797373"; 
    $password = "1145"; 
    $text = urlencode($sms);
    $to = $contact_number;
    $baseurl = "http://www.textit.biz/sendmsg";
    $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
    file_get_contents($url);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    $contact_number = trim($_POST['contact_number']);
    $nic = trim($_POST['nic']);
    $full_name = trim($_POST['full_name']);
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $password = generatePassword(); // Generate a secure password

    // Validate contact number
    if (!preg_match('/^\d{10}$/', $contact_number)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Please enter a valid 10-digit contact number.
                </div>';
    // Validate NIC
    } elseif (!preg_match('/^\d{12}$|^\d{9}[V|X]$/', $nic)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    NIC must be either 12 digits or 9 digits followed by V or X.
                </div>';
    // Validate full name
    } elseif (empty($full_name) || !preg_match('/^[a-zA-Z\s]+$/', $full_name)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Full name is required and must contain only letters and spaces.
                </div>';
    // Validate gender
    } elseif (empty($gender) || !in_array($gender, ['Male', 'Female'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Please select a valid gender.
                </div>';
    // Validate address
    } elseif (empty($address)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Address is required.
                </div>';
    } else {
        // Check for duplicate contact number and NIC
        $stmt = $db->prepare("SELECT staff_id FROM staff WHERE contact_number = ? OR nic = ?");
        $stmt->bind_param("ss", $contact_number, $nic);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Contact number or NIC already exists. Please use different values.
                    </div>';
        } else {
            // Insert new staff into the database
            $stmt = $db->prepare("INSERT INTO staff (contact_number, nic, full_name, gender, address, password) VALUES (?, ?, ?, ?, ?, ?)");
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $stmt->bind_param("ssssss", $contact_number, $nic, $full_name, $gender, $address, $hashed_password);

            if ($stmt->execute()) {
                sendOTP($password, $contact_number); // Send the password via SMS
                $success = '<div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                Staff added successfully. The password has been sent via SMS.
                            </div>';
                // Clear form inputs after success
                $contact_number = $nic = $full_name = $gender = $address = '';
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Failed to add staff. Please try again.
                        </div>';
            }

            $stmt->close();
        }

        $stmt->close();
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Gallery Cafe | Add Staff</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <?php require('header.php'); ?>
        <?php require('sidebar.php'); ?>
        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Add Staff</h3>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header mb-2">
                                <h4 class="m-b-0 text-white">Add New Staff</h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($error)) : ?>
                                    <div class="alert alert-danger">
                                        <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($success)) : ?>
                                    <div class="alert alert-success">
                                        <?php
                                        echo $success;
                                        echo '<script>
                                            setTimeout(function(){
                                            window.location.href = "all_staff.php";
                                            }, 2000); // 2000 milliseconds = 2 seconds
                                            </script>';
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <form action="" method="POST">

                                    <div class="form-group">
                                        <label for="full_name">Full Name:</label>
                                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="nic">NIC:</label>
                                        <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($nic); ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_number">Contact Number:</label>
                                        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Gender:</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <textarea id="address" name="address" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
                                    </div>

                                    <button type="submit" name="add_staff" class="btn btn-primary">Add Staff</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
