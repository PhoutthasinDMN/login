<!-- Modal: แก้ไขผลการตรวจวิเคราะห์ -->
<div id="editLabModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl h-5/6 flex flex-col">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-xl font-display font-semibold text-gray-800">แก้ไขผลการตรวจวิเคราะห์</h3>
      <button id="closeEditLabModal" class="text-gray-400 hover:text-gray-500">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="p-6 overflow-y-auto flex-grow">
      <form id="editLabForm" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" id="edit_lab_id" name="lab_id">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="edit_patient_id" class="block text-sm font-medium text-gray-700 mb-1">ผู้ป่วย <span class="text-red-500">*</span></label>
            <select id="edit_patient_id" name="patient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
              <option value="">เลือกผู้ป่วย</option>
              <?php foreach ($patients as $patient) : ?>
              <option value="<?= $patient['id']; ?>"><?= htmlspecialchars($patient['patient_name'] . ' (HN: ' . $patient['hn'] . ')'); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div>
            <label for="edit_lab_date" class="block text-sm font-medium text-gray-700 mb-1">วันที่ตรวจ <span class="text-red-500">*</span></label>
            <input type="date" id="edit_lab_date" name="lab_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
          </div>
          
          <div>
            <label for="edit_lab_type" class="block text-sm font-medium text-gray-700 mb-1">ประเภทการตรวจ <span class="text-red-500">*</span></label>
            <select id="edit_lab_type" name="lab_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
              <option value="">เลือกประเภทการตรวจ</option>
              <option value="ตรวจสุขภาพประจำปี">ตรวจสุขภาพประจำปี</option>
              <option value="ตรวจติดตามโรคประจำตัว">ตรวจติดตามโรคประจำตัว</option>
              <option value="ตรวจรักษาทั่วไป">ตรวจรักษาทั่วไป</option>
              <option value="ตรวจเฉพาะทาง">ตรวจเฉพาะทาง</option>
              <option value="ตรวจก่อนการผ่าตัด">ตรวจก่อนการผ่าตัด</option>
              <option value="ตรวจหลังการผ่าตัด">ตรวจหลังการผ่าตัด</option>
              <option value="ตรวจร่างกายก่อนเข้าทำงาน">ตรวจร่างกายก่อนเข้าทำงาน</option>
            </select>
          </div>
          
          <div class="md:col-span-2">
            <label for="edit_lab_details" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดการตรวจ</label>
            <textarea id="edit_lab_details" name="lab_details" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="edit_weight" class="block text-sm font-medium text-gray-700 mb-1">น้ำหนัก (กก.)</label>
              <input type="number" id="edit_weight" name="weight" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
              <label for="edit_height" class="block text-sm font-medium text-gray-700 mb-1">ส่วนสูง (ซม.)</label>
              <input type="number" id="edit_height" name="height" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="edit_blood_pressure" class="block text-sm font-medium text-gray-700 mb-1">ความดันโลหิต (มม.ปรอท)</label>
              <input type="text" id="edit_blood_pressure" name="blood_pressure" placeholder="เช่น 120/80" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
              <label for="edit_pulse" class="block text-sm font-medium text-gray-700 mb-1">ชีพจร (ครั้ง/นาที)</label>
              <input type="number" id="edit_pulse" name="pulse" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="edit_temperature" class="block text-sm font-medium text-gray-700 mb-1">อุณหภูมิร่างกาย (°C)</label>
              <input type="number" id="edit_temperature" name="temperature" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
              <label for="edit_glucose" class="block text-sm font-medium text-gray-700 mb-1">น้ำตาลในเลือด (มก./ดล.)</label>
              <input type="number" id="edit_glucose" name="glucose" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
          </div>
          
          <div>
            <label for="edit_cholesterol" class="block text-sm font-medium text-gray-700 mb-1">คอเลสเตอรอล (มก./ดล.)</label>
            <input type="number" id="edit_cholesterol" name="cholesterol" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
          </div>
          
          <div class="md:col-span-2">
            <label for="edit_note" class="block text-sm font-medium text-gray-700 mb-1">บันทึกเพิ่มเติม</label>
            <textarea id="edit_note" name="note" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button type="button" id="cancelEditLabBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
            ยกเลิก
          </button>
          <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
            บันทึกการแก้ไข
          </button>
        </div>
      </form>
    </div>
  </div>
</div>