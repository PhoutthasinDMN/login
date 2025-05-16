<!-- Sidebar -->
<aside class="w-64 h-screen bg-white shadow-md fixed">
    <div class="p-4 bg-primary-500">
        <h2 class="text-white text-2xl font-display font-semibold">Sneat Admin</h2>
    </div>

    <div class="p-4">
        <div class="flex items-center pb-4 border-b border-gray-200">
            <img src="./img/blank-avatar.jpg" class="w-10 h-10 rounded-full" alt="User avatar">
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($username); ?></p>
                <p class="text-xs text-gray-500">User</p>
            </div>
        </div>
    </div>

    <?php
    // ดึงชื่อไฟล์ปัจจุบัน
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>

    <nav class="mt-4">
        <ul>
            <li class="px-4 py-2 <?= ($current_page == 'dashboard.php') ? 'bg-primary-50 text-primary-500 border-l-4 border-primary-500' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors' ?>">
                <a href="./dashboard.php" class="flex items-center">
                    <i class="fa fa-tachometer-alt w-6"></i>
                    <span>หน้าหลัก</span>
                </a>
            </li>
            <li class="px-4 py-2 <?= ($current_page == 'patients.php') ? 'bg-primary-50 text-primary-500 border-l-4 border-primary-500' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors' ?>">
                <a href="./patients.php" class="flex items-center">
                    <i class="fa fa-hospital-user w-6"></i>
                    <span>ข้อมูลผู้ป่วย</span>
                </a>
            </li>
            <li class="px-4 py-2 <?= ($current_page == 'lab_results.php') ? 'bg-primary-50 text-primary-500 border-l-4 border-primary-500' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors' ?>">
                <a href="./lab_results.php" class="flex items-center">
                    <i class="fa fa-flask w-6"></i>
                    <span>ผลตรวจวิเคราะห์</span>
                </a>
            </li>
            <li class="px-4 py-2 <?= ($current_page == 'lab_new.php') ? 'bg-primary-50 text-primary-500 border-l-4 border-primary-500' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors' ?>">
                <a href="./lab_new.php" class="flex items-center">
                    <i class="fa fa-plus-circle w-6"></i>
                    <span>เพิ่มผลตรวจใหม่</span>
                </a>
            </li>
            <li class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors">
                <a href="#" class="flex items-center">
                    <i class="fa fa-user-md w-6"></i>
                    <span>ข้อมูลแพทย์</span>
                </a>
            </li>
            <li class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors">
                <a href="#" class="flex items-center">
                    <i class="fa fa-calendar-check w-6"></i>
                    <span>นัดหมาย</span>
                </a>
            </li>
            <li class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors">
                <a href="#" class="flex items-center">
                    <i class="fa fa-chart-line w-6"></i>
                    <span>รายงาน</span>
                </a>
            </li>
            <li class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors">
                <a href="#" class="flex items-center">
                    <i class="fa fa-cog w-6"></i>
                    <span>ตั้งค่า</span>
                </a>
            </li>
            <li class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-500 transition-colors">
                <a href="./logout.php" class="flex items-center">
                    <i class="fa fa-sign-out-alt w-6"></i>
                    <span>ออกจากระบบ</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>