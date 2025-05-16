<?php
// ตัวอย่างโครงสร้างข้อมูลสำหรับหมวดหมู่และรายการตรวจ
$lab_categories = [
    [
        'id' => 1,
        'name' => 'Hematology',
        'items' => [
            ['id' => 101, 'name' => 'Complete Blood Count (CBC)', 'code' => 'CBC'],
            ['id' => 102, 'name' => 'Blood Group', 'code' => 'BG'],
            ['id' => 103, 'name' => 'ESR', 'code' => 'ESR'],
            ['id' => 104, 'name' => 'Coagulation', 'code' => 'COAG']
        ]
    ],
    [
        'id' => 2,
        'name' => 'Chemistry',
        'items' => [
            ['id' => 201, 'name' => 'Liver Function Test', 'code' => 'LFT'],
            ['id' => 202, 'name' => 'Kidney Function Test', 'code' => 'KFT'],
            ['id' => 203, 'name' => 'Lipid Profile', 'code' => 'LIPID'],
            ['id' => 204, 'name' => 'Electrolytes', 'code' => 'ELEC']
        ]
    ],
    [
        'id' => 3,
        'name' => 'Microbiology',
        'items' => [
            ['id' => 301, 'name' => 'Culture and Sensitivity', 'code' => 'CULT'],
            ['id' => 302, 'name' => 'Gram Stain', 'code' => 'GRAM'],
            ['id' => 303, 'name' => 'AFB', 'code' => 'AFB']
        ]
    ],
    [
        'id' => 4,
        'name' => 'Urinalysis',
        'items' => [
            ['id' => 401, 'name' => 'Routine Examination', 'code' => 'URO'],
            ['id' => 402, 'name' => 'Microscopy', 'code' => 'MICRO'],
            ['id' => 403, 'name' => 'Protein', 'code' => 'PROT']
        ]
    ]
];

// ข้อมูลช่องกรอกของรายการตรวจแต่ละประเภท
$lab_item_fields = [
    // CBC fields
    101 => [
        ['name' => 'wbc', 'label' => 'WBC', 'unit' => 'x10^3/μL', 'normal_range' => '4.0-10.0'],
        ['name' => 'rbc', 'label' => 'RBC', 'unit' => 'x10^6/μL', 'normal_range' => '4.2-5.4'],
        ['name' => 'hgb', 'label' => 'Hemoglobin', 'unit' => 'g/dL', 'normal_range' => '13.0-17.0'],
        ['name' => 'hct', 'label' => 'Hematocrit', 'unit' => '%', 'normal_range' => '40.0-50.0'],
        ['name' => 'plt', 'label' => 'Platelets', 'unit' => 'x10^3/μL', 'normal_range' => '150-450']
    ],
    // Blood Group fields
    102 => [
        ['name' => 'abo', 'label' => 'ABO Group', 'unit' => '', 'options' => ['A', 'B', 'AB', 'O']],
        ['name' => 'rh', 'label' => 'Rh Factor', 'unit' => '', 'options' => ['Positive', 'Negative']]
    ],
    // ข้อมูลช่องกรอกสำหรับรายการตรวจอื่นๆ ...
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Test Form</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Custom Styles -->
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .collapse-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .collapse-content.show {
            max-height: 1000px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-6">บันทึกผลตรวจวิเคราะห์</h1>
        
        <!-- หมวดหมู่แท็บ -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="flex border-b">
                    <?php foreach ($lab_categories as $index => $category): ?>
                    <button 
                        class="category-tab px-4 py-3 text-sm font-medium <?= $index === 0 ? 'text-primary-500 border-b-2 border-primary-500' : 'text-gray-500 hover:text-primary-500' ?>" 
                        data-tab="tab-<?= $category['id'] ?>">
                        <?= $category['name'] ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                
                <!-- เนื้อหาแต่ละแท็บ -->
                <?php foreach ($lab_categories as $index => $category): ?>
                <div id="tab-<?= $category['id'] ?>" class="tab-content p-4 <?= $index === 0 ? 'active' : '' ?>">
                    <h2 class="text-lg font-semibold mb-4">เลือกรายการตรวจ <?= $category['name'] ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <?php foreach ($category['items'] as $item): ?>
                        <div class="item-selector bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="checkbox" class="item-checkbox form-checkbox h-5 w-5 text-primary-500 rounded" 
                                           data-item-id="<?= $item['id'] ?>">
                                    <span class="ml-2 text-gray-700"><?= $item['name'] ?> (<?= $item['code'] ?>)</span>
                                </label>
                                <button class="toggle-item text-gray-400 hover:text-gray-600" 
                                        data-target="item-fields-<?= $item['id'] ?>">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            
                            <!-- ส่วนกรอกข้อมูลรายการตรวจ (ซ่อนไว้ก่อน) -->
                            <div id="item-fields-<?= $item['id'] ?>" class="collapse-content mt-4 pl-7 border-l-2 border-gray-200">
                                <?php if (isset($lab_item_fields[$item['id']])): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php foreach ($lab_item_fields[$item['id']] as $field): ?>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    <?= $field['label'] ?>
                                                    <?php if (!empty($field['normal_range'])): ?>
                                                        <span class="text-xs text-gray-500 ml-1">(<?= $field['normal_range'] ?>)</span>
                                                    <?php endif; ?>
                                                </label>
                                                
                                                <?php if (isset($field['options'])): ?>
                                                    <!-- Dropdown for options -->
                                                    <select name="lab_data[<?= $item['id'] ?>][<?= $field['name'] ?>]" 
                                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                                        <option value="">-- เลือก --</option>
                                                        <?php foreach ($field['options'] as $option): ?>
                                                            <option value="<?= $option ?>"><?= $option ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php else: ?>
                                                    <!-- Text input for values -->
                                                    <div class="mt-1 flex rounded-md shadow-sm">
                                                        <input type="text" name="lab_data[<?= $item['id'] ?>][<?= $field['name'] ?>]" 
                                                               class="flex-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                                        <?php if (!empty($field['unit'])): ?>
                                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                                                <?= $field['unit'] ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-gray-500 italic">ไม่มีฟอร์มกรอกข้อมูลสำหรับรายการนี้</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- ปุ่มบันทึก -->
                <div class="p-4 bg-gray-50 border-t flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
                        บันทึกผลการตรวจ
                    </button>
                </div>
            </div>
        </div>
        
        <!-- ส่วนสรุปรายการตรวจที่เลือก -->
        <div class="selected-items-summary bg-white rounded-lg shadow-md p-4 mt-6 hidden">
            <h2 class="text-lg font-semibold mb-4">รายการตรวจที่เลือก</h2>
            <div id="selected-items-list" class="space-y-2">
                <!-- จะถูกเติมโดย JavaScript -->
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // เปลี่ยนแท็บ
            $('.category-tab').click(function() {
                const tabId = $(this).data('tab');
                
                // Activate tab
                $('.category-tab').removeClass('text-primary-500 border-b-2 border-primary-500').addClass('text-gray-500');
                $(this).addClass('text-primary-500 border-b-2 border-primary-500').removeClass('text-gray-500');
                
                // Show tab content
                $('.tab-content').removeClass('active');
                $('#' + tabId).addClass('active');
            });
            
            // Toggle item fields
            $('.toggle-item').click(function() {
                const targetId = $(this).data('target');
                $('#' + targetId).toggleClass('show');
                
                // Change icon
                const icon = $(this).find('i');
                if ($('#' + targetId).hasClass('show')) {
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else {
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
            });
            
            // Checkbox behavior
            $('.item-checkbox').change(function() {
                const itemId = $(this).data('item-id');
                const isChecked = $(this).is(':checked');
                
                if (isChecked) {
                    // Show fields when checked
                    $('#item-fields-' + itemId).addClass('show');
                    $(this).closest('.item-selector').find('.toggle-item i')
                        .removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    
                    updateSelectedItemsSummary();
                } else {
                    // Hide fields when unchecked
                    $('#item-fields-' + itemId).removeClass('show');
                    $(this).closest('.item-selector').find('.toggle-item i')
                        .removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    
                    updateSelectedItemsSummary();
                }
            });
            
            // Update summary of selected items
            function updateSelectedItemsSummary() {
                const selectedItems = $('.item-checkbox:checked');
                
                if (selectedItems.length > 0) {
                    $('.selected-items-summary').removeClass('hidden');
                    
                    // Clear previous list
                    $('#selected-items-list').empty();
                    
                    // Add each selected item
                    selectedItems.each(function() {
                        const itemId = $(this).data('item-id');
                        const itemName = $(this).closest('label').text().trim();
                        
                        $('#selected-items-list').append(`
                            <div class="p-2 bg-gray-50 rounded flex justify-between items-center">
                                <span>${itemName}</span>
                                <span class="text-primary-500 text-sm">กรอกข้อมูลแล้ว</span>
                            </div>
                        `);
                    });
                } else {
                    $('.selected-items-summary').addClass('hidden');
                }
            }
        });
    </script>
</body>
</html>