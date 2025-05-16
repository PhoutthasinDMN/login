
<script>
        $(document).ready(function() {
            // คำนวณ BMI อัตโนมัติ
            function calculateBMI() {
                const weight = parseFloat($('#weight').val()) || 0;
                const height = parseFloat($('#height').val()) || 0;
                
                if (weight > 0 && height > 0) {
                    const heightInMeters = height / 100;
                    const bmi = weight / (heightInMeters * heightInMeters);
                    $('#bmi_display').val(bmi.toFixed(2));
                } else {
                    $('#bmi_display').val('');
                }
            }
            
            $('#weight, #height').on('input', calculateBMI);
            
            // สลับแท็บหมวดหมู่
            $('.category-tab').click(function() {
                const tabId = $(this).data('tab');
                
                // เปลี่ยนสีแท็บที่เลือก
                $('.category-tab').removeClass('text-primary-600 border-b-2 border-primary-500').addClass('text-gray-500 hover:text-primary-600 hover:border-primary-300');
                $(this).addClass('text-primary-600 border-b-2 border-primary-500').removeClass('text-gray-500 hover:text-primary-600 hover:border-primary-300');
                
                // แสดงเนื้อหาแท็บที่เลือก
                $('.tab-content').addClass('hidden');
                $('#' + tabId).removeClass('hidden');
            });
            
            // แสดง/ซ่อนฟิลด์ตามการเลือกรายการตรวจ
            $('.lab-item-checkbox').change(function() {
                const isChecked = $(this).is(':checked');
                const fieldsContainer = $(this).closest('.lab-item').find('.item-fields');
                
                if (isChecked) {
                    fieldsContainer.removeClass('hidden');
                    
                    // เพิ่มความรู้สึกว่าจำเป็นต้องกรอกข้อมูล
                    const requiredFields = fieldsContainer.find('[required]');
                    if (requiredFields.length > 0) {
                        requiredFields.addClass('border-red-300');
                        
                        // เพิ่มข้อความแนะนำ
                        if (!fieldsContainer.find('.required-message').length) {
                            fieldsContainer.prepend('<p class="required-message text-red-500 text-sm mb-2">โปรดกรอกข้อมูลในช่องที่มีเครื่องหมาย * ให้ครบถ้วน</p>');
                        }
                    }
                } else {
                    fieldsContainer.addClass('hidden');
                    
                    // ล้างค่าในฟิลด์เมื่อยกเลิกการเลือก
                    fieldsContainer.find('input, select').val('');
                    fieldsContainer.find('.required-message').remove();
                    fieldsContainer.find('.border-red-300').removeClass('border-red-300');
                }
                
                // อัพเดทสรุปรายการที่เลือก
                updateSelectedItemsSummary();
            });
            
            // สรุปรายการที่เลือก
            function updateSelectedItemsSummary() {
                const selectedItems = $('.lab-item-checkbox:checked');
                
                // นับจำนวนรายการที่เลือก
                if (selectedItems.length > 0) {
                    // แสดงจำนวนรายการในปุ่มบันทึก
                    $('button[type="submit"]').text(`บันทึกผลการตรวจ (${selectedItems.length} รายการ)`);
                } else {
                    $('button[type="submit"]').text('บันทึกผลการตรวจ');
                }
            }
            
            // ตรวจสอบค่าที่กรอกว่าอยู่ในช่วงปกติหรือไม่
            $(document).on('change', 'input[type="number"]', function() {
                const $this = $(this);
                const value = parseFloat($this.val());
                const normalRange = $this.closest('.form-group').find('label span').text();
                
                // ถ้ามีการระบุช่วงค่าปกติและมีค่าที่กรอก
                if (normalRange && value) {
                    // ลบวงเล็บและดึงเฉพาะช่วงค่า
                    const rangeMatch = normalRange.match(/\(([^)]+)\)/);
                    if (rangeMatch && rangeMatch[1]) {
                        const range = rangeMatch[1];
                        
                        // ตรวจสอบว่ามีเครื่องหมาย "-" หรือไม่ (เช่น 4.0-10.0)
                        if (range.includes('-')) {
                            const [min, max] = range.split('-').map(num => parseFloat(num.trim()));
                            
                            // ตรวจสอบว่าค่าอยู่ในช่วงหรือไม่
                            if (value < min || value > max) {
                                // แสดงการเตือนว่าค่าไม่อยู่ในช่วงปกติ
                                $this.addClass('border-red-500');
                                
                                // เพิ่มข้อความแสดงการเตือน
                                const alertMessage = $this.next('.abnormal-alert');
                                if (alertMessage.length === 0) {
                                    $this.after(`<div class="abnormal-alert text-xs text-red-500 mt-1">ค่าไม่อยู่ในช่วงปกติ (${range})</div>`);
                                }
                            } else {
                                // ถ้าค่าอยู่ในช่วงปกติ ลบการเตือน
                                $this.removeClass('border-red-500');
                                $this.next('.abnormal-alert').remove();
                            }
                        }
                    }
                }
            });
            
            // ตรวจสอบการกรอกฟอร์มก่อนส่ง
            $('form').submit(function(e) {
                const selectedItems = $('.lab-item-checkbox:checked').length;
                
                if (selectedItems === 0) {
                    e.preventDefault();
                    
                    // แสดงการเตือนว่ายังไม่ได้เลือกรายการตรวจ
                    Swal.fire({
                        icon: 'warning',
                        title: 'ยังไม่ได้เลือกรายการตรวจ',
                        text: 'กรุณาเลือกอย่างน้อย 1 รายการตรวจก่อนบันทึก',
                        confirmButtonColor: '#0075e1'
                    });
                    return false;
                }
                
                // ตรวจสอบการกรอกฟิลด์ที่จำเป็น
                let hasError = false;
                $('.lab-item-checkbox:checked').each(function() {
                    const fieldsContainer = $(this).closest('.lab-item').find('.item-fields');
                    const requiredFields = fieldsContainer.find('[required]');
                    
                    requiredFields.each(function() {
                        if (!$(this).val()) {
                            hasError = true;
                            $(this).addClass('border-red-500');
                        } else {
                            $(this).removeClass('border-red-500');
                        }
                    });
                });
                
                if (hasError) {
                    e.preventDefault();
                    
                    // แสดงการเตือนว่ายังกรอกข้อมูลไม่ครบ
                    Swal.fire({
                        icon: 'error',
                        title: 'กรอกข้อมูลไม่ครบ',
                        text: 'กรุณากรอกข้อมูลในช่องที่มีเครื่องหมาย * ให้ครบถ้วน',
                        confirmButtonColor: '#0075e1'
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>
<?php
// ปิดการเชื่อมต่อกับฐานข้อมูล
mysqli_close($link);
?>