<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    redirect("./login.php");
}

# Include connection
require_once "./config.php";

# ตรวจสอบว่ามีการระบุ lab_id หรือไม่
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect("./lab_results.php");
}

$lab_id = intval($_GET['id']);

# ดึงข้อมูลผู้ใช้ปัจจุบัน
$id = $_SESSION["id"];
$user_data = null;

$sql = "SELECT * FROM users WHERE id = ?";

if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        if($user_data = mysqli_fetch_assoc($result)) {
            $username = $user_data['username'];
            $email = $user_data['email'];
        }
        mysqli_free_result($result);
    }
    
    mysqli_stmt_close($stmt);
}

# ดึงข้อมูลผลการตรวจ
$lab_result = null;
$sql = "SELECT l.*, p.patient_name, p.hn FROM lab_results l 
        JOIN patients p ON l.patient_id = p.id 
        WHERE l.id = ?";
        
if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $lab_id);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($result)) {
            $lab_result = $row;
        } else {
            redirect("./lab_results.php");
        }
        mysqli_free_result($result);
    }
    
    mysqli_stmt_close($stmt);
}

# ดึงหมวดหมู่ที่มีผลการตรวจ
$lab_categories = [];
$sql = "SELECT DISTINCT c.* FROM lab_categories c 
        JOIN lab_items i ON c.id = i.category_id 
        JOIN lab_result_details d ON i.id = d.lab_item_id 
        WHERE d.lab_result_id = ?
        ORDER BY c.order_number, c.category_name";

if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $lab_id);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        while($row = mysqli_fetch_assoc($result)) {
            $lab_categories[] = $row;
        }
        mysqli_free_result($result);
    }
    
    mysqli_stmt_close($stmt);
}

# ดึงรายละเอียดผลการตรวจแยกตามหมวดหมู่
$lab_details = [];
foreach ($lab_categories as $category) {
    $category_id = $category['id'];
    
    $sql = "SELECT d.*, i.item_name, i.item_code, i.unit, i.normal_range FROM lab_result_details d 
            JOIN lab_items i ON d.lab_item_id = i.id 
            WHERE d.lab_result_id = ? AND i.category_id = ?
            ORDER BY i.order_number, i.item_name";
    
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $lab_id, $category_id);
        
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            $lab_details[$category_id] = [];
            while($row = mysqli_fetch_assoc($result)) {
                $lab_details[$category_id][] = $row;
            }
            mysqli_free_result($result);
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
                    <h1 class="text-3xl font-display font-semibold text-gray-800">ผลการตรวจวิเคราะห์</h1>
                    <p class="text-gray-600">
                        ผู้ป่วย: <strong><?= escape_html($lab_result['patient_name']); ?></strong> 
                        (HN: <?= escape_html($lab_result['hn']); ?>)
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button id="printLabBtn" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-print mr-2"></i> พิมพ์
                    </button>
                    <a href="./lab_results.php" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> กลับ
                    </a>
                </div>
            </div>
            
            <!-- Lab Results -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- ข้อมูลทั่วไป -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-4 bg-primary-500 text-white rounded-t-lg">
                        <h2 class="text-lg font-semibold">ข้อมูลทั่วไป</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">วันที่ตรวจ</p>
                                <p class="font-semibold"><?= date('d/m/Y', strtotime($lab_result['lab_date'])); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">ประเภทการตรวจ</p>
                                <p class="font-semibold"><?= escape_html($lab_result['lab_type']); ?></p>
                            </div>
                            <?php if (!empty($lab_result['lab_details'])) : ?>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500">รายละเอียด</p>
                                <p class="font-semibold"><?= escape_html($lab_result['lab_details']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- สัญญาณชีพ -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-4 bg-blue-500 text-white rounded-t-lg">
                        <h2 class="text-lg font-semibold">สัญญาณชีพ</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!empty($lab_result['blood_pressure'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">ความดันโลหิต</p>
                                <p class="font-semibold"><?= escape_html($lab_result['blood_pressure']); ?> มม.ปรอท</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($lab_result['pulse'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">ชีพจร</p>
                                <p class="font-semibold"><?= escape_html($lab_result['pulse']); ?> ครั้ง/นาที</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($lab_result['temperature'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">อุณหภูมิร่างกาย</p>
                                <p class="font-semibold"><?= escape_html($lab_result['temperature']); ?> °C</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- น้ำหนักและส่วนสูง -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-4 bg-green-500 text-white rounded-t-lg">
                        <h2 class="text-lg font-semibold">น้ำหนักและส่วนสูง</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!empty($lab_result['weight'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">น้ำหนัก</p>
                                <p class="font-semibold"><?= escape_html($lab_result['weight']); ?> กก.</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($lab_result['height'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">ส่วนสูง</p>
                                <p class="font-semibold"><?= escape_html($lab_result['height']); ?> ซม.</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($lab_result['bmi'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">ดัชนีมวลกาย (BMI)</p>
                                <p class="font-semibold"><?= escape_html($lab_result['bmi']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- การตรวจเลือดเบื้องต้น -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-4 bg-red-500 text-white rounded-t-lg">
                        <h2 class="text-lg font-semibold">การตรวจเลือดเบื้องต้น</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!empty($lab_result['glucose'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">น้ำตาลในเลือด</p>
                                <p class="font-semibold"><?= escape_html($lab_result['glucose']); ?> มก./ดล.</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($lab_result['cholesterol'])) : ?>
                            <div>
                                <p class="text-sm text-gray-500">คอเลสเตอรอล</p>
                                <p class="font-semibold"><?= escape_html($lab_result['cholesterol']); ?> มก./ดล.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ผลการตรวจตามหมวดหมู่ -->
            <?php foreach ($lab_categories as $category) : ?>
                <?php 
                    $category_id = $category['id'];
                    $category_items = isset($lab_details[$category_id]) ? $lab_details[$category_id] : [];
                ?>
                <?php if (!empty($category_items)) : ?>
                <div class="bg-white rounded-lg shadow-md mb-6">
                    <div class="p-4 bg-yellow-500 text-white rounded-t-lg">
                        <h2 class="text-lg font-semibold"><?= escape_html($category['category_name']); ?></h2>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รายการตรวจ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รหัส</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ผลตรวจ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หน่วย</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ค่าปกติ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($category_items as $item) : ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900"><?= escape_html($item['item_name']); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= escape_html($item['item_code']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm <?= $item['is_abnormal'] ? 'font-bold text-red-600' : 'text-gray-900'; ?>">
                                            <?= escape_html($item['result_value']); ?>
                                        </span>
                                        <?php if ($item['is_abnormal']) : ?>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ผิดปกติ
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= escape_html($item['unit']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= escape_html($item['normal_range']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= escape_html($item['note']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- บันทึกเพิ่มเติม -->
            <?php if (!empty($lab_result['note'])) : ?>
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="p-4 bg-purple-500 text-white rounded-t-lg">
                    <h2 class="text-lg font-semibold">บันทึกเพิ่มเติม</h2>
                </div>
                <div class="p-4">
                    <p class="text-gray-800"><?= nl2br(escape_html($lab_result['note'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        $(document).ready(function() {
            // พิมพ์ผลการตรวจ
            $('#printLabBtn').click(function() {
                const patientName = "<?= escape_html($lab_result['patient_name']); ?>";
                const hn = "<?= escape_html($lab_result['hn']); ?>";
                const labDate = "<?= date('d/m/Y', strtotime($lab_result['lab_date'])); ?>";
                const labType = "<?= escape_html($lab_result['lab_type']); ?>";
                
                window.print();
            });
        });
    </script>
</body>
</html>
<?php
// ปิดการเชื่อมต่อกับฐานข้อมูล
mysqli_close($link);
?>