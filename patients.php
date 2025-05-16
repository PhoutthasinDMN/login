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
    }
    
    mysqli_stmt_close($stmt);
}

# ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    # เพิ่มผู้ป่วยใหม่
    if ($_POST["action"] == "add") {
        $patient_name = trim($_POST["patient_name"]);
        $hn = trim($_POST["hn"]);
        $age = trim($_POST["age"]);
        $gender = trim($_POST["gender"]);
        $address = trim($_POST["address"]);
        $phone = trim($_POST["phone"]);
        $diagnosis = trim($_POST["diagnosis"]);

        # ตรวจสอบว่าไม่มีช่องว่างเปล่า
        if (!empty($patient_name) && !empty($hn) && !empty($age)) {
            # ใช้ Prepared Statement เพื่อความปลอดภัย
            $sql = "INSERT INTO patients (hn, patient_name, age, gender, address, phone, diagnosis) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssissss", $hn, $patient_name, $age, $gender, $address, $phone, $diagnosis);

                if (mysqli_stmt_execute($stmt)) {
                    # เพิ่มข้อมูลสำเร็จ
                    redirect("./patients.php?success=add");
                } else {
                    # เกิดข้อผิดพลาด
                    redirect("./patients.php?error=add");
                }

                mysqli_stmt_close($stmt);
            }
        }
    }

    # แก้ไขข้อมูลผู้ป่วย
    elseif ($_POST["action"] == "edit") {
        $patient_id = $_POST["patient_id"];
        $patient_name = trim($_POST["patient_name"]);
        $hn = trim($_POST["hn"]);
        $age = trim($_POST["age"]);
        $gender = trim($_POST["gender"]);
        $address = trim($_POST["address"]);
        $phone = trim($_POST["phone"]);
        $diagnosis = trim($_POST["diagnosis"]);

        # ตรวจสอบว่าไม่มีช่องว่างเปล่า
        if (!empty($patient_name) && !empty($hn) && !empty($age)) {
            # ใช้ Prepared Statement เพื่อความปลอดภัย
            $sql = "UPDATE patients SET 
                    hn = ?, 
                    patient_name = ?, 
                    age = ?, 
                    gender = ?, 
                    address = ?, 
                    phone = ?, 
                    diagnosis = ? 
                    WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssissssi", $hn, $patient_name, $age, $gender, $address, $phone, $diagnosis, $patient_id);

                if (mysqli_stmt_execute($stmt)) {
                    # แก้ไขข้อมูลสำเร็จ
                    redirect("./patients.php?success=edit");
                } else {
                    # เกิดข้อผิดพลาด
                    redirect("./patients.php?error=edit");
                }

                mysqli_stmt_close($stmt);
            }
        }
    }

    # ลบข้อมูลผู้ป่วย
    elseif ($_POST["action"] == "delete") {
        $patient_id = $_POST["patient_id"];

        # เตรียม SQL สำหรับลบข้อมูล
        $sql = "DELETE FROM patients WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $patient_id);

            if (mysqli_stmt_execute($stmt)) {
                # ลบข้อมูลสำเร็จ
                redirect("./patients.php?success=delete");
            } else {
                # เกิดข้อผิดพลาด
                redirect("./patients.php?error=delete");
            }

            mysqli_stmt_close($stmt);
        }
    }
}

# ดึงข้อมูลผู้ป่วยทั้งหมด
$patients = [];
$sql = "SELECT * FROM patients ORDER BY id DESC";
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
                    <h1 class="text-3xl font-display font-semibold text-gray-800">ข้อมูลผู้ป่วย</h1>
                    <p class="text-gray-600">จัดการข้อมูลผู้ป่วยในระบบ</p>
                </div>
                <button id="addPatientBtn" class="bg-primary-500 hover:bg-primary-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i> เพิ่มผู้ป่วยใหม่
                </button>
            </div>

            <!-- Patient Table Card -->
            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="p-6">
                    <table id="patientsTable" class="display w-full">
                        <thead>
                            <tr>
                                <th>HN</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อายุ</th>
                                <th>เพศ</th>
                                <th>โทรศัพท์</th>
                                <th>การวินิจฉัย</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $patient) : ?>
                                <tr>
                                    <td><?= escape_html($patient['hn']); ?></td>
                                    <td><?= escape_html($patient['patient_name']); ?></td>
                                    <td><?= escape_html($patient['age']); ?></td>
                                    <td><?= escape_html($patient['gender']); ?></td>
                                    <td><?= escape_html($patient['phone']); ?></td>
                                    <td><?= escape_html($patient['diagnosis']); ?></td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <button
                                                class="view-patient text-blue-500 hover:text-blue-700"
                                                data-id="<?= $patient['id']; ?>"
                                                data-hn="<?= escape_html($patient['hn']); ?>"
                                                data-name="<?= escape_html($patient['patient_name']); ?>"
                                                data-age="<?= escape_html($patient['age']); ?>"
                                                data-gender="<?= escape_html($patient['gender']); ?>"
                                                data-address="<?= escape_html($patient['address']); ?>"
                                                data-phone="<?= escape_html($patient['phone']); ?>"
                                                data-diagnosis="<?= escape_html($patient['diagnosis']); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="edit-patient text-yellow-500 hover:text-yellow-700"
                                                data-id="<?= $patient['id']; ?>"
                                                data-hn="<?= escape_html($patient['hn']); ?>"
                                                data-name="<?= escape_html($patient['patient_name']); ?>"
                                                data-age="<?= escape_html($patient['age']); ?>"
                                                data-gender="<?= escape_html($patient['gender']); ?>"
                                                data-address="<?= escape_html($patient['address']); ?>"
                                                data-phone="<?= escape_html($patient['phone']); ?>"
                                                data-diagnosis="<?= escape_html($patient['diagnosis']); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="delete-patient text-red-500 hover:text-red-700"
                                                data-id="<?= $patient['id']; ?>"
                                                data-name="<?= escape_html($patient['patient_name']); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <a
                                                href="lab_results.php?patient_id=<?= $patient['id']; ?>"
                                                class="text-green-500 hover:text-green-700"
                                                title="ดูผลการตรวจวิเคราะห์">
                                                <i class="fas fa-flask"></i>
                                            </a>
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
    <?php include 'includes/modals/patient_add_modal.php'; ?>
    <?php include 'includes/modals/patient_edit_modal.php'; ?>
    <?php include 'includes/modals/patient_view_modal.php'; ?>

    <!-- Hidden Form for Delete -->
    <form id="deletePatientForm" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="hidden">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" id="delete_patient_id" name="patient_id">
    </form>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts/patient_scripts.php'; ?>
</body>

</html>
<?php
// ปิดการเชื่อมต่อกับฐานข้อมูล
mysqli_close($link);
?>