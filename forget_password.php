<?php
require 'vendor/autoload.php'; // This autoloads PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'config/db.php';


$step = 1;
$success = '';
$error = '';

// Initialize PHPMailer
$mail = new PHPMailer(true);
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['email_or_phone'])) {
    $email_or_phone = trim($_POST['email_or_phone']);

    // Send OTP via email or phone
    if (filter_var($email_or_phone, FILTER_VALIDATE_EMAIL)) {

      $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->bind_param("s", $email_or_phone);
      $stmt->execute();
      $result = $stmt->get_result();


      if ($result->num_rows > 0) {

        $otp = rand(100000, 999999);

        // Store OTP in session for verification later
        $_SESSION['otp'] = $otp;
        $_SESSION['email_or_phone'] = $email_or_phone;
        $_SESSION['otp_expiry'] = time() + 600; // 10 minutes expiry

        try {
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
          $mail->SMTPAuth = true;
          $mail->Username = 'rushikeshpachore2@gmail.com'; // Your SMTP username
          $mail->Password = 'hbct facv bxeq vzes'; // Your SMTP password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port = 587;

          // Recipients
          $mail->setFrom('your@gmail.com', 'Password Recovery');
          $mail->addAddress($email_or_phone); // Add recipient email


          // Content
          $mail->isHTML(true);
          $mail->Subject = 'Your OTP Code';
          $mail->Body = "Your OTP for password recovery is: <b>$otp</b>. It is valid for 10 minutes.";
          $mail->AltBody =

            $mail->send();
          $step = 2; // Move to OTP verification step
          $success = "OTP has been sent to $email_or_phone. Please check your inbox.";
        } catch (Exception $e) {
          $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
      } else {
        // Email does not exist in the database
        $error = "The email address does not exist in our records.";
      }
    }
  } else {
    // You can integrate SMS API here for phone number OTP
    // Example: send_sms($email_or_phone, "Your OTP is $otp");
    $step = 2; // Move to OTP verification step
  }

  // Step 2: Verify OTP
  if (isset($_POST['otp'])) {
    $entered_otp = trim($_POST['otp']);
    if ($_SESSION['otp'] == $entered_otp && time() <= $_SESSION['otp_expiry']) {
      $step = 3; // OTP is valid, move to reset password step
    } else {
      $error = "Invalid or expired OTP.";
      $step = 2; // Stay on the OTP verification step
    }
  }

  // Step 3: Reset Password
  if (isset($_POST['new_password'], $_POST['confirm_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email_or_phone = $_SESSION['email_or_phone'];
    if ($new_password === $confirm_password) {
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $sql = "UPDATE users SET password = ? WHERE email = ? OR phone = ?";
      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $hashed_password, $email_or_phone, $email_or_phone);
        if (mysqli_stmt_execute($stmt)) {
          echo "<script>
                        alert('Password successfully reset. You can now log in.');
                        window.location.href = 'login.php';
                      </script>";
          session_unset(); // Clear session data
          session_destroy();
          $step = 1; // Reset back to the beginning for new reset requests
        } else {
          $error = "Something went wrong. Please try again.";
          $step = 3; // Stay on reset password step
        }
      }
    } else {
      $error = "Passwords do not match.";
      $step = 3; // Stay on reset password step
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="css/styles.css">
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
  <div class="containerforget">
    <h2>Forgot Password</h2>
    <?php if ($success): ?>
      <div class="success-msg"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Step 1: Request OTP Form -->
    <?php if ($step == 1): ?>
      <form method="post" action="">
        <div class="form-floating mb-3">
          <input type="text" name="email_or_phone" id="email_or_phone" class="form-control" required>
          <label for="email_or_phone">Enter Email or Phone</label>
        </div>
        <button type="submit" class="btnlogin btn-primary">Send OTP</button>
      </form>
    <?php endif; ?>

    <!-- Step 2: Verify OTP Form -->
    <?php if ($step == 2): ?>
      <form method="post" action="">
        <div class="form-floating mb-3">
          <input type="text" name="otp" id="otp" class="form-control" required>
          <label for="otp">Enter OTP</label>
        </div>
        <button type="submit" class="btnlogin btn-primary">Verify OTP</button>
      </form>
    <?php endif; ?>

    <!-- Step 3: Reset Password Form -->
    <?php if ($step == 3): ?>
      <form method="post" action="">
        <div class="form-floating mb-3">
          <input type="password" name="new_password" class="form-control" required>
          <label for="new_password">New Password</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" name="confirm_password" class="form-control" required>
          <label for="confirm_password">Confirm Password</label>
        </div>
        <button type="submit" class="btnlogin btn-primary">Reset Password</button>
      </form>
    <?php endif; ?>
    <div class="card-footer text-center py-3">
      <div class="small">
        <p>Already have an account? <a href="login.php">Login</a></p>
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
</body>

</html>


<!-- 


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Password Reset - SB Admin</title>
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">Password Recovery</h3>
                </div>
                <div class="card-body">
                  <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                  <form>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" />
                      <label for="inputEmail">Email address</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                      <a class="small" href="login.php">Return to login</a>
                      <a class="btnlogin btn-primary" href="login.php">Reset Password</a>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center py-3">
                  <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <div id="layoutAuthentication_footer">
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Your Website 2023</div>
            <div>
              <a href="#">Privacy Policy</a>
              &middot;
              <a href="#">Terms &amp; Conditions</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
</body>

</html> -->