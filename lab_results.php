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

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if ($user_data = mysqli_fetch_assoc($result)) {
            $username = $user_data['username'];
            $email = $user_data['email'];
        }
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
        $pulse = trim($_POST["pulse"]);
        $temperature = trim($_POST["temperature"]);
        $weight = trim($_POST["weight"]);
        $height = trim($_POST["height"]);
        $glucose = trim($_POST["glucose"]);
        $cholesterol = trim($_POST["cholesterol"]);
        $note = trim($_POST["note"]);

        # คำนวณ BMI
        $bmi = 0;
        if (!empty($weight) && !empty($height) && $height > 0) {
            $height_m = $height / 100; // แปลงส่วนสูงจาก cm เป็น m
            $bmi = round($weight / ($height_m * $height_m), 2);
        }

        # ตรวจสอบว่าไม่มีช่องว่างเปล่าที่จำเป็น
        if (!empty($patient_id) && !empty($lab_date) && !empty($lab_type)) {

            # ใช้วิธีที่ง่ายกว่าแทน - ไม่ใช้ Prepared Statement
            $patient_id = intval($patient_id);
            $lab_date = mysqli_real_escape_string($link, $lab_date);
            $lab_type = mysqli_real_escape_string($link, $lab_type);
            $lab_details = mysqli_real_escape_string($link, $lab_details);
            $blood_pressure = mysqli_real_escape_string($link, $blood_pressure);
            $pulse = intval($pulse);
            $temperature = floatval($temperature);
            $weight = floatval($weight);
            $height = floatval($height);
            $bmi = floatval($bmi);
            $glucose = floatval($glucose);
            $cholesterol = floatval($cholesterol);
            $created_by = intval($id);
            $note = mysqli_real_escape_string($link, $note);

            $sql = "INSERT INTO lab_results (patient_id, lab_date, lab_type, lab_details, blood_pressure, pulse, temperature, weight, height, bmi, glucose, cholesterol, created_by, note) 
              VALUES ($patient_id, '$lab_date', '$lab_type', '$lab_details', '$blood_pressure', $pulse, $temperature, $weight, $height, $bmi, $glucose, $cholesterol, $created_by, '$note')";

            if (mysqli_query($link, $sql)) {
                # เพิ่มข้อมูลสำเร็จ
                echo "<script>" . "window.location.href='./lab_results.php?success=add';" . "</script>";
                exit;
            } else {
                # เกิดข้อผิดพลาด
                echo "Error: " . mysqli_error($link);
                exit;
            }
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
        $pulse = trim($_POST["pulse"]);
        $temperature = trim($_POST["temperature"]);
        $weight = trim($_POST["weight"]);
        $height = trim($_POST["height"]);
        $glucose = trim($_POST["glucose"]);
        $cholesterol = trim($_POST["cholesterol"]);
        $note = trim($_POST["note"]);

        # คำนวณ BMI
        $bmi = 0;
        if (!empty($weight) && !empty($height) && $height > 0) {
            $height_m = $height / 100; // แปลงส่วนสูงจาก cm เป็น m
            $bmi = round($weight / ($height_m * $height_m), 2);
        }

        # ตรวจสอบว่าไม่มีช่องว่างเปล่าที่จำเป็น
        if (!empty($patient_id) && !empty($lab_date) && !empty($lab_type)) {

            # ใช้วิธีที่ง่ายกว่าแทน - ไม่ใช้ Prepared Statement
            $lab_id = intval($lab_id);
            $patient_id = intval($patient_id);
            $lab_date = mysqli_real_escape_string($link, $lab_date);
            $lab_type = mysqli_real_escape_string($link, $lab_type);
            $lab_details = mysqli_real_escape_string($link, $lab_details);
            $blood_pressure = mysqli_real_escape_string($link, $blood_pressure);
            $pulse = intval($pulse);
            $temperature = floatval($temperature);
            $weight = floatval($weight);
            $height = floatval($height);
            $bmi = floatval($bmi);
            $glucose = floatval($glucose);
            $cholesterol = floatval($cholesterol);
            $note = mysqli_real_escape_string($link, $note);

            $sql = "UPDATE lab_results SET 
              patient_id = $patient_id, 
              lab_date = '$lab_date', 
              lab_type = '$lab_type', 
              lab_details = '$lab_details', 
              blood_pressure = '$blood_pressure', 
              pulse = $pulse, 
              temperature = $temperature, 
              weight = $weight, 
              height = $height, 
              bmi = $bmi, 
              glucose = $glucose, 
              cholesterol = $cholesterol, 
              note = '$note' 
              WHERE id = $lab_id";

            if (mysqli_query($link, $sql)) {
                # แก้ไขข้อมูลสำเร็จ
                echo "<script>" . "window.location.href='./lab_results.php?success=edit';" . "</script>";
                exit;
            } else {
                # เกิดข้อผิดพลาด
                echo "Error: " . mysqli_error($link);
                exit;
            }
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
                echo "<script>" . "window.location.href='./lab_results.php?success=delete';" . "</script>";
                exit;
            } else {
                # เกิดข้อผิดพลาด
                echo "<script>" . "window.location.href='./lab_results.php?error=delete';" . "</script>";
                exit;
            }

            mysqli_stmt_close($stmt);
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
                        <p class="text-gray-600">กำลังแสดงผลการตรวจวิเคราะห์ของ: <strong><?= htmlspecialchars($patient_filter_name) ?></strong> <a href="lab_results.php" class="text-primary-500 hover:underline">(แสดงทั้งหมด)</a></p>
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

                                $sql = "SELECT COUNT(*) as total, SUM(is_abnormal) as abnormal FROM lab_result_details WHERE lab_result_id = ?";
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    mysqli_stmt_bind_param($stmt, "i", $lab_id);

                                    if (mysqli_stmt_execute($stmt)) {
                                        $result = mysqli_stmt_get_result($stmt);

                                        if ($row = mysqli_fetch_assoc($result)) {
                                            $total_items = $row['total'];
                                            $abnormal_items = $row['abnormal'];
                                        }
                                    }

                                    mysqli_stmt_close($stmt);
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($lab['lab_date']))); ?></td>
                                    <td><?= htmlspecialchars($lab['patient_name']); ?></td>
                                    <td><?= htmlspecialchars($lab['hn']); ?></td>
                                    <td><?= htmlspecialchars($lab['lab_type']); ?></td>
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
                                    <td><?= htmlspecialchars($lab['weight'] ? $lab['weight'] . ' กก. / ' . $lab['height'] . ' ซม.' : '-'); ?></td>
                                    <td><?= htmlspecialchars($lab['blood_pressure'] ?: '-'); ?></td>
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
                                                data-patient-name="<?= htmlspecialchars($lab['patient_name']); ?>"
                                                data-hn="<?= htmlspecialchars($lab['hn']); ?>"
                                                data-lab-date="<?= htmlspecialchars($lab['lab_date']); ?>"
                                                data-lab-type="<?= htmlspecialchars($lab['lab_type']); ?>"
                                                data-lab-details="<?= htmlspecialchars($lab['lab_details']); ?>"
                                                data-blood-pressure="<?= htmlspecialchars($lab['blood_pressure']); ?>"
                                                data-pulse="<?= htmlspecialchars($lab['pulse']); ?>"
                                                data-temperature="<?= htmlspecialchars($lab['temperature']); ?>"
                                                data-weight="<?= htmlspecialchars($lab['weight']); ?>"
                                                data-height="<?= htmlspecialchars($lab['height']); ?>"
                                                data-bmi="<?= htmlspecialchars($lab['bmi']); ?>"
                                                data-glucose="<?= htmlspecialchars($lab['glucose']); ?>"
                                                data-cholesterol="<?= htmlspecialchars($lab['cholesterol']); ?>"
                                                data-note="<?= htmlspecialchars($lab['note']); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <!-- ปุ่มอื่นๆ เหมือนเดิม -->
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