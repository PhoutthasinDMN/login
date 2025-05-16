<!-- Modal: ดูผลการตรวจวิเคราะห์ -->
<div id="viewLabModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-xl font-display font-semibold text-gray-800">รายละเอียดผลการตรวจวิเคราะห์</h3>
      <button id="closeViewLabModal" class="text-gray-400 hover:text-gray-500">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2 border-b border-gray-200 pb-4 mb-4">
          <div class="flex justify-between items-center">
            <div>
              <h3 id="view_patient_name" class="text-xl font-semibold text-gray-800"></h3>
              <p id="view_hn" class="text-gray-500"></p>
            </div>
            <div class="text-right">
              <h4 id="view_lab_date" class="text-lg font-semibold text-primary-600"></h4>
              <p id="view_lab_type" class="text-gray-500"></p>
            </div>
          </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
          <h4 class="text-lg font-semibold text-gray-800 mb-3">สัญญาณชีพ</h4>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <p class="text-sm text-gray-500">ความดันโลหิต</p>
              <p id="view_blood_pressure" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">ชีพจร</p>
              <p id="view_pulse" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">อุณหภูมิร่างกาย</p>
              <p id="view_temperature" class="font-semibold"></p>
            </div>
          </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4 bg-green-50">
          <h4 class="text-lg font-semibold text-gray-800 mb-3">ดัชนีมวลกาย</h4>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <p class="text-sm text-gray-500">น้ำหนัก</p>
              <p id="view_weight" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">ส่วนสูง</p>
              <p id="view_height" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">BMI</p>
              <p id="view_bmi" class="font-semibold"></p>
            </div>
          </div>
        </div>
        
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="border border-gray-200 rounded-lg p-4 bg-yellow-50">
            <h4 class="text-lg font-semibold text-gray-800 mb-3">ผลการตรวจเลือด</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-sm text-gray-500">น้ำตาลในเลือด</p>
                <p id="view_glucose" class="font-semibold"></p>
              </div>
              <div>
                <p class="text-sm text-gray-500">คอเลสเตอรอล</p>
                <p id="view_cholesterol" class="font-semibold"></p>
              </div>
            </div>
          </div>
          
          <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-3">รายละเอียดการตรวจ</h4>
            <p id="view_lab_details" class="text-gray-600"></p>
          </div>
        </div>
        
        <div class="md:col-span-2 border border-gray-200 rounded-lg p-4">
          <h4 class="text-lg font-semibold text-gray-800 mb-2">บันทึกเพิ่มเติม</h4>
          <p id="view_note" class="text-gray-600"></p>
        </div>
      </div>
    </div>
    
    <div class="p-6 border-t border-gray-200 flex justify-end">
      <button id="printLabResultBtn" class="px-4 py-2 mr-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
        <i class="fas fa-print mr-1"></i> พิมพ์
      </button>
      <button type="button" id="closeViewLabBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
        ปิด
      </button>
    </div>
  </div>
</div>