<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}

# Include connection
require_once "./config.php";

# ดึงข้อมูลผู้ใช้ปัจจุบัน
$id = $_SESSION["id"];
$sql = "SELECT * FROM users WHERE id = ?";

if($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $id);
  
  if(mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    
    if($user_data = mysqli_fetch_assoc($result)) {
      $username = $user_data['username'];
      $email = $user_data['email'];
    }
  }
  
  mysqli_stmt_close($stmt);
}

# ตรวจสอบว่ามีการระบุ patient_id หรือไม่
if (!isset($_GET['patient_id']) || empty($_GET['patient_id'])) {
  echo "<script>" . "window.location.href='./patients.php';" . "</script>";
  exit;
}

$patient_id = intval($_GET['patient_id']);

# ดึงข้อมูลผู้ป่วย
$patient = null;
$sql = "SELECT * FROM patients WHERE id = ?";
        
if($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $patient_id);
  
  if(mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
      $patient = $row;
    } else {
      echo "<script>" . "window.location.href='./patients.php';" . "</script>";
      exit;
    }
  }
  
  mysqli_stmt_close($stmt);
}

# ดึงรายการตรวจที่ต้องการดูกราฟ
$available_items = [];
$sql = "SELECT DISTINCT i.id, i.item_name, i.item_code, i.unit 
        FROM lab_items i 
        JOIN lab_result_details d ON i.id = d.lab_item_id 
        JOIN lab_results l ON d.lab_result_id = l.id 
        WHERE l.patient_id = ? 
        ORDER BY i.item_name";

if($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $patient_id);
  
  if(mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    
    while($row = mysqli_fetch_assoc($result)) {
      $available_items[] = $row;
    }
  }
  
  mysqli_stmt_close($stmt);
}

# ดึงข้อมูลผลการตรวจย้อนหลัง
$selected_item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : (count($available_items) > 0 ? $available_items[0]['id'] : 0);

$chart_data = [];
if ($selected_item_id > 0) {
  $sql = "SELECT d.result_value, d.is_abnormal, l.lab_date 
          FROM lab_result_details d 
          JOIN lab_results l ON d.lab_result_id = l.id 
          WHERE l.patient_id = ? AND d.lab_item_id = ? 
          ORDER BY l.lab_date";
  
  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $patient_id, $selected_item_id);
    
    if(mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      
      while($row = mysqli_fetch_assoc($result)) {
        $chart_data[] = $row;
      }
    }
    
    mysqli_stmt_close($stmt);
  }
}

# ดึงรายละเอียดรายการตรวจที่เลือก
$selected_item = null;
if ($selected_item_id > 0) {
  $sql = "SELECT * FROM lab_items WHERE id = ?";
  
  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $selected_item_id);
    
    if(mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      
      if($row = mysqli_fetch_assoc($result)) {
        $selected_item = $row;
      }
    }
    
    mysqli_stmt_close($stmt);
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
      <div class="mb-6 flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-display font-semibold text-gray-800">กราฟแสดงผลการตรวจย้อนหลัง</h1>
          <p class="text-gray-600">
            ผู้ป่วย: <strong><?= htmlspecialchars($patient['patient_name']); ?></strong> 
            (HN: <?= htmlspecialchars($patient['hn']); ?>)
          </p>
        </div>
        <div>
          <a href="./patients.php" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> กลับ
          </a>
        </div>
      </div>
      
      <!-- เลือกรายการตรวจ -->
      <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-4 bg-primary-500 text-white rounded-t-lg">
          <h2 class="text-lg font-semibold">เลือกรายการตรวจ</h2>
        </div>
        <div class="p-4">
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="flex items-end space-x-4">
            <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
            
            <div class="flex-grow">
              <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">รายการตรวจ</label>
              <select id="item_id" name="item_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <?php foreach ($available_items as $item) : ?>
                <option value="<?= $item['id']; ?>" <?= $selected_item_id == $item['id'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($item['item_name'] . ' (' . $item['item_code'] . ')'); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
              แสดงกราฟ
            </button>
          </form>
        </div>
      </div>
      
      <!-- กราฟแสดงผล -->
      <?php if ($selected_item && count($chart_data) > 0) : ?>
      <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-4 bg-blue-500 text-white rounded-t-lg">
          <h2 class="text-lg font-semibold">
            กราฟแสดงผล: <?= htmlspecialchars($selected_item['item_name']); ?> 
            (<?= htmlspecialchars($selected_item['item_code']); ?>)
          </h2>
        </div>
        <div class="p-4">
          <div style="width: 100%; height: 400px;" id="chart-container"></div>
        </div>
      </div>
      
      <!-- ตารางข้อมูล -->
      <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-4 bg-green-500 text-white rounded-t-lg">
          <h2 class="text-lg font-semibold">ข้อมูลผลการตรวจย้อนหลัง</h2>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่ตรวจ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ผลตรวจ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หน่วย</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php foreach ($chart_data as $item) : ?>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm font-medium text-gray-900">
                    <?= date('d/m/Y', strtotime($item['lab_date'])); ?>
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm <?= $item['is_abnormal'] ? 'font-bold text-red-600' : 'text-gray-900'; ?>">
                    <?= htmlspecialchars($item['result_value']); ?>
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <?= htmlspecialchars($selected_item['unit']); ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <?php if ($item['is_abnormal']) : ?>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    ผิดปกติ
                  </span>
                  <?php else : ?>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    ปกติ
                  </span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php else : ?>
      <div class="bg-white rounded-lg shadow-md mb-6 p-8 text-center">
        <div class="text-gray-500">
          <i class="fas fa-chart-line fa-5x mb-4"></i>
          <p class="text-xl">ไม่มีข้อมูลผลการตรวจสำหรับรายการที่เลือก</p>
        </div>
      </div>
      <?php endif; ?>
    </main>
  </div>
  
  <?php include 'includes/footer.php'; ?>
  
  <!-- เพิ่ม Chart.js สำหรับแสดงกราฟ -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
    $(document).ready(function() {
      <?php if ($selected_item && count($chart_data) > 0) : ?>
      // สร้างกราฟแสดงผล
      const ctx = document.getElementById('chart-container').getContext('2d');
      
      // เตรียมข้อมูลสำหรับกราฟ
      const chartData = {
        labels: [
          <?php foreach ($chart_data as $item) : ?>
          "<?= date('d/m/Y', strtotime($item['lab_date'])); ?>",
          <?php endforeach; ?>
        ],
        datasets: [{
          label: '<?= htmlspecialchars($selected_item['item_name']); ?> (<?= htmlspecialchars($selected_item['unit']); ?>)',
          data: [
            <?php foreach ($chart_data as $item) : ?>
            <?= is_numeric($item['result_value']) ? $item['result_value'] : 'null'; ?>,
            <?php endforeach; ?>
          ],
          borderColor: 'rgb(0, 117, 225)',
          backgroundColor: 'rgba(0, 117, 225, 0.1)',
          tension: 0.2,
          fill: true,
          pointBackgroundColor: [
            <?php foreach ($chart_data as $item) : ?>
            "<?= $item['is_abnormal'] ? 'rgb(239, 68, 68)' : 'rgb(0, 117, 225)'; ?>",
            <?php endforeach; ?>
          ],
          pointRadius: 6,
          pointHoverRadius: 8
        }]
      };
      
      const myChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'ผลการตรวจย้อนหลัง: <?= htmlspecialchars($selected_item['item_name']); ?>'
            },
            tooltip: {
              callbacks: {
                title: function(context) {
                  return context[0].label;
                },
                label: function(context) {
                  return `ผลตรวจ: ${context.raw} ${<?= json_encode($selected_item['unit']); ?>}`;
                },
                footer: function(context) {
                  const dataIndex = context[0].dataIndex;
                  const isAbnormal = <?= json_encode(array_column($chart_data, 'is_abnormal')); ?>[dataIndex];
                  return isAbnormal ? 'สถานะ: ผิดปกติ' : 'สถานะ: ปกติ';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              title: {
                display: true,
                text: '<?= htmlspecialchars($selected_item['unit']); ?>'
              }
            },
            x: {
              title: {
                display: true,
                text: 'วันที่ตรวจ'
              }
            }
          }
        }
      });
      <?php endif; ?>
    });
  </script>
</body>
</html>