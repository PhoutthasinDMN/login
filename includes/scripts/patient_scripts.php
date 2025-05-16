<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#patientsTable').DataTable({
      language: {
        search: "ค้นหา:",
        lengthMenu: "แสดง _MENU_ รายการ",
        info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
        infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
        infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
        paginate: {
          first: "หน้าแรก",
          last: "หน้าสุดท้าย",
          next: "ถัดไป",
          previous: "ก่อนหน้า"
        },
        emptyTable: "ไม่มีข้อมูลในตาราง"
      },
      responsive: true
    });
    
    // Check URL parameters for notifications
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('success') === 'add') {
      Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: 'เพิ่มข้อมูลผู้ป่วยเรียบร้อยแล้ว',
        confirmButtonColor: '#0075e1'
      });
    } else if (urlParams.get('success') === 'edit') {
      Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: 'แก้ไขข้อมูลผู้ป่วยเรียบร้อยแล้ว',
        confirmButtonColor: '#0075e1'
      });
    } else if (urlParams.get('success') === 'delete') {
      Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: 'ลบข้อมูลผู้ป่วยเรียบร้อยแล้ว',
        confirmButtonColor: '#0075e1'
      });
    } else if (urlParams.get('error')) {
      Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด!',
        text: 'เกิดข้อผิดพลาดในการทำงาน กรุณาลองใหม่อีกครั้ง',
        confirmButtonColor: '#0075e1'
      });
    }
    
    // Add Patient Modal
    $('#addPatientBtn').click(function() {
      $('#addPatientModal').removeClass('hidden');
    });
    
    $('#closeAddModal, #cancelAddBtn').click(function() {
      $('#addPatientModal').addClass('hidden');
    });
    
    // View Patient
    $('.view-patient').click(function() {
      const id = $(this).data('id');
      const hn = $(this).data('hn');
      const name = $(this).data('name');
      const age = $(this).data('age');
      const gender = $(this).data('gender');
      const address = $(this).data('address');
      const phone = $(this).data('phone');
      const diagnosis = $(this).data('diagnosis');
      
      // Populate modal with data
      $('#view_patient_name').text(name);
      $('#view_hn').text('HN: ' + hn);
      $('#view_age').text(age + ' ปี');
      $('#view_gender').text(gender || '-');
      $('#view_address').text(address || '-');
      $('#view_phone').text(phone || '-');
      $('#view_diagnosis').text(diagnosis || '-');
      
      // Update lab results link
      $('#view_lab_link').attr('href', 'lab_results.php?patient_id=' + id);
      
      // Show modal
      $('#viewPatientModal').removeClass('hidden');
    });
    
    $('#closeViewModal, #closeViewBtn').click(function() {
      $('#viewPatientModal').addClass('hidden');
    });
    
    // Edit Patient
    $('.edit-patient').click(function() {
      const id = $(this).data('id');
      const hn = $(this).data('hn');
      const name = $(this).data('name');
      const age = $(this).data('age');
      const gender = $(this).data('gender');
      const address = $(this).data('address');
      const phone = $(this).data('phone');
      const diagnosis = $(this).data('diagnosis');
      
      // Populate form with data
      $('#edit_patient_id').val(id);
      $('#edit_patient_name').val(name);
      $('#edit_hn').val(hn);
      $('#edit_age').val(age);
      $('#edit_gender').val(gender);
      $('#edit_address').val(address);
      $('#edit_phone').val(phone);
      $('#edit_diagnosis').val(diagnosis);
      
      // Show modal
      $('#editPatientModal').removeClass('hidden');
    });
    
    $('#closeEditModal, #cancelEditBtn').click(function() {
      $('#editPatientModal').addClass('hidden');
    });
    
    // Delete Patient
    $('.delete-patient').click(function() {
      const id = $(this).data('id');
      const name = $(this).data('name');
      
      Swal.fire({
        title: 'ยืนยันการลบ?',
        html: `คุณต้องการลบข้อมูลผู้ป่วย <b>${name}</b> ใช่หรือไม่?<br>การกระทำนี้ไม่สามารถยกเลิกได้`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบข้อมูล',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#delete_patient_id').val(id);
          $('#deletePatientForm').submit();
        }
      });
    });
  });
</script>