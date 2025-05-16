/**
 * script.js - ไฟล์ JavaScript หลักสำหรับระบบจัดการผู้ป่วย
 * ใช้สำหรับฟังก์ชันทั่วไปที่ใช้หลายหน้า
 */

document.addEventListener('DOMContentLoaded', function() {
  // ฟังก์ชันสลับการแสดงรหัสผ่าน
  initPasswordToggle();
  
  // เพิ่มอนิเมชันให้กับองค์ประกอบ
  initAnimations();
  
  // ฟังก์ชันแจ้งเตือนก่อนออกจากหน้าที่ยังไม่ได้บันทึกข้อมูล
  preventDataLoss();
  
  // กำหนดฟังก์ชันการทำงานของสไลด์บาร์ (หากมี)
  initSidebar();
});

/**
 * ฟังก์ชันสลับการแสดงรหัสผ่าน
 */
function initPasswordToggle() {
  const passwordFields = document.querySelectorAll('input[type="password"]');
  const toggleElements = document.querySelectorAll('#togglePassword');
  
  if (passwordFields.length > 0 && toggleElements.length > 0) {
    toggleElements.forEach(function(toggleEl) {
      toggleEl.addEventListener("click", function() {
        passwordFields.forEach(function(field) {
          if (toggleEl.checked === true) {
            field.setAttribute("type", "text");
          } else {
            field.setAttribute("type", "password");
          }
        });
      });
    });
  }
}

/**
 * เพิ่มอนิเมชันให้กับองค์ประกอบ
 */
function initAnimations() {
  // เพิ่มคลาส animate__fadeIn ให้กับการ์ดหลังจากโหลดหน้าเสร็จ
  const cards = document.querySelectorAll('.card, .bg-white');
  if (cards.length > 0) {
    cards.forEach(function(card, index) {
      setTimeout(function() {
        card.classList.add('fade-in');
      }, 50 * index); // เพิ่มดีเลย์เล็กน้อยสำหรับแต่ละการ์ด
    });
  }
  
  // เพิ่มเอฟเฟกต์ Hover ให้กับรายการเมนู
  const menuItems = document.querySelectorAll('.sidebar li');
  if (menuItems.length > 0) {
    menuItems.forEach(function(item) {
      item.addEventListener('mouseenter', function() {
        this.classList.add('sidebar-transition');
      });
      
      item.addEventListener('mouseleave', function() {
        this.classList.remove('sidebar-transition');
      });
    });
  }
}

/**
 * ฟังก์ชันแจ้งเตือนก่อนออกจากหน้าที่ยังไม่ได้บันทึกข้อมูล
 */
function preventDataLoss() {
  const forms = document.querySelectorAll('form:not([data-prevent-loss="false"])');
  
  if (forms.length > 0) {
    forms.forEach(function(form) {
      let formChanged = false;
      
      // ตรวจสอบการเปลี่ยนแปลงข้อมูลในฟอร์ม
      form.addEventListener('change', function() {
        formChanged = true;
      });
      
      // ยกเลิกการติดตามเมื่อกดปุ่มบันทึก
      form.addEventListener('submit', function() {
        formChanged = false;
      });
      
      // แจ้งเตือนเมื่อผู้ใช้พยายามออกจากหน้าที่มีการแก้ไขข้อมูล
      window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
          // แสดงข้อความเตือน
          e.preventDefault();
          e.returnValue = 'คุณมีข้อมูลที่ยังไม่ได้บันทึก ต้องการออกจากหน้านี้หรือไม่?';
          return e.returnValue;
        }
      });
    });
  }
}

/**
 * กำหนดฟังก์ชันการทำงานของสไลด์บาร์
 */
function initSidebar() {
  const toggleSidebarButton = document.getElementById('toggleSidebar');
  const sidebar = document.querySelector('.sidebar');
  
  if (toggleSidebarButton && sidebar) {
    toggleSidebarButton.addEventListener('click', function() {
      sidebar.classList.toggle('hidden');
      document.querySelector('main').classList.toggle('ml-0');
      document.querySelector('main').classList.toggle('ml-64');
    });
  }
}

/**
 * ฟังก์ชันคำนวณ BMI
 * @param {number} weight - น้ำหนักในหน่วยกิโลกรัม
 * @param {number} height - ส่วนสูงในหน่วยเซนติเมตร
 * @returns {string} - ค่า BMI ทศนิยม 2 ตำแหน่ง
 */
function calculateBMI(weight, height) {
  if (weight > 0 && height > 0) {
    // แปลงส่วนสูงจาก cm เป็น m
    const heightInMeters = height / 100;
    const bmi = weight / (heightInMeters * heightInMeters);
    return bmi.toFixed(2);
  }
  return '';
}

/**
 * ฟังก์ชันแสดงสถานะ BMI
 * @param {number} bmi - ค่า BMI
 * @returns {Object} - สถานะ BMI และสีที่ใช้แสดง
 */
function getBMIStatus(bmi) {
  let status = '';
  let color = '';
  
  // ตรวจสอบสถานะตามช่วง BMI
  if (bmi < 18.5) {
    status = 'น้ำหนักต่ำกว่าเกณฑ์';
    color = 'blue';
  } else if (bmi >= 18.5 && bmi < 25) {
    status = 'น้ำหนักปกติ';
    color = 'green';
  } else if (bmi >= 25 && bmi < 30) {
    status = 'น้ำหนักเกิน';
    color = 'orange';
  } else if (bmi >= 30) {
    status = 'อ้วน';
    color = 'red';
  }
  
  return { status, color };
}

/**
 * ฟังก์ชันแปลงวันที่เป็นรูปแบบไทย
 * @param {string} dateString - วันที่ในรูปแบบ YYYY-MM-DD หรือ Date object
 * @returns {string} - วันที่ในรูปแบบไทย (วัน/เดือน/ปี)
 */
function formatThaiDate(dateString) {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return dateString;
  
  // แปลงเป็นรูปแบบ วัน/เดือน/ปี
  const day = date.getDate().toString().padStart(2, '0');
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const year = date.getFullYear();
  
  return `${day}/${month}/${year}`;
}

/**
 * ฟังก์ชันแสดงข้อความแจ้งเตือน
 * @param {string} title - หัวข้อแจ้งเตือน
 * @param {string} message - ข้อความแจ้งเตือน
 * @param {string} type - ประเภทการแจ้งเตือน (success, error, warning, info)
 */
function showAlert(title, message, type = 'info') {
  // ตรวจสอบว่ามีไลบรารี SweetAlert2 หรือไม่
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: title,
      text: message,
      icon: type,
      confirmButtonColor: '#0075e1'
    });
  } else {
    // ใช้ alert ปกติหากไม่มี SweetAlert2
    alert(`${title}: ${message}`);
  }
}

/**
 * ฟังก์ชันยืนยันการลบข้อมูล
 * @param {string} title - หัวข้อยืนยัน
 * @param {string} message - ข้อความยืนยัน
 * @param {Function} confirmCallback - ฟังก์ชันที่ทำงานเมื่อยืนยัน
 */
function confirmDelete(title, message, confirmCallback) {
  // ตรวจสอบว่ามีไลบรารี SweetAlert2 หรือไม่
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: title,
      html: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'ใช่, ลบข้อมูล',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed && typeof confirmCallback === 'function') {
        confirmCallback();
      }
    });
  } else {
    // ใช้ confirm ปกติหากไม่มี SweetAlert2
    if (confirm(`${title}\n${message}`)) {
      if (typeof confirmCallback === 'function') {
        confirmCallback();
      }
    }
  }
}

/**
 * ฟังก์ชันสร้างกราฟข้อมูล (ถ้ามีไลบรารี Chart.js)
 * @param {string} canvasId - ID ของ canvas สำหรับแสดงกราฟ
 * @param {Object} config - การตั้งค่ากราฟ
 */
function createChart(canvasId, config) {
  if (typeof Chart !== 'undefined') {
    const ctx = document.getElementById(canvasId);
    if (ctx) {
      new Chart(ctx, config);
    }
  }
}