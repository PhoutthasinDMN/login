<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#labResultsTable').DataTable({
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
            order: [
                [0, 'desc']
            ], // เรียงตามวันที่ล่าสุด
            responsive: true
        });

        // Check URL parameters for notifications
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('success') === 'add') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'บันทึกผลการตรวจวิเคราะห์เรียบร้อยแล้ว',
                confirmButtonColor: '#0075e1'
            });
        } else if (urlParams.get('success') === 'edit') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'แก้ไขผลการตรวจวิเคราะห์เรียบร้อยแล้ว',
                confirmButtonColor: '#0075e1'
            });
        } else if (urlParams.get('success') === 'delete') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'ลบผลการตรวจวิเคราะห์เรียบร้อยแล้ว',
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

        // Add Lab Result Modal
        $('#addLabResultBtn').click(function() {
            $('#addLabModal').removeClass('hidden');
        });

        $('#closeAddLabModal, #cancelAddLabBtn').click(function() {
            $('#addLabModal').addClass('hidden');
        });

        // View Lab Result
        $('.view-lab').click(function() {
            const id = $(this).data('id');
            const patientName = $(this).data('patient-name');
            const hn = $(this).data('hn');
            const labDate = new Date($(this).data('lab-date')).toLocaleDateString('th-TH', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const labType = $(this).data('lab-type');
            const labDetails = $(this).data('lab-details');
            const bloodPressure = $(this).data('blood-pressure') || '-';
            const pulse = $(this).data('pulse') ? $(this).data('pulse') + ' ครั้ง/นาที' : '-';
            const temperature = $(this).data('temperature') ? $(this).data('temperature') + ' °C' : '-';
            const weight = $(this).data('weight') ? $(this).data('weight') + ' กก.' : '-';
            const height = $(this).data('height') ? $(this).data('height') + ' ซม.' : '-';
            const bmi = $(this).data('bmi') || '-';
            const glucose = $(this).data('glucose') ? $(this).data('glucose') + ' มก./ดล.' : '-';
            const cholesterol = $(this).data('cholesterol') ? $(this).data('cholesterol') + ' มก./ดล.' : '-';
            const note = $(this).data('note') || 'ไม่มีบันทึกเพิ่มเติม';

            // Populate modal with data
            $('#view_patient_name').text(patientName);
            $('#view_hn').text('HN: ' + hn);
            $('#view_lab_date').text(labDate);
            $('#view_lab_type').text(labType);
            $('#view_lab_details').text(labDetails || 'ไม่มีรายละเอียดเพิ่มเติม');
            $('#view_blood_pressure').text(bloodPressure);
            $('#view_pulse').text(pulse);
            $('#view_temperature').text(temperature);
            $('#view_weight').text(weight);
            $('#view_height').text(height);
            $('#view_bmi').text(bmi);
            $('#view_glucose').text(glucose);
            $('#view_cholesterol').text(cholesterol);
            $('#view_note').text(note);

            // Show modal
            $('#viewLabModal').removeClass('hidden');
        });

        $('#closeViewLabModal, #closeViewLabBtn').click(function() {
            $('#viewLabModal').addClass('hidden');
        });

        // Edit Lab Result
        $('.edit-lab').click(function() {
            const id = $(this).data('id');
            const patientId = $(this).data('patient-id');
            const labDate = $(this).data('lab-date');
            const labType = $(this).data('lab-type');
            const labDetails = $(this).data('lab-details');
            const bloodPressure = $(this).data('blood-pressure');
            const pulse = $(this).data('pulse');
            const temperature = $(this).data('temperature');
            const weight = $(this).data('weight');
            const height = $(this).data('height');
            const glucose = $(this).data('glucose');
            const cholesterol = $(this).data('cholesterol');
            const note = $(this).data('note');

            // Populate form with data
            $('#edit_lab_id').val(id);
            $('#edit_patient_id').val(patientId);
            $('#edit_lab_date').val(labDate);
            $('#edit_lab_type').val(labType);
            $('#edit_lab_details').val(labDetails);
            $('#edit_blood_pressure').val(bloodPressure);
            $('#edit_pulse').val(pulse);
            $('#edit_temperature').val(temperature);
            $('#edit_weight').val(weight);
            $('#edit_height').val(height);
            $('#edit_glucose').val(glucose);
            $('#edit_cholesterol').val(cholesterol);
            $('#edit_note').val(note);

            // Show modal
            $('#editLabModal').removeClass('hidden');
        });

        $('#closeEditLabModal, #cancelEditLabBtn').click(function() {
            $('#editLabModal').addClass('hidden');
        });

        // Delete Lab Result
        $('.delete-lab').click(function() {
            const id = $(this).data('id');
            const patientName = $(this).data('patient-name');
            const labDate = $(this).data('lab-date');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                html: `คุณต้องการลบผลการตรวจวิเคราะห์ของ <b>${patientName}</b> วันที่ <b>${labDate}</b> ใช่หรือไม่?<br>การกระทำนี้ไม่สามารถยกเลิกได้`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบข้อมูล',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete_lab_id').val(id);
                    $('#deleteLabForm').submit();
                }
            });
        });

        // Print Lab Result
        $('#printLabResultBtn').click(function() {
            const patientName = $('#view_patient_name').text();
            const hn = $('#view_hn').text();
            const labDate = $('#view_lab_date').text();
            const labType = $('#view_lab_type').text();

            // Create print window content
            const printContent = `
        <html>
        <head>
          <title>ผลการตรวจวิเคราะห์ - ${patientName}</title>
          <style>
            body { font-family: 'Prompt', sans-serif; padding: 20px; }
            h1 { text-align: center; font-size: 22px; margin-bottom: 10px; }
            .header { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
            .patient-info { margin-bottom: 20px; }
            .section { margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
            .section h3 { margin-top: 0; margin-bottom: 10px; font-size: 16px; }
            .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
            .item { margin-bottom: 8px; }
            .label { font-size: 12px; color: #666; margin-bottom: 2px; }
            .value { font-weight: bold; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            @media print {
              body { margin: 0; padding: 15px; }
              button { display: none; }
            }
          </style>
        </head>
        <body>
          <div class="header">
            <div>
              <h1>ผลการตรวจวิเคราะห์</h1>
              <p>${labType}</p>
            </div>
            <div style="text-align: right;">
              <p style="font-weight: bold;">${labDate}</p>
              <p>โรงพยาบาลตัวอย่าง</p>
            </div>
          </div>
          
          <div class="patient-info">
            <h2>${patientName}</h2>
            <p>${hn}</p>
          </div>
          
          <div class="grid">
            <div class="section">
              <h3>สัญญาณชีพ</h3>
              <div class="grid">
                <div class="item">
                  <div class="label">ความดันโลหิต</div>
                  <div class="value">${$('#view_blood_pressure').text()}</div>
                </div>
                <div class="item">
                  <div class="label">ชีพจร</div>
                  <div class="value">${$('#view_pulse').text()}</div>
                </div>
                <div class="item">
                  <div class="label">อุณหภูมิร่างกาย</div>
                 <div class="value">${$('#view_temperature').text()}</div>
               </div>
             </div>
           </div>
           
           <div class="section">
             <h3>ดัชนีมวลกาย</h3>
             <div class="grid">
               <div class="item">
                 <div class="label">น้ำหนัก</div>
                 <div class="value">${$('#view_weight').text()}</div>
               </div>
               <div class="item">
                 <div class="label">ส่วนสูง</div>
                 <div class="value">${$('#view_height').text()}</div>
               </div>
               <div class="item">
                 <div class="label">BMI</div>
                 <div class="value">${$('#view_bmi').text()}</div>
               </div>
             </div>
           </div>
         </div>
         
         <div class="grid">
           <div class="section">
             <h3>ผลการตรวจเลือด</h3>
             <div class="grid">
               <div class="item">
                 <div class="label">น้ำตาลในเลือด</div>
                 <div class="value">${$('#view_glucose').text()}</div>
               </div>
               <div class="item">
                 <div class="label">คอเลสเตอรอล</div>
                 <div class="value">${$('#view_cholesterol').text()}</div>
               </div>
             </div>
           </div>
           
           <div class="section">
             <h3>รายละเอียดการตรวจ</h3>
             <p>${$('#view_lab_details').text()}</p>
           </div>
         </div>
         
         <div class="section">
           <h3>บันทึกเพิ่มเติม</h3>
           <p>${$('#view_note').text()}</p>
         </div>
         
         <div class="footer">
           <p>เอกสารนี้ออกให้ ณ วันที่ ${new Date().toLocaleDateString('th-TH', {day: 'numeric', month: 'long', year: 'numeric'})}</p>
         </div>
         
         <div style="text-align: center; margin-top: 20px;">
           <button onclick="window.print();" style="padding: 8px 16px; background-color: #0075e1; color: white; border: none; border-radius: 4px; cursor: pointer;">พิมพ์</button>
         </div>
       </body>
       </html>
     `;

            // Open new window and print
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            // Auto print when ready
            printWindow.onload = function() {
                printWindow.focus();
                // Uncomment this line to immediately print
                // printWindow.print();
            };
        });

        // คำนวณ BMI อัตโนมัติเมื่อกรอกน้ำหนักและส่วนสูง
        function calculateBMI(weight, height) {
            if (weight > 0 && height > 0) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);
                return bmi.toFixed(2);
            }
            return '';
        }

        // คำนวณ BMI ในฟอร์มเพิ่ม
        $('#weight, #height').on('input', function() {
            const weight = parseFloat($('#weight').val()) || 0;
            const height = parseFloat($('#height').val()) || 0;
            const bmi = calculateBMI(weight, height);

            // แสดง BMI ใน tooltip หรือ notification
            if (bmi) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'info',
                    title: 'BMI = ' + bmi,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    timerProgressBar: true,
                    width: 200
                });
            }
        });

        // คำนวณ BMI ในฟอร์มแก้ไข
        $('#edit_weight, #edit_height').on('input', function() {
            const weight = parseFloat($('#edit_weight').val()) || 0;
            const height = parseFloat($('#edit_height').val()) || 0;
            const bmi = calculateBMI(weight, height);

            // แสดง BMI ใน tooltip หรือ notification
            if (bmi) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'info',
                    title: 'BMI = ' + bmi,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    timerProgressBar: true,
                    width: 200
                });
            }
        });
    });
</script>