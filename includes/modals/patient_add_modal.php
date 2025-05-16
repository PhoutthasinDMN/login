<!-- Modal: เพิ่มผู้ป่วยใหม่ -->
<div id="addPatientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
    <div class="p-6 border-b border-gray-200">
      <div class="flex justify-between items-center">
        <h3 class="text-xl font-display font-semibold text-gray-800">เพิ่มข้อมูลผู้ป่วยใหม่</h3>
        <button id="closeAddModal" class="text-gray-400 hover:text-gray-500">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <form id="addPatientForm" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <input type="hidden" name="action" value="add">
      <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
          <input type="text" id="patient_name" name="patient_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div>
          <label for="hn" class="block text-sm font-medium text-gray-700 mb-1">HN <span class="text-red-500">*</span></label>
          <input type="text" id="hn" name="hn" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="age" class="block text-sm font-medium text-gray-700 mb-1">อายุ <span class="text-red-500">*</span></label>
            <input type="number" id="age" name="age" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
          </div>
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">เพศ</label>
            <select id="gender" name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
              <option value="ชาย">ชาย</option>
              <option value="หญิง">หญิง</option>
              <option value="อื่นๆ">อื่นๆ</option>
            </select>
          </div>
        </div>
        <div class="md:col-span-2">
          <label for="address" class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่</label>
          <textarea id="address" name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
        </div>
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
          <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div>
          <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">การวินิจฉัย</label>
          <input type="text" id="diagnosis" name="diagnosis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
      </div>
      <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
        <button type="button" id="cancelAddBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
          ยกเลิก
        </button>
        <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
          บันทึกข้อมูล
        </button>
      </div>
    </form>
  </div>
</div>