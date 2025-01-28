<?php
require("auth.php");
checkLoggedIn($db);

$error = '';
$success = '';

// Function to generate a random password
function generatePassword($length = 8) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

// Function to send SMS
function sendSMS($message, $contact_number) {
    $user = "94763797373"; 
    $password = "1145"; 
    $text = urlencode($message);
    $to = $contact_number;
    $baseurl = "http://www.textit.biz/sendmsg";
    $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
    file_get_contents($url);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_staff'])) {
    $staff_id = $_POST['staff_id'];
    $contact_number = trim($_POST['contact_number']);
    $nic = trim($_POST['nic']);
    $full_name = trim($_POST['full_name']);
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $generate_password = isset($_POST['generate_password']); // Checkbox value

    $update_password = false;
    $new_password = '';
    $send_sms = false; // Flag to determine if SMS needs to be sent

    // Check if password should be generated
    if ($generate_password) {
        $new_password = generatePassword(); // Generate a new password
        $update_password = true;
    }

    // Fetch the existing contact number
    $stmt = $db->prepare("SELECT contact_number FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $stmt->bind_result($current_contact_number);
    $stmt->fetch();
    $stmt->close();

    // Check if contact number needs to be updated
    if ($contact_number !== $current_contact_number) {
        $send_sms = true; // Set flag to send SMS
    }

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
        $stmt = $db->prepare("SELECT staff_id FROM staff WHERE (contact_number = ? OR nic = ?) AND staff_id != ?");
        $stmt->bind_param("ssi", $contact_number, $nic, $staff_id);
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
            // Prepare the SQL query
            $query = "UPDATE staff SET contact_number = ?, nic = ?, full_name = ?, gender = ?, address = ?";

            // Add password update if necessary
            if ($update_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $query .= ", password = ?";
            }

            $query .= " WHERE staff_id = ?";

            $stmt = $db->prepare($query);

            if ($update_password) {
                $stmt->bind_param("ssssssi", $contact_number, $nic, $full_name, $gender, $address, $hashed_password, $staff_id);
            } else {
                $stmt->bind_param("sssssi", $contact_number, $nic, $full_name, $gender, $address, $staff_id);
            }

            if ($stmt->execute()) {
                if ($update_password) {
                    $sms_message = "Your Gallery Cafe Staff login details have been updated. New password: $new_password";
                    sendSMS($sms_message, $contact_number); // Send the new password via SMS
                }

                if ($send_sms) {
                    $sms_message = "Your Gallery Cafe Staff login contact number has been updated to this $contact_number.";
                    sendSMS($sms_message, $contact_number); // Send an SMS to the new contact number
                }

                $success = '<div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                Staff updated successfully.
                            </div>';
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Failed to update staff. Please try again.
                        </div>';
            }

            $stmt->close();
        }

        $stmt->close();
    }
}

// Fetch existing staff details for editing
$staff_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

$full_name = $staff['full_name'];
$contact_number = $staff['contact_number'];
$nic = $staff['nic'];
$gender = $staff['gender'];
$address = $staff['address'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Gallery Cafe | Edit Staff</title>
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
                    <h3 class="text-primary">Edit Staff</h3>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header mb-2">
                                <h4 class="m-b-0 text-white">Edit Staff</h4>
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
                                    <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">

                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" pattern="\d{10}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="nic">NIC</label>
                                        <input type="text" class="form-control" id="nic" name="nic" value="<?php echo htmlspecialchars($nic); ?>" pattern="\d{12}|\d{9}[V|X]" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled>Select Gender</option>
                                            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="generate_password">Generate New Password</label>
                                        <input type="checkbox" id="generate_password" name="generate_password">
                                    </div>

                                    <button type="submit" name="update_staff" class="btn btn-primary">Update Staff</button>
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
