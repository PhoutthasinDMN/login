<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    redirect("./login.php");
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
        }
        mysqli_free_result($result);
    }
    
    mysqli_stmt_close($stmt);
}

# ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    # เพิ่มผลการตรวจวิเคราะห์ใหม่
    if ($_POST["action"] == "add") {

        $patient_id = trim($_POST["patient_id"]);
        $lab_date = trim($_POST["lab_date"]);
        $lab_type = trim($_POST["lab_type"]);
        $lab_details = trim($_POST["lab_details"]);
        $blood_pressure = trim($_POST["blood_pressure"]);
        $pulse = !empty($_POST["pulse"]) ? trim($_POST["pulse"]) : null;
        $temperature = !empty($_POST["temperature"]) ? trim($_POST["temperature"]) : null;
        $weight = !empty($_POST["weight"]) ? trim($_POST["weight"]) : null;
        $height = !empty($_POST["height"]) ? trim($_POST["height"]) : null;
        $glucose = !empty($_POST["glucose"]) ? trim($_POST["glucose"]) : null;
        $cholesterol = !empty($_POST["cholesterol"]) ? trim($_POST["cholesterol"]) : null;
        $note = trim($_POST["note"]);
        $created_by = $id; // user_id

        # คำนวณ BMI
        $bmi = null;
        if (!empty($weight) && !empty($height) && $height > 0) {
            $height_m = $height / 100; // แปลงส่วนสูงจาก cm เป็น m
            $bmi = round($weight / ($height_m * $height_m), 2);
        }

        # ตรวจสอบว่าไม่มีช่องว่างเปล่าที่จำเป็น
        if (!empty($patient_id) && !empty($lab_date) && !empty($lab_type)) {
            # ใช้ Prepared Statement เพื่อความปลอดภัย
            $sql = "INSERT INTO lab_results (patient_id, lab_date, lab_type, lab_details, blood_pressure, pulse, temperature, weight, height, bmi, glucose, cholesterol, created_by, note) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param(
                    $stmt, 
                    "issssdddddddis", 
                    $patient_id,          // i - integer 
                    $lab_date,            // s - string
                    $lab_type,            // s - string
                    $lab_details,         // s - string
                    $blood_pressure,      // s - string
                    $pulse,               // d - double (numeric)
                    $temperature,         // d - double
                    $weight,              // d - double
                    $height,              // d - double
                    $bmi,                 // d - double
                    $glucose,             // d - double
                    $cholesterol,         // d - double
                    $created_by,          // i - integer
                    $note                 // s - string
                );

                if (mysqli_stmt_execute($stmt)) {
                    # เพิ่มข้อมูลสำเร็จ
                    redirect("./lab_results.php?success=add");
                } else {
                    # เกิดข้อผิดพลาด
                    redirect("./lab_results.php?error=add&message=" . mysqli_error($link));
                }

                mysqli_stmt_close($stmt);
            } else {
                # เกิดข้อผิดพลาดในการสร้าง Prepared Statement
                redirect("./lab_results.php?error=prepare&message=" . mysqli_error($link));
            }
        } else {
            # ข้อมูลจำเป็นไม่ครบถ้วน
            redirect("./lab_results.php?error=validation");
        }
    }

    # แก้ไขผลการตรวจวิเคราะห์
    elseif ($_POST["action"] == "edit") {

        $lab_id = $_POST["lab_id"];
        $patient_id = trim($_POST["patient_id"]);
        $lab_date = trim($_POST["lab_date"]);
        $lab_type = trim($_POST["lab_type"]);
        $lab_details = trim($_POST["lab_details"]);
        $blood_pressure = trim($_POST["blood_pressure"]);
        $pulse = !empty($_POST["pulse"]) ? trim($_POST["pulse"]) : null;
        $temperature = !empty($_POST["temperature"]) ? trim($_POST["temperature"]) : null;
        $weight = !empty($_POST["weight"]) ? trim($_POST["weight"]) : null;
        $height = !empty($_POST["height"]) ? trim($_POST["height"]) : null;
        $glucose = !empty($_POST["glucose"]) ? trim($_POST["glucose"]) : null;
        $cholesterol = !empty($_POST["cholesterol"]) ? trim($_POST["cholesterol"]) : null;
        $note = trim($_POST["note"]);

        # คำนวณ BMI
        $bmi = null;
        if (!empty($weight) && !empty($height) && $height > 0) {
            $height_m = $height / 100; // แปลงส่วนสูงจาก cm เป็น m
            $bmi = round($weight / ($height_m * $height_m), 2);
        }

        # ตรวจสอบว่าไม่มีช่องว่างเปล่าที่จำเป็น
        if (!empty($patient_id) && !empty($lab_date) && !empty($lab_type)) {
            # ใช้ Prepared Statement เพื่อความปลอดภัย
            $sql = "UPDATE lab_results SET 
                    patient_id = ?, 
                    lab_date = ?, 
                    lab_type = ?, 
                    lab_details = ?, 
                    blood_pressure = ?, 
                    pulse = ?, 
                    temperature = ?, 
                    weight = ?, 
                    height = ?, 
                    bmi = ?, 
                    glucose = ?, 
                    cholesterol = ?, 
                    note = ? 
                    WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param(
                    $stmt, 
                    "issssdddddddsi", 
                    $patient_id,          // i - integer
                    $lab_date,            // s - string  
                    $lab_type,            // s - string
                    $lab_details,         // s - string
                    $blood_pressure,      // s - string
                    $pulse,               // d - double
                    $temperature,         // d - double
                    $weight,              // d - double
                    $height,              // d - double
                    $bmi,                 // d - double
                    $glucose,             // d - double
                    $cholesterol,         // d - double
                    $note,                // s - string
                    $lab_id               // i - integer
                );

                if (mysqli_stmt_execute($stmt)) {
                    # แก้ไขข้อมูลสำเร็จ
                    redirect("./lab_results.php?success=edit");
                } else {
                    # เกิดข้อผิดพลาด
                    redirect("./lab_results.php?error=edit&message=" . mysqli_error($link));
                }

                mysqli_stmt_close($stmt);
            } else {
                # เกิดข้อผิดพลาดในการสร้าง Prepared Statement
                redirect("./lab_results.php?error=prepare&message=" . mysqli_error($link));
            }
        } else {
            # ข้อมูลจำเป็นไม่ครบถ้วน
            redirect("./lab_results.php?error=validation");
        }
    }

    # ลบผลการตรวจวิเคราะห์
    elseif ($_POST["action"] == "delete") {
        $lab_id = $_POST["lab_id"];

        # เตรียม SQL สำหรับลบข้อมูล
        $sql = "DELETE FROM lab_results WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $lab_id);

            if (mysqli_stmt_execute($stmt)) {
                # ลบข้อมูลสำเร็จ
                redirect("./lab_results.php?success=delete");
            } else {
                # เกิดข้อผิดพลาด
                redirect("./lab_results.php?error=delete&message=" . mysqli_error($link));
            }

            mysqli_stmt_close($stmt);
        } else {
            # เกิดข้อผิดพลาดในการสร้าง Prepared Statement
            redirect("./lab_results.php?error=prepare&message=" . mysqli_error($link));
        }
    }
}

# ตรวจสอบการกรองตามผู้ป่วย
$filter_patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
$patient_filter_name = "";

if ($filter_patient_id > 0) {
    # ดึงข้อมูลผลการตรวจเฉพาะผู้ป่วยที่ระบุ
    $lab_results = []; // ต้องเริ่มต้นด้วยอาร์เรย์ว่าง
    $sql = "SELECT l.*, p.patient_name, p.hn FROM lab_results l JOIN patients p ON l.patient_id = p.id WHERE l.patient_id = ? ORDER BY l.lab_date DESC";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $filter_patient_id);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $lab_results[] = $row;
                if (empty($patient_filter_name)) {
                    $patient_filter_name = $row['patient_name'];
                }
            }
            mysqli_free_result($result);
        }

        mysqli_stmt_close($stmt);
    }
} else {
    # ดึงข้อมูลผลการตรวจทั้งหมด
    $lab_results = [];
    $sql = "SELECT l.*, p.patient_name, p.hn FROM lab_results l JOIN patients p ON l.patient_id = p.id ORDER BY l.lab_date DESC";
    $result = mysqli_query($link, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lab_results[] = $row;
        }
        mysqli_free_result($result);
    }
}

# ดึงข้อมูลผู้ป่วยทั้งหมดสำหรับเลือกในฟอร์ม
$patients = [];
$sql = "SELECT id, patient_name, hn FROM patients ORDER BY patient_name";
$result = mysqli_query($link, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row;
    }
    mysqli_free_result($result);
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
                    <?php if (!empty($patient_filter_name)) : ?>
                        <p class="text-gray-600">กำลังแสดงผลการตรวจวิเคราะห์ของ: <strong><?= escape_html($patient_filter_name) ?></strong> <a href="lab_results.php" class="text-primary-500 hover:underline">(แสดงทั้งหมด)</a></p>
                    <?php else : ?>
                        <p class="text-gray-600">บันทึกและจัดการผลการตรวจวิเคราะห์ของผู้ป่วย</p>
                    <?php endif; ?>
                </div>
                <button id="addLabResultBtn" class="bg-primary-500 hover:bg-primary-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i> เพิ่มผลตรวจใหม่
                </button>
            </div>

            <!-- Lab Results Table Card -->
            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="p-6">
                    <table id="labResultsTable" class="display w-full">
                        <thead>
                            <tr>
                                <th>วันที่ตรวจ</th>
                                <th>ชื่อผู้ป่วย</th>
                                <th>HN</th>
                                <th>ประเภทการตรวจ</th>
                                <th>รายการตรวจ</th>
                                <th>ความผิดปกติ</th>
                                <th>น้ำหนัก/ส่วนสูง</th>
                                <th>ความดัน</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lab_results as $lab) : ?>
                                <?php
                                // ดึงจำนวนรายการตรวจและจำนวนรายการผิดปกติ
                                $lab_id = $lab['id'];
                                $total_items = 0;
                                $abnormal_items = 0;

                                $sql = "SELECT COUNT(*) as total, SUM(CASE WHEN is_abnormal = 1 THEN 1 ELSE 0 END) as abnormal FROM lab_result_details WHERE lab_result_id = ?";
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    mysqli_stmt_bind_param($stmt, "i", $lab_id);

                                    if (mysqli_stmt_execute($stmt)) {
                                        $result = mysqli_stmt_get_result($stmt);

                                        if ($row = mysqli_fetch_assoc($result)) {
                                            $total_items = $row['total'];
                                            $abnormal_items = $row['abnormal'] ?? 0;
                                        }
                                        mysqli_free_result($result);
                                    }

                                    mysqli_stmt_close($stmt);
                                }
                                ?>
                                <tr>
                                    <td><?= escape_html(date('d/m/Y', strtotime($lab['lab_date']))); ?></td>
                                    <td><?= escape_html($lab['patient_name']); ?></td>
                                    <td><?= escape_html($lab['hn']); ?></td>
                                    <td><?= escape_html($lab['lab_type']); ?></td>
                                    <td>
                                        <?php if ($total_items > 0) : ?>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                <?= $total_items ?> รายการ
                                            </span>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($abnormal_items > 0) : ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                <?= $abnormal_items ?> รายการ
                                            </span>
                                        <?php else : ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                ปกติ
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= escape_html($lab['weight'] ? $lab['weight'] . ' กก. / ' . $lab['height'] . ' ซม.' : '-'); ?></td>
                                    <td><?= escape_html($lab['blood_pressure'] ?: '-'); ?></td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <a
                                                href="lab_view.php?id=<?= $lab['id']; ?>"
                                                class="text-blue-500 hover:text-blue-700"
                                                title="ดูผลตรวจโดยละเอียด">
                                                <i class="fas fa-file-medical-alt"></i>
                                            </a>
                                            <button
                                                class="view-lab text-blue-500 hover:text-blue-700"
                                                data-id="<?= $lab['id']; ?>"
                                                data-patient-id="<?= $lab['patient_id']; ?>"
                                                data-patient-name="<?= escape_html($lab['patient_name']); ?>"
                                                data-hn="<?= escape_html($lab['hn']); ?>"
                                                data-lab-date="<?= escape_html($lab['lab_date']); ?>"
                                                data-lab-type="<?= escape_html($lab['lab_type']); ?>"
                                                data-lab-details="<?= escape_html($lab['lab_details']); ?>"
                                                data-blood-pressure="<?= escape_html($lab['blood_pressure']); ?>"
                                                data-pulse="<?= escape_html($lab['pulse']); ?>"
                                                data-temperature="<?= escape_html($lab['temperature']); ?>"
                                                data-weight="<?= escape_html($lab['weight']); ?>"
                                                data-height="<?= escape_html($lab['height']); ?>"
                                                data-bmi="<?= escape_html($lab['bmi']); ?>"
                                                data-glucose="<?= escape_html($lab['glucose']); ?>"
                                                data-cholesterol="<?= escape_html($lab['cholesterol']); ?>"
                                                data-note="<?= escape_html($lab['note']); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="edit-lab text-yellow-500 hover:text-yellow-700"
                                                data-id="<?= $lab['id']; ?>"
                                                data-patient-id="<?= $lab['patient_id']; ?>"
                                                data-lab-date="<?= escape_html($lab['lab_date']); ?>"
                                                data-lab-type="<?= escape_html($lab['lab_type']); ?>"
                                                data-lab-details="<?= escape_html($lab['lab_details']); ?>"
                                                data-blood-pressure="<?= escape_html($lab['blood_pressure']); ?>"
                                                data-pulse="<?= escape_html($lab['pulse']); ?>"
                                                data-temperature="<?= escape_html($lab['temperature']); ?>"
                                                data-weight="<?= escape_html($lab['weight']); ?>"
                                                data-height="<?= escape_html($lab['height']); ?>"
                                                data-glucose="<?= escape_html($lab['glucose']); ?>"
                                                data-cholesterol="<?= escape_html($lab['cholesterol']); ?>"
                                                data-note="<?= escape_html($lab['note']); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="delete-lab text-red-500 hover:text-red-700"
                                                data-id="<?= $lab['id']; ?>"
                                                data-patient-name="<?= escape_html($lab['patient_name']); ?>"
                                                data-lab-date="<?= escape_html($lab['lab_date']); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Include Modals -->
    <?php include 'includes/modals/lab_add_modal.php'; ?>
    <?php include 'includes/modals/lab_edit_modal.php'; ?>
    <?php include 'includes/modals/lab_view_modal.php'; ?>

    <!-- Hidden Form for Delete -->
    <form id="deleteLabForm" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="hidden">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" id="delete_lab_id" name="lab_id">
    </form>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts/lab_scripts.php'; ?>
</body>

</html>
<?php
// ปิดการเชื่อมต่อกับฐานข้อมูล
mysqli_close($link);
?>