<?php
# Initialize session
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='./'" . "</script>";
  exit;
}

# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["user_login"]))) {
    $user_login_err = "Please enter your username or an email id.";
  } else {
    $user_login = trim($_POST["user_login"]);
  }

  if (empty(trim($_POST["user_password"]))) {
    $user_password_err = "Please enter your password.";
  } else {
    $user_password = trim($_POST["user_password"]);
  }

  # Validate credentials 
  if (empty($user_login_err) && empty($user_password_err)) {
    # Prepare a select statement
    $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind variables to the statement as parameters
      mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

      # Set parameters
      $param_user_login = $user_login;

      # Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        # Store result
        mysqli_stmt_store_result($stmt);

        # Check if user exists, If yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          # Bind values in result to variables
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            # Check if password is correct
            if (password_verify($user_password, $hashed_password)) {

              # Store data in session variables
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["loggedin"] = TRUE;

              # Redirect user to index page
              echo "<script>" . "window.location.href='./'" . "</script>";
              exit;
            } else {
              # If password is incorrect show an error message
              $login_err = "The email or password you entered is incorrect.";
            }
          }
        } else {
          # If user doesn't exists show an error message
          $login_err = "Invalid username or password.";
        }
      } else {
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php'" . "</script>";
        exit;
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - User Management System</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- สไตล์เพิ่มเติม -->
  <link rel="stylesheet" href="./css/main.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- Custom Tailwind Config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#e6f0ff',
              100: '#b8d7fe',
              200: '#8abffd',
              300: '#5ca6fc',
              400: '#2e8efb',
              500: '#0075e1',
              600: '#005cb0',
              700: '#00437f',
              800: '#002a4e',
              900: '#00121f',
            }
          },
          fontFamily: {
            sans: ['Prompt', 'sans-serif'],
            display: ['Mitr', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <!-- JavaScript ดั้งเดิม -->
  <script defer src="./js/script.js"></script>
</head>

<body class="bg-gray-100 font-sans">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
      <?php
      if (!empty($login_err)) {
        echo "<div class='mb-4 p-4 rounded-md bg-red-50 border-l-4 border-red-500 text-red-500'>" . $login_err . "</div>";
      }
      ?>

      <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="bg-primary-500 p-6 text-white">
          <div class="text-center mb-4">
            <img src="./img/favicon-16x16.png" alt="Logo" class="inline-block h-10 w-10">
          </div>
          <h1 class="text-2xl font-semibold text-center">Welcome to Sneat</h1>
          <p class="text-center text-blue-100 mt-1">Please sign-in to your account</p>
        </div>

        <div class="p-6">
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-4">
              <label for="user_login" class="block text-sm font-medium text-gray-700 mb-1">Email or Username</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <i class="fas fa-user"></i>
                </span>
                <input
                  type="text"
                  class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  name="user_login"
                  id="user_login"
                  value="<?= $user_login; ?>"
                  placeholder="Enter your email or username">
              </div>
              <?php if (!empty($user_login_err)) : ?>
                <p class="mt-1 text-sm text-red-500"><?= $user_login_err; ?></p>
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <div class="flex justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <a href="#" class="text-sm text-primary-500 hover:text-primary-600 hover:underline">Forgot Password?</a>
              </div>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <i class="fas fa-lock"></i>
                </span>
                <input
                  type="password"
                  class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  name="user_password"
                  id="password"
                  placeholder="············">
              </div>
              <?php if (!empty($user_password_err)) : ?>
                <p class="mt-1 text-sm text-red-500"><?= $user_password_err; ?></p>
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-primary-500 rounded border-gray-300 focus:ring-primary-500" id="togglePassword">
                <span class="ml-2 text-sm text-gray-700">Show Password</span>
              </label>
            </div>

            <div class="mb-6">
              <button type="submit" class="w-full bg-primary-500 text-white py-2 px-4 rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 transition-colors">
                Sign in
              </button>
            </div>

            <p class="text-center text-gray-600 text-sm">
              New on our platform?
              <a href="./register.php" class="text-primary-500 hover:text-primary-600 hover:underline">Create an account</a>
            </p>
          </form>
        </div>
      </div>

      <div class="text-center mt-6 text-gray-500 text-sm">
        © 2025 Sneat User Management System
      </div>
    </div>
  </div>
</body>

</html>