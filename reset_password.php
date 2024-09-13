<?php
include "config/db.php";
include "inc/header.php";
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: login.php");
  exit();
}
$old_password = $new_password = $confirm_password = "";
$old_password_err = $new_password_err = $confirm_password_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate old password
  if (empty(trim($_POST["old_password"]))) {
    $old_password_err = "Please enter your old password.";
  } else {
    $old_password = trim($_POST["old_password"]);
  }

  // Validate new password
  if (empty(trim($_POST["new_password"]))) {
    $new_password_err = "Please enter a new password.";
  } elseif (strlen(trim($_POST["new_password"])) < 6) {
    $new_password_err = "Password must have at least 6 characters.";
  } else {
    $new_password = trim($_POST["new_password"]);
  }

  // Validate confirm password
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Please confirm the password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($new_password_err) && ($new_password != $confirm_password)) {
      $confirm_password_err = "Passwords did not match.";
    }
  }

  // If no errors, proceed with password update
  if (empty($old_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
    // Get logged-in user's id from session
    $user_id = $_SESSION["user_id"];

    // Query to check if the old password matches the current password in the database
    $sql = "SELECT password FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      // Bind the user id to the prepared statement
      mysqli_stmt_bind_param($stmt, "i", $param_id);
      $param_id = $user_id;

      
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        // Check if the user exists and verify the old password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          // Bind result variables
          mysqli_stmt_bind_result($stmt, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            // Verify old password
            if (password_verify($old_password, $hashed_password)) {
              // Prepare an update statement to change the password
              $sql = "UPDATE users SET password = ? WHERE id = ?";
              if ($stmt = mysqli_prepare($conn, $sql)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                // Bind new password and user id to the prepared statement
                mysqli_stmt_bind_param($stmt, "si", $new_hashed_password, $user_id);


                // Execute the update
                if (mysqli_stmt_execute($stmt)) {
                  // Password updated successfully, destroy session and redirect to login
                  session_destroy();
                  header("Location: login.php");
                  exit();
                } else {
                  echo "Oops! Something went wrong. Please try again later.";
                }
              }
            } else {
              $old_password_err = "The old password you entered is incorrect.";
            }
          } else {
            echo "User does not exist.";
          }
        } else {
          echo "Oops! Something went wrong. Please try again later.";
        }
      }
    }
    // Close the prepared statement
    mysqli_stmt_close($stmt);
  }

  // Close the database connection
  mysqli_close($conn);
}
?>
<main class="main">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">
                    Password Recovery
                  </h3>
                </div>
                <div class="card-body">
                  <div class="small mb-3 text-muted">
                    Reset your password
                  </div>
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-floating mb-3">
                      <input
                        class="form-control"
                        id="oldpassword"
                        type="password"
                        name="old_password"
                        required />
                      <label for="oldpassword">Old Password</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="newpassword"
                        type="password" name="new_password" required />
                      <label for="newpassword">New Password</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="confirmpassword"
                        type="password" name="confirm_password" required />
                      <label for="confirmpassword">Confirm Password</label>
                    </div>
                    <div
                      class="d-flex align-items-center justify-content-center mt-4 mb-0">
                      <button type="submit" class="btnlogin btn-primary">Reset Password</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</main>
<?php
include "inc/footer.php";
?>