<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    redirect("/login.php");
}

# Include connection
require_once "./config.php";

# ดึงข้อมูลผู้ใช้ปัจจุบัน
$id = $_SESSION["id"];
$user_data = null;

$sql = "SELECT * FROM users WHERE id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user_data = mysqli_fetch_assoc($result)) {
            $username = $user_data['username'];
            $email = $user_data['email'];
            $reg_date = $user_data['reg_date'];
        }
    }
    
    mysqli_stmt_close($stmt);
}

# ดึงข้อมูลสถิติต่างๆ
// จำนวนผู้ป่วยทั้งหมด
$total_patients = 0;
$sql = "SELECT COUNT(*) as total FROM patients";
$result = mysqli_query($link, $sql);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $total_patients = $row['total'];
}

// จำนวนผลตรวจวิเคราะห์ทั้งหมด
$total_labs = 0;
$sql = "SELECT COUNT(*) as total FROM lab_results";
$result = mysqli_query($link, $sql);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $total_labs = $row['total'];
}

// จำนวนผู้ป่วยล่าสุด (เพิ่มในรอบ 7 วัน)
$new_patients = 0;
$sql = "SELECT COUNT(*) as total FROM patients WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$result = mysqli_query($link, $sql);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $new_patients = $row['total'];
}

// ดึงข้อมูลผู้ป่วยล่าสุด 5 ราย
$recent_patients = [];
$sql = "SELECT * FROM patients ORDER BY id DESC LIMIT 5";
$result = mysqli_query($link, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recent_patients[] = $row;
    }
}

// ดึงข้อมูลผลตรวจล่าสุด 5 รายการ
$recent_labs = [];
$sql = "SELECT l.*, p.patient_name FROM lab_results l 
        JOIN patients p ON l.patient_id = p.id 
        ORDER BY l.id DESC LIMIT 5";
$result = mysqli_query($link, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recent_labs[] = $row;
    }
}

?>

<?php include 'includes/header.php'; ?>

<body class="bg-gray-100 font-sans">
  <!-- Main Layout -->
  <div class="flex">
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content -->
    <main class="ml-64 flex-1 p-8">
      <div class="mb-6">
        <h1 class="text-3xl font-display font-semibold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">สวัสดี, <?= escape_html($username); ?>! ยินดีต้อนรับกลับมา</p>
      </div>
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-500">จำนวนผู้ป่วยทั้งหมด</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $total_patients ?></h3>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-primary-500">
            <i class="fa fa-users"></i>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-500">ผลตรวจวิเคราะห์</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $total_labs ?></h3>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-500">
            <i class="fa fa-flask"></i>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-500">ผู้ป่วยใหม่ (7 วัน)</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $new_patients ?></h3>
          </div>
          <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-500">
            <i class="fa fa-user-plus"></i>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-500">สถานะระบบ</p>
            <h3 class="text-2xl font-bold text-gray-800">พร้อมใช้งาน</h3>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-500">
            <i class="fa fa-server"></i>
          </div>
        </div>
      </div>
      
      <!-- User Profile Card -->
      <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800">ข้อมูลผู้ใช้งาน</h2>
        </div>
        <div class="p-6">
          <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3 text-center mb-6 md:mb-0">
              <img src="./img/blank-avatar.jpg" class="w-32 h-32 rounded-full mx-auto" alt="User avatar">
              <h3 class="mt-4 text-lg font-semibold"><?= escape_html($username); ?></h3>
              <p class="text-sm text-gray-500"><?= escape_html($email); ?></p>
              <button class="mt-4 px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
                แก้ไขโปรไฟล์
              </button>
            </div>
            <div class="md:w-2/3 md:pl-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <p class="text-sm text-gray-500">ชื่อผู้ใช้</p>
                  <p class="font-semibold"><?= escape_html($username); ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">อีเมล</p>
                  <p class="font-semibold"><?= escape_html($email); ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">วันที่ลงทะเบียน</p>
                  <p class="font-semibold"><?= escape_html($reg_date); ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">รหัสผู้ใช้</p>
                  <p class="font-semibold">#<?= escape_html($id); ?></p>
                </div>
              </div>
              <div class="mt-6">
                <h4 class="text-lg font-semibold mb-2">เกี่ยวกับ</h4>
                <p class="text-gray-600">
                  นี่คือข้อมูลโปรไฟล์ของคุณ คุณสามารถแก้ไขข้อมูลโปรไฟล์ได้จากปุ่ม "แก้ไขโปรไฟล์"
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Recent Data -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Recent Patients -->
        <div class="bg-white rounded-lg shadow-md">
          <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">ผู้ป่วยล่าสุด</h2>
            <a href="./patients.php" class="text-primary-500 hover:text-primary-600">
              ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
          <div class="p-6">
            <div class="divide-y divide-gray-200">
              <?php foreach ($recent_patients as $patient) : ?>
              <div class="py-3 flex items-center justify-between">
                <div class="flex items-center">
                  <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-500 mr-3">
                    <i class="fas fa-user"></i>
                  </div>
                  <div>
                    <p class="font-medium"><?= escape_html($patient['patient_name']); ?></p>
                    <p class="text-sm text-gray-500">HN: <?= escape_html($patient['hn']); ?></p>
                  </div>
                </div>
                <a href="patients.php?view=<?= $patient['id']; ?>" class="text-blue-500 hover:text-blue-700">
                  <i class="fas fa-eye"></i>
                </a>
              </div>
              <?php endforeach; ?>
              
              <?php if (count($recent_patients) === 0) : ?>
              <div class="py-4 text-center text-gray-500">
                ไม่มีข้อมูลผู้ป่วย
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <!-- Recent Lab Results -->
        <div class="bg-white rounded-lg shadow-md">
          <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">ผลตรวจล่าสุด</h2>
            <a href="./lab_results.php" class="text-primary-500 hover:text-primary-600">
              ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
          <div class="p-6">
            <div class="divide-y divide-gray-200">
              <?php foreach ($recent_labs as $lab) : ?>
              <div class="py-3 flex items-center justify-between">
                <div class="flex items-center">
                  <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 mr-3">
                    <i class="fas fa-flask"></i>
                  </div>
                  <div>
                    <p class="font-medium"><?= escape_html($lab['patient_name']); ?></p>
                    <p class="text-sm text-gray-500">
                      <?= escape_html($lab['lab_type']); ?> - 
                      <?= escape_html(date('d/m/Y', strtotime($lab['lab_date']))); ?>
                    </p>
                  </div>
                </div>
                <a href="lab_results.php?view=<?= $lab['id']; ?>" class="text-blue-500 hover:text-blue-700">
                  <i class="fas fa-eye"></i>
                </a>
              </div>
              <?php endforeach; ?>
              
              <?php if (count($recent_labs) === 0) : ?>
              <div class="py-4 text-center text-gray-500">
                ไม่มีข้อมูลผลตรวจวิเคราะห์
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Quick Links -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800">ทางลัด</h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="./patients.php" class="bg-primary-50 rounded-lg p-4 flex items-center hover:bg-primary-100 transition-colors">
              <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-500 mr-3">
                <i class="fas fa-hospital-user"></i>
              </div>
              <div>
                <p class="font-medium">จัดการผู้ป่วย</p>
                <p class="text-sm text-gray-500">ดูและจัดการข้อมูลผู้ป่วย</p>
              </div>
            </a>
            <a href="./lab_results.php" class="bg-green-50 rounded-lg p-4 flex items-center hover:bg-green-100 transition-colors">
              <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 mr-3">
                <i class="fas fa-flask"></i>
              </div>
              <div>
                <p class="font-medium">ผลตรวจวิเคราะห์</p>
                <p class="text-sm text-gray-500">จัดการผลการตรวจวิเคราะห์</p>
              </div>
            </a>
            <a href="#" class="bg-yellow-50 rounded-lg p-4 flex items-center hover:bg-yellow-100 transition-colors">
              <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500 mr-3">
                <i class="fas fa-chart-line"></i>
              </div>
              <div>
                <p class="font-medium">รายงาน</p>
                <p class="text-sm text-gray-500">ดูรายงานและสถิติต่างๆ</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </main>
  </div>
  
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/scripts/dashboard_scripts.php'; ?>
</body>
</html>