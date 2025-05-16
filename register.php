<?php
# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$username_err = $email_err = $password_err = "";
$username = $email = $password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    # Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "กรุณากรอกชื่อผู้ใช้";
    } else {
        $username = trim($_POST["username"]);
        if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
            $username_err = "ชื่อผู้ใช้ต้องประกอบด้วยตัวอักษร ตัวเลข และสัญลักษณ์ '@', '_', หรือ '-' เท่านั้น";
        } else {
            # Prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                # Bind variables to the statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                # Set parameters
                $param_username = $username;

                # Execute the prepared statement 
                if (mysqli_stmt_execute($stmt)) {
                    # Store result
                    mysqli_stmt_store_result($stmt);

                    # Check if username is already registered
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "ชื่อผู้ใช้นี้ถูกใช้งานแล้ว";
                    }
                } else {
                    $username_err = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้งในภายหลัง";
                }

                # Close statement 
                mysqli_stmt_close($stmt);
            }
        }
    }

    # Validate email 
    if (empty(trim($_POST["email"]))) {
        $email_err = "กรุณากรอกอีเมล";
    } else {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "กรุณากรอกอีเมลที่ถูกต้อง";
        } else {
            # Prepare a select statement
            $sql = "SELECT id FROM users WHERE email = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                # Bind variables to the statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                # Set parameters
                $param_email = $email;

                # Execute the prepared statement 
                if (mysqli_stmt_execute($stmt)) {
                    # Store result
                    mysqli_stmt_store_result($stmt);

                    # Check if email is already registered
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $email_err = "อีเมลนี้ถูกใช้งานแล้ว";
                    }
                } else {
                    $email_err = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้งในภายหลัง";
                }

                # Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    # Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "กรุณากรอกรหัสผ่าน";
    } else {
        $password = trim($_POST["password"]);
        if (strlen($password) < 8) {
            $password_err = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
        }
    }

    # Check input errors before inserting data into database
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        # Prepare an insert statement
        $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            # Bind varibales to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

            # Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            # Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>" . "alert('ลงทะเบียนสำเร็จ กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ');" . "</script>";
                echo "<script>" . "window.location.href='./login.php';" . "</script>";
                exit;
            } else {
                $register_err = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้งในภายหลัง";
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
  <title>Register - User Management System</title>

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
      <?php if (isset($register_err)) : ?>
        <div class="mb-4 p-4 rounded-md bg-red-50 border-l-4 border-red-500 text-red-500">
          <?= $register_err; ?>
        </div>
      <?php endif; ?>

      <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="bg-primary-500 p-6 text-white">
          <div class="text-center mb-4">
            <img src="./img/favicon-16x16.png" alt="Logo" class="inline-block h-10 w-10">
          </div>
          <h1 class="text-2xl font-semibold text-center">เริ่มการผจญภัยที่นี่</h1>
          <p class="text-center text-blue-100 mt-1">สร้างบัญชีผู้ใช้และเริ่มการผจญภัย</p>
        </div>

        <div class="p-6">
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-4">
              <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <i class="fas fa-user"></i>
                </span>
                <input
                  type="text"
                  class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  name="username"
                  id="username"
                  value="<?= escape_html($username); ?>"
                  placeholder="johndoe">
              </div>
              <?php if (!empty($username_err)) : ?>
                <p class="mt-1 text-sm text-red-500"><?= $username_err; ?></p>
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <i class="fas fa-envelope"></i>
                </span>
                <input
                  type="email"
                  class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  name="email"
                  id="email"
                  value="<?= escape_html($email); ?>"
                  placeholder="john@example.com">
              </div>
              <?php if (!empty($email_err)) : ?>
                <p class="mt-1 text-sm text-red-500"><?= $email_err; ?></p>
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <i class="fas fa-lock"></i>
                </span>
                <input
                  type="password"
                  class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  name="password"
                  id="password"
                  placeholder="············">
              </div>
              <?php if (!empty($password_err)) : ?>
                <p class="mt-1 text-sm text-red-500"><?= $password_err; ?></p>
              <?php endif; ?>
              <p class="mt-1 text-xs text-gray-500">รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร</p>
            </div>

            <div class="mb-4">
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-primary-500 rounded border-gray-300 focus:ring-primary-500" id="togglePassword">
                <span class="ml-2 text-sm text-gray-700">แสดงรหัสผ่าน</span>
              </label>
            </div>

            <div class="mb-6">
              <button type="submit" class="w-full bg-primary-500 text-white py-2 px-4 rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 transition-colors">
                ลงทะเบียน
              </button>
            </div>

            <p class="text-center text-gray-600 text-sm">
              มีบัญชีผู้ใช้อยู่แล้ว?
              <a href="./login.php" class="text-primary-500 hover:text-primary-600 hover:underline">เข้าสู่ระบบ</a>
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