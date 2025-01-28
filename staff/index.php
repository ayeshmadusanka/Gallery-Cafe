<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
session_start();

$message = '';
if(isset($_POST['submit'])) {
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];
    
    if(!empty($contact_number) && !empty($password)) {
        // Prepare the SQL statement
        $stmt = $db->prepare("SELECT * FROM staff WHERE contact_number=? AND is_active=1");
        $stmt->bind_param("s", $contact_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // Verify the password
        if(is_array($row) && password_verify($password, $row['password'])) {
            $_SESSION["gallerycafe_staff_id"] = $row['staff_id'];
            $_SESSION["gallerycafe_staff_name"] = $row['full_name'];
            header('Location: dashboard.php');
        } else {
            $message = "Invalid Contact Number, Password, or Account Inactive!";
        }
        
        // Close the statement
        $stmt->close();
    }
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
  <title>Gallery Café | Staff Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
<div class="container">
  <div class="info">
    <h1>Gallery Café</h1><h2>Staff Login</h2>
  </div>
</div>
<div class="form">
  <div class="thumbnail"><img src="images/manager.png" alt="Manager Image"/></div>
  
  <span style="color:red;"><?php echo $message; ?></span>
  <br>
  <br>
  <form class="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <input type="text" placeholder="Contact Number" name="contact_number" required />
    <input type="password" placeholder="Password" name="password" required />
    <input type="submit" name="submit" value="Login" />
  </form>
</div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='js/index.js'></script>
</body>
</html>
