<!-- Modal: ดูข้อมูลผู้ป่วย -->
<div id="viewPatientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-xl font-display font-semibold text-gray-800">ข้อมูลผู้ป่วย</h3>
      <button id="closeViewModal" class="text-gray-400 hover:text-gray-500">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="p-6">
      <div class="text-center mb-6">
        <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center text-primary-500 text-4xl mx-auto">
          <i class="fas fa-user-circle"></i>
        </div>
        <h4 id="view_patient_name" class="mt-4 text-xl font-semibold text-gray-800"></h4>
        <p id="view_hn" class="text-gray-500"></p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h5 class="text-sm font-medium text-gray-500">อายุ</h5>
          <p id="view_age" class="font-semibold"></p>
        </div>
        <div>
          <h5 class="text-sm font-medium text-gray-500">เพศ</h5>
          <p id="view_gender" class="font-semibold"></p>
        </div>
        <div class="md:col-span-2">
          <h5 class="text-sm font-medium text-gray-500">ที่อยู่</h5>
          <p id="view_address" class="font-semibold"></p>
        </div>
        <div>
          <h5 class="text-sm font-medium text-gray-500">เบอร์โทรศัพท์</h5>
          <p id="view_phone" class="font-semibold"></p>
        </div>
        <div>
          <h5 class="text-sm font-medium text-gray-500">การวินิจฉัย</h5>
          <p id="view_diagnosis" class="font-semibold"></p>
        </div>
      </div>
      
      <div class="mt-6">
        <a id="view_lab_link" href="#" class="block w-full bg-green-50 hover:bg-green-100 rounded-lg p-4 text-center transition-colors">
          <i class="fas fa-flask text-green-500 mr-2"></i>
          <span>ดูผลการตรวจวิเคราะห์ของผู้ป่วยรายนี้</span>
        </a>
      </div>
    </div>
    <div class="p-6 border-t border-gray-200 flex justify-end">
      <button type="button" id="closeViewBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
        ปิด
      </button>
    </div>
  </div>
</div>